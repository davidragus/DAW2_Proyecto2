<template>
	<div>
		<Button @click="visible = true" class="register-button" buttonColor="yellow" buttonStyle="outlined"
			buttonSize="normal">REGISTER</Button>
		<Dialog v-model:visible="visible" modal header="Sign Up"
			:style="{ width: '400px', backgroundColor: '#212121', color: 'white', borderColor: '#3B3B3B' }"
			:panelStyle="{ backgroundColor: 'red' }" @update:visible="onDialogClose">
			<div class="signup-container p-4">
				<form @submit.prevent="submitRegisterYup">
					<div class="mb-3">
						<Dropdown v-model="registerForm.country" :options="countries" placeholder="Country of residence"
							class="w-100"
							:style="{ backgroundColor: '#313131', color: 'white', borderColor: '#414141', placeholderColor: '' }"
							pt:option:id="option-dropdown-register"
							pt:listContainer:id="list-container-dropdown-register"
							pt:overlay:id="overlay-dropdown-register" pt:root:id="root-dropdown-register" />
					</div>
					<input v-model="registerForm.dni" type="text" class="form-control mb-3"
						placeholder="National Identity Card Number" required />

					<div class="mb-3">
						<FileUpload @select="onSelectValidationImage" mode="basic">

						</FileUpload>
						<!-- <input id=" idImage" type="file" @change="handleFileUpload"
							class="form-control image-input" required />
						<label for="idImage" class="form-control mb-3"><span class="file-span">Choose a
								file</span>Upload your ID image</label>
						<p v-if="fileName" class="text-white mt-2">Selected file: {{ fileName }}</p> -->
					</div>
					<div class="border rounded name-container">
						<input v-model="registerForm.name" type="text"
							class="form-control first-name-rounded border-0 border-bottom" placeholder="Name"
							required />
						<input v-model="registerForm.surname1" type="text"
							class="form-control rounded-0 border-0 border-bottom" placeholder="Surname" required />
						<input v-model="registerForm.surname2" type="text" class="form-control border-0"
							placeholder="Second surname (optional)" />
					</div>
					<div class="d-flex justify-content-center rounded border my-3 gender-container">
						<div class="gender-option left-gender" @click="registerForm.gender = 'M'"
							:class="{ active: registerForm.gender === 'M' }">
							♂
						</div>
						<div class="gender-option" @click="registerForm.gender = 'F'"
							:class="{ active: registerForm.gender === 'F' }">
							♀
						</div>
						<div class="gender-option right-gender" @click="registerForm.gender = 'N'"
							:class="{ active: registerForm.gender === 'N' }">
							⚧
						</div>
					</div>

					<div class="d-flex mb-3">
						<input v-model="registerForm.day" type="text" class="form-control me-1" placeholder="Day (dd)"
							required />
						<input v-model="registerForm.month" type="text" class="form-control mx-1"
							placeholder="Month (mm)" required />
						<input v-model="registerForm.year" type="text" class="form-control ms-1"
							placeholder="Year (yyyy)" required />
					</div>

					<input v-model="registerForm.phone_number" type="text" class="form-control mb-3"
						placeholder="Phone number" required />
					<input v-model="registerForm.email" type="email" class="form-control mb-3" placeholder="Email"
						required />
					<input v-model="registerForm.username" type="text" class="form-control mb-3" placeholder="Username"
						required />

					<div class="d-flex mb-3">
						<div class="password-container me-1">
							<input v-model="registerForm.password" :type="passwordFieldType" class="form-control"
								placeholder="Password" required />
							<i class="fa fa-eye password-toggle" @mousedown="showPassword" @mouseup="hidePassword"
								@mouseleave="hidePassword"></i>
						</div>
						<input v-model="registerForm.password_confirmation" type="password" class="form-control ms-1"
							placeholder="Confirm password" required />
					</div>

					<div class="form-check mb-3">
						<input v-model="registerForm.terms" type="checkbox" class="form-check-input" required />
						<label class="form-check-label">I confirm that I am over the majority age of my country and that
							I accept the Terms and Conditions and the Privacy Policy</label>
					</div>

					<PrimeButton type="submit" label="SIGN UP" class="w-100 register-button" :disabled="processing"
						:style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }" @click="submitRegister"/>

					<p class="text-center mt-3">Are you already registered? <a href="#" class="login ms-2"
							@click.prevent="openLoginDialog">Log in</a></p>
				</form>
			</div>
		</Dialog>
	</div>
</template>

<script setup>
import { ref, defineEmits, watch } from 'vue';
import * as yup from 'yup';
import Dropdown from 'primevue/dropdown';
import Button from '../components/Button.vue';
import PrimeButton from 'primevue/button';
import Dialog from 'primevue/dialog';
import useAuth from '@/composables/auth';

const props = defineProps(['visible']);
const visible = ref(props.visible);
const fileName = ref('');

watch(() => props.visible, (newVal) => {
	visible.value = newVal;
});

const onDialogClose = (newValue) => {
	if (!newValue) {
		visible.value = false;
		emit('update:visible', false);
	}
};

const { registerForm, validationErrors, processing, submitRegister } = useAuth();
const countries = ref(['Spain', 'USA', 'UK', 'France', 'Mexico', 'Peru']);
const emit = defineEmits(['open-login-dialog', 'update:visible']);

// const handleFileUpload = (event) => {
// 	registerForm.idImage = event.target.files[0];
// 	fileName.value = event.target.files[0].name;
// };

const onSelectValidationImage = (event) => {
	registerForm.validationImages = event.files;
};
const openLoginDialog = () => {
	visible.value = false;
	emit('update:visible', false);
	emit('open-login-dialog');
};

const passwordFieldType = ref('password');

const showPassword = () => {
	passwordFieldType.value = 'text';
};

const hidePassword = () => {
	passwordFieldType.value = 'password';
};

const schema = yup.object({
	username: yup.string().required('Username is required').max(255),
	name: yup.string().required('Name is required').max(255),
	surname1: yup.string().required('First surname is required').max(255),
	surname2: yup.string().nullable().max(255),
	email: yup.string().required('Email is required').email('Email must be valid').max(255),
	dni: yup.string().required('DNI is required').max(255),
	gender: yup.string().required('Gender is required').oneOf(['N', 'M', 'F']),
	phone_number: yup.string().required('Phone number is required').max(255),
	password: yup.string().required('Password is required').min(8, 'Password must be at least 8 characters'),
	password_confirmation: yup.string().oneOf([yup.ref('password'), null], 'Passwords must match'),
	country: yup.string().required('Country is required').max(255),
});
</script>

<style scoped>
.signup-container {
	padding: 20px;
}

.gender-option {
	background-color: #313131;
	flex: 1;
	text-align: center;
	cursor: pointer;
	padding: 0px;
	margin: 0;
	font-size: 20px;
	border: 1px solid transparent;
}

.gender-option.active {
	background-color: #ff0000;
	color: white;
	border: 1px solid #ff0000;
}

.left-gender {
	border-radius: 0.375rem 0 0 0.375rem;
	border-right: 1px solid #414141;
}

.right-gender {
	border-radius: 0 0.375rem 0.375rem 0;
	border-left: 1px solid #414141;
}

.border-bottom {
	border-bottom: 1px solid #414141 !important;
}

.first-name-rounded {
	border-radius: 0.375rem 0.375rem 0 0;
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

.form-control:focus {
	box-shadow: none;
	border-color: #ced4da;
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

.login {
	color: white;
	text-decoration: underline !important;
}

.login:hover {
	color: red;
}

.name-container {
	border: 1px solid #414141 !important;
}

.gender-container {
	border: 1px solid #414141 !important;
}

.image-input {
	display: none;
}

.file-span {
	padding: 8px;
	color: white;
	background-color: #414141;
	margin-right: 10px;
}

.p-select-option {
	background-color: #313131;
	color: white;
}

.register-button {
	font-size: 16px;
	padding: 10px 20px;
}

.password-container {
	position: relative;
	width: 100%;
}

.password-toggle {
	position: absolute;
	top: 50%;
	right: 10px;
	transform: translateY(-50%);
	cursor: pointer;
	color: #bcbcbc;
}

@media (max-width: 768px) {
	.register-button {
		font-size: 14px;
		padding: 5px 10px;
	}
}
</style>