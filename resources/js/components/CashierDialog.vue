<template>
    <div>
        <Button class="cashier-button" buttonColor="yellow" buttonStyle="filled" @click="visible = true">CASHIER</Button>
        <Dialog v-model:visible="visible" modal header="ADD CHIPS" :style="{ width: '400px' , backgroundColor: '#212121', color: 'white' , borderColor: '#3B3B3B'}" @update:visible="onDialogClose">
            <form class="cashier-container p-4" @submit.prevent="submitForm">
                <h3>Pick your deposit:</h3>
                <div class="deposit-options">
                    <Button v-for="amount in [10, 25, 50, 100, 200]" :key="amount" :id="amount" :class="['deposit-button', { 'selected': customDeposit.value === amount }]" @click="setDeposit($event, amount)">{{ amount }}€</Button>
                </div>
                <div class="custom-deposit">
                    <label for="customDeposit">Custom deposit:</label>
                    <input type="number" id="customDeposit" min="10" v-model="customDeposit" class="form-control" @input="selectButton" required />
                    <span v-if="errors.customDeposit" class="error-text">{{ errors.customDeposit }}</span>
                </div>
                <div class="card-details">
                    <input type="text" placeholder="Card number" class="form-control" v-model="cardNumber" required />
                    <span v-if="errors.cardNumber" class="error-text">{{ errors.cardNumber }}</span>
                    <input type="text" id="expiration_date" placeholder="Expiration date" class="form-control" v-model="expirationDate" @input="formatExpirationDate" required />
                    <span v-if="errors.expirationDate" class="error-text">{{ errors.expirationDate }}</span>
                    <input type="text" placeholder="CVV" class="form-control" v-model="cvv" required />
                    <span v-if="errors.cvv" class="error-text">{{ errors.cvv }}</span>
                </div>
                <p class="d-flex justify-content-center username-text">{{ userFullName }}</p>
                <div class="form-check">
                    <input type="checkbox" id="confirmName" class="form-check-input" v-model="confirmName" required />
                    <label for="confirmName" class="form-check-label">I confirm that the full name of the owner of the card is the same as the full name of the account.</label>
                    <span v-if="errors.confirmName" class="error-text">{{ errors.confirmName }}</span>
                </div>
                <Button class="deposit-button-submit" type="submit">DEPOSIT {{ chipsNumber }}</Button>
            </form>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import Button from './Button.vue';
import Dialog from 'primevue/dialog';
import { authStore } from "../store/auth";

import { useRoute, useRouter } from 'vue-router';   
import * as yup from 'yup';

const props = defineProps({
	show: Boolean
});

const visible = ref(false);
const customDeposit = ref(10);
const cardNumber = ref('');
const expirationDate = ref('');
const cvv = ref('');
const confirmName = ref(false);
const chipsNumber = ref(customDeposit.value*10);

const router = useRouter();
watch(() => customDeposit.value, (newVal) => {
	chipsNumber.value = newVal * 10;
});
const user = authStore().user;
const userFullName = computed(() => `${user.name} ${user.surname1} ${user.surname2 != null ? user.surname2 : ''}`);

watch(() => props.show, (newVal) => {
	visible.value = newVal;
	props.show = false;
});

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
	deselectButtons();
	if ([10, 25, 50, 100, 200].includes(customDeposit.value)) {
		document.getElementById(customDeposit.value).classList.add('selected');
	}
};

const deposit = async () => {
	if (
		customDeposit.value != 0 &&
		customDeposit.value != null &&
		cardNumber.value != '' &&
		expirationDate.value != '' &&
		cvv.value != '' &&
		confirmName.value
	) {
		try {
			// console.log(expirationDate.value);
			let expiration_date_split = expirationDate.value.split('/');
			let expiration_date_formatted = expiration_date_split[1] + '/' + expiration_date_split[0] + '/01';
			const response = await axios.post('/api/transactions', {
				user_id: user.id,
				type: 'DEPOSIT',
				money: customDeposit.value,
				chips: chipsNumber.value,
				card_number: cardNumber.value,
				cvv: cvv.value,
				expiration_date: expiration_date_formatted
			});
			console.log('Transaction successful:', response.data);
			console.log(`${user.chips} - ${chipsNumber.value}`);
			const responseUser = await axios.put(`/api/users/updateChips/${user.id}`, {
				// name: user.name,
				// surname1: user.surname1,
				// surname2: user.surname2,
				// birthdate: user.birthdate,
				// country: user.country,
				// username: user.username,
				// email: user.email,
				// phone_number: user.phone_number,
				chips: user.chips + chipsNumber.value
			});
			authStore().user.chips += chipsNumber.value;
			console.log('User updated:', responseUser.data);
			visible.value = false;
			customDeposit.value = 10;
			cardNumber.value = '';
			expirationDate.value = '';
			expiration_date_split = '';
			expiration_date_formatted = '';
			cvv.value = '';
			confirmName.value = false;
			//router.go();
		} catch (error) {
			console.error('Transaction failed:', error);
		}
	}
};

const onDialogClose = (newValue) => {
	if (!newValue) {
		visible.value = false;
	}
};
const formatExpirationDate = (event) => {
	let value = event.target.value.replace(/[^\d]/g, '');
	if (value.length >= 2) {
		value = value.slice(0, 2) + '/' + value.slice(2, 4);
	}
	event.target.value = value;
	expirationDate.value = value;
};

const schema = yup.object({
    customDeposit: yup.number().required('Custom deposit is required').min(10, 'Minimum deposit is 10€').max(10000, 'Maximum deposit is 10000€'),
    cardNumber: yup.string().required('Card number is required').max(255),
    expirationDate: yup.string().required('Expiration date is required').max(255),
    cvv: yup.string().required('CVV is required').max(255),
    confirmName: yup.boolean().required('You must confirm that the full name of the owner of the card is the same as the full name of the account.')
});
const errors = ref({});
const validateForm = async () => {
    errors.value = {};
    try {
        await schema.validate(
            {
                customDeposit: customDeposit.value,
                cardNumber: cardNumber.value,
                expirationDate: expirationDate.value,
                cvv: cvv.value,
                confirmName: confirmName.value,
            },
            { abortEarly: false }
        );
        return true;
    } catch (validationErrors) {
        if (Array.isArray(validationErrors.inner)) {
            validationErrors.inner.forEach((error) => {
                errors.value[error.path] = error.message;
            });
        }
        return false;
    }
};

const submitForm = async () => {
    const isValid = await validateForm();
    if (isValid) {
		console.log("entrando a deposit")
        await deposit();
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
	background-color: #3A3A3A;
	color: white;
	border: none;
	padding: 10px;
	cursor: pointer;
	width: 100%;
	text-align: center;
}

.deposit-button:hover {
	background-color: #3A3A3A;
	color: yellow;
	border: 1px solid yellow;
	padding: 10px;
	cursor: pointer;
	width: 100%;
	text-align: center;
}

.deposit-button.selected {
	background-color: #ff0000;
}

.deposit-button.selected:hover {
	background-color: #ff0000;
	color: white;
	border: none;
	padding: 10px;
	cursor: pointer;
	width: 100%;
	text-align: center;
}

.deposit-button-submit {
	background-color: #ff0000;
	color: white;
	border: none;
	padding: 10px;
	cursor: pointer;
	width: 100%;
	text-align: center;
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
</style>