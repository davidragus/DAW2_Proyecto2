<template>
	<div id="mainContent" class="d-flex ms-4" :class="isMobile ? 'flex-column mr-4' : ''">
		<MyAccountSidebar :isMobile="isMobile" />
		<div class="container">
			<div class="main-content-validation">
				<div v-if="user.validation_status === 'ACCEPTED'" class="status-card accepted">
					<h2>Validation Accepted</h2>
					<p>Your identity has been successfully verified.</p>
				</div>
				<div v-else-if="user.validation_status === 'PENDING'" class="status-card pending">
					<h2>Validation Pending</h2>
					<p>Your identity verification is currently under review. Please wait for further updates.</p>
				</div>
				<div v-else-if="user.validation_status === 'DENIED'" class="status-card denied">
					<h2>Validation Denied</h2>
					<p>Your identity verification was denied. Please submit the required documents again.</p>
					<PrimeButton class="w-100 mt-3" label="Submit New Validation" @click="showModal = true" />
				</div>
			</div>

			<!-- PrimeVue Dialog for uploading images -->
			<Dialog v-model:visible="showModal" header="Submit New Validation" :modal="true" :closable="false"
				:style="{ width: '400px', backgroundColor: '#212121', color: 'white', borderColor: '#3B3B3B' }"
				class="validation-dialog">
				<div class="dialog-container">
					<form @submit.prevent="handleSubmit">
						<div class="form-group">
							<h6>Front image DNI</h6>
							<FileUpload mode="basic" name="image1" accept="image/*" chooseLabel="Upload Image 1"
								@select="onFileSelect($event, 0)" class="w-100" />
							<p v-if="validationErrors[0]" class="text-danger mt-1">{{ validationErrors[0] }}</p>
						</div>

						<div class="form-group">
							<h6>Back image DNI</h6>
							<FileUpload mode="basic" name="image2" accept="image/*" chooseLabel="Upload Image 2"
								@select="onFileSelect($event, 1)" class="w-100 mt-3" />
							<p v-if="validationErrors[1]" class="text-danger mt-1">{{ validationErrors[1] }}</p>
						</div>

						<div class="form-group">
							<h6>Face image</h6>
							<FileUpload mode="basic" name="image3" accept="image/*" chooseLabel="Upload Image 3"
								@select="onFileSelect($event, 2)" class="w-100 mt-3" />
							<p v-if="validationErrors[2]" class="text-danger mt-1">{{ validationErrors[2] }}</p>
						</div>
						<div class="d-flex justify-content-between mt-3">
							<PrimeButton type="submit" label="Submit" class="w-100 p-button-success" />
							<PrimeButton type="button" label="Cancel" class="w-100 p-button-secondary mt-2"
								@click="showModal = false" />
						</div>
					</form>
				</div>
			</Dialog>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import * as yup from "yup";
import { authStore } from "../store/auth";
import useUsers from "@/composables/users";
import PrimeButton from "primevue/button";
import Dialog from "primevue/dialog";
import FileUpload from "primevue/fileupload";
import MyAccountSidebar from './MyAccountSidebar.vue';

const { user, getUser, submitVerifyIdentity, validationErrors } = useUsers();
const showModal = ref(false);
const validationImages = ref([null, null, null]);
const processing = ref(false);

const isMobile = ref(false);

const checkMobile = () => {
	isMobile.value = window.innerWidth <= 768;
	if (!isMobile.value) {
		document.getElementById('mainContent').style.paddingLeft = '230px';
	}
};

onMounted(() => {
	checkMobile();
	getUser(authStore().user.id);
	window.addEventListener('resize', checkMobile);
});

function onFileSelect(event, index) {
	validationImages.value[index] = event.files[0];
}

const schema = yup.object({
	validationImages: yup
		.array()
		.of(
			yup
				.mixed()
				.required("This image is required.")
				.test("fileType", "Only JPG, JPEG, or PNG files are allowed.", (value) => {
					return value && ["image/jpeg", "image/png", "image/jpg"].includes(value?.type);
				})
		)
		.test("len", "You must upload exactly 3 images.", (val) => val && val.filter(Boolean).length === 3),
});

async function handleSubmit() {
	if (processing.value) return;

	processing.value = true;
	validationErrors.value = {};

	try {
		await schema.validate({ validationImages: validationImages.value }, { abortEarly: false });

		await submitVerifyIdentity(authStore().user.id, validationImages.value);
		showModal.value = false;
		validationImages.value = [null, null, null];
		swal({
			icon: "success",
			title: "Validation submitted successfully!",
			showConfirmButton: false,
			timer: 1500,
			background: "#2A2A2A",
			color: "#ffffff",
		});
		getUser(authStore().user.id);
	} catch (err) {
		if (err.inner) {

			err.inner.forEach((error) => {
				const path = error.path;
				const indexMatch = path.match(/\[(\d+)\]/);
				if (indexMatch) {
					const index = indexMatch[1];
					validationErrors.value[index] = error.message;
				} else {
					validationErrors.value[path] = error.message;
				}
			});
		}
	} finally {
		processing.value = false;
	}
}
</script>

<style scoped>
#mainContent {
	padding: 0 0;
}

.main-content-validation {
	min-height: 80vh;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	padding: 20px;
}

.status-card {
	padding: 20px;
	border-radius: 10px;
	text-align: center;
	width: 100%;
	max-width: 400px;
	margin-bottom: 20px;
	font-family: Arial, sans-serif;
	font-size: 16px;
}

.status-card.accepted {
	background-color: #e6f7e6;
	color: #2e7d32;
	border: 1px solid #c8e6c9;
}

.status-card.pending {
	background-color: #fff8e1;
	color: #f9a825;
	border: 1px solid #ffe082;
}

.status-card.denied {
	background-color: #ffebee;
	color: #c62828;
	border: 1px solid #ef9a9a;
}

.dialog-container {
	padding: 20px;
	background-color: #212121;
	border-radius: 10px;
}

.text-danger {
	color: red;
	font-size: 0.875rem;
}
</style>