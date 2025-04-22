<template>
	<div>
		<Button @click="openRegisterDialog" class="register-button" buttonColor="yellow" buttonStyle="outlined"
			buttonSize="normal">REGISTER</Button>
		<Dialog v-model:visible="visible" modal header="Sign Up"
			:style="{ width: '400px', backgroundColor: '#212121', color: 'white', borderColor: '#3B3B3B' }"
			:panelStyle="{ backgroundColor: 'red' }" @update:visible="onDialogClose">
			<div class="signup-container p-4">
				<form @submit.prevent="submitRegisterYup">
					<div class="mb-3">
						<Select v-model="registerForm.country" :options="countries" optionLabel="name"
							optionValue="code" placeholder="Country of residence" class="w-100"
							:style="{ backgroundColor: '#313131', color: 'white', borderColor: '#414141' }" />
						<p v-if="validationErrors.country" class="text-danger mt-1">{{ validationErrors.country }}</p>
					</div>
					<div class="mb-3">
						<input v-model="registerForm.dni" type="text" class="form-control"
							placeholder="National Identity Card Number" />
						<p v-if="validationErrors.dni" class="text-danger mt-1">{{ validationErrors.dni }}</p>
					</div>

					<div class="mb-3">
						<h5 class="text-white">Upload DNI Images</h5>
						<h6>Front image DNI</h6>
						<!-- Imagen frontal del DNI -->
						<FileUpload mode="basic" name="dni_front" accept="image/*" chooseLabel="Upload DNI Front"
							@select="onSelectValidationImage($event, 'front')" class="w-100"
							:style="{ backgroundColor: 'yellow', borderColor: 'yellow', color: 'black' }" />

						<h6>Back image DNI</h6>
						<!-- Imagen trasera del DNI -->
						<FileUpload mode="basic" name="dni_back" accept="image/*" chooseLabel="Upload DNI Back"
							@select="onSelectValidationImage($event, 'back')" class="w-100"
							:style="{ backgroundColor: 'yellow', borderColor: 'yellow', color: 'black' }" />

						<h6>Face picture</h6>
						<!-- Imagen del rostro -->
						<FileUpload mode="basic" name="face_image" accept="image/*" chooseLabel="Upload Face Image"
							@select="onSelectValidationImage($event, 'face')" class="w-100"
							:style="{ backgroundColor: 'yellow', borderColor: 'yellow', color: 'black' }" />
						<p v-if="validationErrors.validationImages" class="text-danger mt-1">{{
							validationErrors.validationImages }}</p>
					</div>
					<div class="border rounded name-container">
						<input v-model="registerForm.name" type="text"
							class="form-control first-name-rounded border-0 border-bottom" placeholder="Name" />
						<input v-model="registerForm.surname1" type="text"
							class="form-control rounded-0 border-0 border-bottom" placeholder="Surname" />
						<input v-model="registerForm.surname2" type="text" class="form-control border-0"
							placeholder="Second surname (optional)" />
					</div>
					<p v-if="validationErrors.name" class="text-danger mt-1">{{ validationErrors.name }}</p>
					<p v-if="validationErrors.surname1" class="text-danger mt-1">{{ validationErrors.surname1 }}</p>
					<p v-if="validationErrors.surname2" class="text-danger mt-1">{{ validationErrors.surname2 }}</p>
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
					<p v-if="validationErrors.gender" class="text-danger mt-1">{{ validationErrors.gender }}</p>

					<div class="d-flex mb-3">
						<input v-model="registerForm.day" type="text" class="form-control me-1"
							placeholder="Day (dd)" />
						<input v-model="registerForm.month" type="text" class="form-control mx-1"
							placeholder="Month (mm)" />
						<input v-model="registerForm.year" type="text" class="form-control ms-1"
							placeholder="Year (yyyy)" />
					</div>
					<p v-if="validationErrors.day || validationErrors.month || validationErrors.year"
						class="text-danger mt-1">
						{{ validationErrors.day || validationErrors.month || validationErrors.year }}
					</p>

					<input v-model="registerForm.phone_number" type="text" class="form-control mb-3"
						placeholder="Phone number" />
					<p v-if="validationErrors.phone_number" class="text-danger mt-1">{{ validationErrors.phone_number }}
					</p>
					<input v-model="registerForm.email" type="email" class="form-control mb-3" placeholder="Email" />
					<p v-if="validationErrors.email" class="text-danger mt-1">{{ validationErrors.email }}</p>
					<input v-model="registerForm.username" type="text" class="form-control mb-3"
						placeholder="Username" />
					<p v-if="validationErrors.username" class="text-danger mt-1">{{ validationErrors.username }}</p>

					<div class="d-flex mb-3">
						<div class="password-container me-1">
							<input v-model="registerForm.password" :type="passwordFieldType" class="form-control"
								placeholder="Password" />
							<i class="fa fa-eye password-toggle" @mousedown="showPassword" @mouseup="hidePassword"
								@mouseleave="hidePassword"></i>
						</div>
						<input v-model="registerForm.password_confirmation" type="password" class="form-control ms-1"
							placeholder="Confirm password" />
					</div>
					<p v-if="validationErrors.password" class="text-danger mt-1">{{ validationErrors.password }}</p>
					<p v-if="validationErrors.password_confirmation" class="text-danger mt-1">
						{{ validationErrors.password_confirmation }}
					</p>

					<div class="form-check mb-3">
						<input v-model="registerForm.terms" type="checkbox" class="form-check-input" />
						<label class="form-check-label">I confirm that I am over the majority age of my country and that
							I accept the Terms and Conditions and the Privacy Policy</label>
					</div>
					<p v-if="validationErrors.terms" class="text-danger mt-1">{{ validationErrors.terms }}</p>

					<PrimeButton type="submit" label="SIGN UP" class="w-100 register-button" :disabled="processing"
						:style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }" />

					<p class="text-center mt-3">Are you already registered? <a href="#" class="login ms-2"
							@click.prevent="openLoginDialog">Log in</a></p>
				</form>
			</div>
		</Dialog>
	</div>
</template>

<script setup>
import { ref, defineEmits, watch, onMounted } from 'vue';
import * as yup from 'yup';
import Dropdown from 'primevue/dropdown';
import Select from 'primevue/select';
import Button from '../components/Button.vue';
import PrimeButton from 'primevue/button';
import Dialog from 'primevue/dialog';
import useAuth from '@/composables/auth';
import useCountries from '@/composables/countries';

const props = defineProps(['visible']);
const visible = ref(props.visible);

watch(() => props.visible, (newVal) => {
	visible.value = newVal;
});

onMounted(() => {
	getCountries();
});

const onDialogClose = (newValue) => {
	if (!newValue) {
		visible.value = false;
		emit('update:visible', false);
	}
};

const { registerForm, validationErrors, processing, submitRegister } = useAuth();
const { getCountries, countries } = useCountries();
const emit = defineEmits(['open-login-dialog', 'update:visible']);

const openRegisterDialog = () => {
	visible.value = true;
	emit('update:visible', true);
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

const onSelectValidationImage = (event, type) => {
	let index;

	// Determinar el índice según el tipo de imagen
	if (type === 'front') {
		index = 0;
	} else if (type === 'back') {
		index = 1;
	} else if (type === 'face') {
		index = 2;
	} else {
		console.error('Invalid image type');
		return;
	}

	// Reemplazar o agregar la imagen en el índice correspondiente
	if (event.files && event.files[0]) {
		registerForm.validationImages[index] = event.files[0];
		// console.log(`Validation Images:`, JSON.stringify(registerForm.validationImages, null, 2));
	}
};

const schema = yup.object({
	username: yup.string().required('Username is required').max(255).min(9),
	name: yup.string().required('Name is required').max(255),
	surname1: yup.string().required('First surname is required').max(255),
	surname2: yup.string().nullable().max(255),
	email: yup
		.string()
		.required('Email is required')
		.email('Email must be valid')
		.max(255),
	dni: yup
		.string()
		.required('DNI is required')
		.matches(/^\d{8}[A-Za-z]$/, 'DNI must have 8 digits followed by a letter')
		.max(9, 'DNI must be exactly 9 characters'),
	gender: yup.string().required('Gender is required').oneOf(['N', 'M', 'F']),
	phone_number: yup.string().required('Phone number is required').max(255),
	password: yup
		.string()
		.required('Password is required')
		.min(8, 'Password must be at least 8 characters'),
	password_confirmation: yup
		.string()
		.oneOf([yup.ref('password'), null], 'Passwords must match'),
	country: yup.string().required('Country is required').max(255),
	terms: yup.boolean().oneOf([true], 'You must accept the terms and conditions'),
	validationImages: yup.array().of(
		yup.object().shape({
			type: yup.string().required(),
			image: yup.mixed().required(),
		})
	)
		.test('len', 'El array debe contener exactamente 3 objetos', (val) => val && val.length === 3)
});

function submitRegisterYup() {
	validationErrors.value = {}; // Reset errors
	console.log("entra en submitRegisterYup")
	schema.validate(registerForm, { abortEarly: false })
		.then(() => {
			console.log("entra en submitRegister")
			submitRegister();
		})
		.catch((err) => {
			err.inner.forEach((error) => {
				console.log("entra en error", error);
				validationErrors.value[error.path] = error.message;
			});
		});
}
</script>

<style scoped>
.text-danger {
	color: red;
	font-size: 0.875rem;
}

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