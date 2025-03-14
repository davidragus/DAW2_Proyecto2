<template>
    <div>
        <Button class="cashier-button" buttonColor="yellow" buttonStyle="filled" @click="visible = true">CASHIER</Button>
        <Dialog v-model:visible="visible" modal header="ADD CHIPS" :style="{ width: '400px' , backgroundColor: '#212121', color: 'white' , borderColor: '#3B3B3B'}" @update:visible="onDialogClose">
            <form class="cashier-container p-4" @submit.prevent="deposit">
                <h3>Pick your deposit:</h3>
                <div class="deposit-options">
                    <Button v-for="amount in [10, 25, 50, 100, 200]" :key="amount" :id="amount" :class="['deposit-button', { 'selected': customDeposit.value === amount }]" @click="setDeposit($event, amount)">{{ amount }}€</Button>
                </div>
                <div class="custom-deposit">
                    <label for="customDeposit">Custom deposit:</label>
                    <input type="number" id="customDeposit" v-model="customDeposit" class="form-control" @input="selectButton" required />
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
                <Button class="deposit-button" type="submit">DEPOSIT</Button>
            </form>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import Button from './Button.vue';
import Dialog from 'primevue/dialog';
import { authStore } from "../store/auth";

const visible = ref(false);
const customDeposit = ref(0);
const cardNumber = ref('');
const expirationDate = ref('');
const cvc = ref('');
const confirmName = ref(false);

const user = authStore().user;
const userFullName = computed(() => `${user.name} ${user.surname1} ${user.surname2}`);

const setDeposit = (event, amount) => {
    event.preventDefault();
    customDeposit.value = amount;
    deselectButtons();
    document.getElementById(amount).classList.add('selected');
};
const deselectButtons = () => {
    const buttons = document.getElementsByClassName('deposit-button');
    for (let i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove('selected');
    }
};
const selectButton = () => {
    // Lógica para deseleccionar el botón
    deselectButtons();
    // Lógica para seleccionar el botón
    if ([10, 25, 50, 100, 200].includes(customDeposit.value)) {
        document.getElementById(customDeposit.value).classList.add('selected');
    }
};

const deposit = () => {
    // Lógica para realizar el depósito
    if (
        customDeposit.value != 0 &&
        customDeposit.value != null &&
        cardNumber.value != '' &&
        expirationDate.value != '' &&
        cvc.value != '' &&
        confirmName.value
    ) {
        console.log('Deposit:', customDeposit.value, cardNumber.value, expirationDate.value, cvc.value, confirmName.value);
        visible.value = false;
        customDeposit.value = 0;
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
.cashier-button {
    padding: 10px 20px;
    font-size: 16px;
    font-weight: 900;
    cursor: pointer;
}

.cashier-container {
    padding: 20px;
}

.deposit-options {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.deposit-button {
    background-color: #f0f0f0;
    color: black;
    border: 1px solid #ccc;
    padding: 10px;
    cursor: pointer;
}

.deposit-button.selected {
    background-color: #3A3A3A;
}

.custom-deposit {
    margin-bottom: 10px;
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

.deposit-button {
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
.form-check-input{
  background-color: #414141;
  border-color: #313131;
}
.form-check-input:checked{
  background-color:red;
  border-color: #cb0000;
}
.form-check-input:focus{
  border-color: #313131;
  outline: 0;
  box-shadow: 0 0 0 0.25rem #31313133;
}
</style>
