<template>
    <div>
        <Dialog v-model:visible="visible" modal header="WITHDRAW" :style="{ width: '400px' , backgroundColor: '#212121', color: 'white' , borderColor: '#3B3B3B'}" @update:visible="onDialogClose">
            <form class="withdraw-container p-4" @submit.prevent="withdraw">
                <h3>Amount of chips to withdraw:</h3>
                <div class="withdraw-amount">
                    <img src="/images/chips2.png" alt="icon of chips" class="icon-24 me-2"> 
                    <input type="number" v-model="chips" :max=authStore().user.chips min="0" class="form-control chips-input" required />
                    <span class="equals">=</span>
                    <span class="amount">{{ (chips * 0.1).toFixed(2) }}€</span>
                </div>
                <div class="card-details">
                    <input type="text" placeholder="Card number" class="form-control" v-model="cardNumber" required />
                    <input type="text" placeholder="Expiration date" class="form-control" v-model="expirationDate" required />
                    <input type="text" placeholder="CVC" class="form-control" v-model="cvc" required />
                </div>
                <p class="d-flex justify-content-center username-text">{{ userFullName }}</p>
                <div class="form-check">
                    <input type="checkbox" id="confirmName" class="form-check-input" v-model="confirmName" required />
                    <label for="confirmName" class="form-check-label">I confirm that the full name of the owner of the card is the same as the full name of the account.</label>
                </div>
                <Button class="withdraw-button" type="submit">WITHDRAW</Button>
            </form>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import Button from './Button.vue';
import Dialog from 'primevue/dialog';
import { authStore } from "../store/auth";

const props = defineProps({
    show: Boolean
});

const visible = ref(false);
const chips = ref(0);
const cardNumber = ref('');
const expirationDate = ref('');
const cvc = ref('');
const confirmName = ref(false);

const user = authStore().user;
const userFullName = computed(() => `${user.name} ${user.surname1} ${user.surname2 = null ? user.surname2 : ''}`);

watch(() => props.show, (newVal) => {
    visible.value = newVal;
    props.show = false;
});

const withdraw = () => {
    if (
        chips.value > 0 &&
        cardNumber.value != '' &&
        expirationDate.value != '' &&
        cvc.value != '' &&
        confirmName.value
    ) {
        console.log('Withdraw:', chips.value, cardNumber.value, expirationDate.value, cvc.value, confirmName.value);
        visible.value = false;
        chips.value = 0;
        cardNumber.value = '';
        expirationDate.value = '';
        cvc.value = '';
        confirmName.value = false;
    }
};

const onDialogClose = (newValue) => {
    if (!newValue) {
        visible.value = false;
    }
};
</script>

<style scoped>
.withdraw-container {
    padding: 20px;
}

.withdraw-amount {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.chips-icon {
    font-size: 24px;
}

.chips-input {
    width: 60px;
    text-align: center;
}

.equals {
    font-size: 24px;
}

.amount {
    font-size: 24px;
}

.card-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 10px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #414141;
    border-radius: 5px;
    background-color: #313131;
    color: white;
}

.form-control::placeholder {
    color: #bcbcbc;
}

.form-check {
    margin-bottom: 10px;
}

.withdraw-button {
    background-color: #ff0000;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    width: 100%;
    text-align: center;
}

.username-text {
    font-size: 20px;
}

.form-check-input {
    background-color: #414141;
    border-color: #313131;
}

.form-check-input:checked {
    background-color: red;
    border-color: #cb0000;
}

.form-check-input:focus {
    border-color: #313131;
    outline: 0;
    box-shadow: 0 0 0 0.25rem #31313133;
}
.icon-24{
    width: 24px;
    height: 24px;
}
</style>