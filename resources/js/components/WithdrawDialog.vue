<template>
	<div>
		<Dialog v-model:visible="visible" modal header="WITHDRAW"
			:style="{ width: '400px', backgroundColor: '#212121', color: 'white', borderColor: '#3B3B3B' }"
			@update:visible="onDialogClose" appendTo="self">
			<form class="withdraw-container p-4" @submit.prevent="withdraw">
				<h3>Amount of chips to withdraw:</h3>
				<div class="withdraw-amount">
					<img src="/images/chips2.png" alt="icon of chips" class="icon-24 me-2">
					<input type="number" v-model="chips" :max="authStore().user.chips" min="0"
						class="form-control chips-input" required />
					<span class="equals">=</span>
					<span class="amount">{{ (chips * 0.09).toFixed(2) }}€</span>
				</div>
				<div v-if="errors.chips" class="text-danger mt-1">{{ errors.chips }}</div>
				<div class="card-details">
					<input type="text" placeholder="Card number" class="form-control" v-model="cardNumber" required />
					<div v-if="errors.cardNumber" class="text-danger mt-1">{{ errors.cardNumber }}</div>
					<input type="text" placeholder="Expiration date" class="form-control" v-model="expirationDate"
						@input="formatExpirationDate" required />
					<div v-if="errors.expirationDate" class="text-danger mt-1">{{ errors.expirationDate }}</div>
					<input type="text" placeholder="CVC" class="form-control" v-model="cvc" required />
					<div v-if="errors.cvc" class="text-danger mt-1">{{ errors.cvc }}</div>
				</div>
				<p class="d-flex justify-content-center username-text">{{ userFullName }}</p>
				<div class="form-check">
					<input type="checkbox" id="confirmName" class="form-check-input" v-model="confirmName" required />
					<label for="confirmName" class="form-check-label">I confirm that the full name of the owner of the
						card is the same as the full name of the account.</label>
				</div>
				<div v-if="errors.confirmName" class="text-danger mt-1">{{ errors.confirmName }}</div>
				<Button class="withdraw-button" type="submit">WITHDRAW</Button>
			</form>
		</Dialog>
	</div>
</template>

<script setup>
import { ref, computed, watch, defineEmits } from 'vue';
import Button from './Button.vue';
import Dialog from 'primevue/dialog';
import { authStore } from "../store/auth";
import * as yup from 'yup';
import { ro } from 'yup-locales';
import { useRoute, useRouter } from 'vue-router';

const router = useRouter();
const props = defineProps({
	show: Boolean
});

const emit = defineEmits(['update:visible']);

const visible = ref(props.show);
const chips = ref(0);
const cardNumber = ref('');
const expirationDate = ref('');
const cvc = ref('');
const confirmName = ref(false);
const errors = ref({});

const user = authStore().user;
const userFullName = computed(() => `${user.name} ${user.surname1} ${user.surname2 ? user.surname2 : ''}`);

watch(() => props.show, (newVal) => {
	visible.value = newVal;
});

watch(visible, (newVal) => {
	emit('update:visible', newVal);
});

const schema = yup.object().shape({
	chips: yup.number().required('Amount of chips is required').max(authStore().user.chips, 'You cannot withdraw more chips than you have').min(100, 'You cannot withdraw less than 100 chips'),
	cardNumber: yup.string().required('Card number is required'),
	expirationDate: yup.string().required('Expiration date is required'),
	cvc: yup.string().required('CVC is required'),
	confirmName: yup.boolean().oneOf([true], 'You must confirm the name')
});

const withdraw = async () => {
	errors.value = {};
	try {
		console.log(errors.value);
		await schema.validate({ chips: chips.value, cardNumber: cardNumber.value, expirationDate: expirationDate.value, cvc: cvc.value, confirmName: confirmName.value }, { abortEarly: false });
		if (
			chips.value != 0 &&
			chips.value != null &&
			cardNumber.value != '' &&
			expirationDate.value != '' &&
			cvc.value != '' &&
			confirmName.value
		) {
			try {
				console.log(expirationDate.value);
				let expiration_date_split = expirationDate.value.split('/');
				let expiration_date_formatted = expiration_date_split[1] + '/' + expiration_date_split[0] + '/01';
				const response = await axios.post('/api/transactions', {
					user_id: user.id,
					type: 'WITHDRAWAL',
					money: chips.value * 0.09,
					chips: chips.value,
					card_number: cardNumber.value,
					cvv: cvc.value,
					expiration_date: expiration_date_formatted
				});
				console.log('Transaction successful:', response.data);
				const responseUser = await axios.put(`/api/users/updateChips/${user.id}`, {
					// name: user.name,
					// surname1: user.surname1,
					// surname2: user.surname2,
					// birthdate: user.birthdate,
					// country: user.country,
					// username: user.username,
					// email: user.email,
					// phone_number: user.phone_number,
					chips: user.chips - chips.value
				});
				user.chips -= chips.value;
				console.log('User updated:', responseUser.data);
				visible.value = false;
				resetForm();
				router.go();
			} catch (error) {
				console.log(errors.value);
				console.error('Transaction failed:', error);
			}
		}
	} catch (validationErrors) {

		validationErrors.inner.forEach(error => {
			errors.value[error.path] = error.message;
		});
	}
};

const onDialogClose = (newValue) => {
	if (!newValue) {
		visible.value = false;
	}
};

const resetForm = () => {
	chips.value = 0;
	cardNumber.value = '';
	expirationDate.value = '';
	cvc.value = '';
	confirmName.value = false;
};
const formatExpirationDate = (event) => {
	let value = event.target.value.replace(/[^\d]/g, '');
	if (value.length >= 2) {
		value = value.slice(0, 2) + '/' + value.slice(2, 4);
	}
	event.target.value = value;
	expirationDate.value = value;
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

.icon-24 {
	width: 24px;
	height: 24px;
}

.text-danger {
	color: red;
}
</style>