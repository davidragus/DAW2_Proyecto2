<template>

	<div class="grid">
		<div class="col-12 md:col-4 lg:col-4 xl:col-3">
		</div>
		<div class="col-12">
			<div class="row">
				<div class="col-3">
					<div class="card">
						<legend>Validation details</legend>
						<div class="row">
							<div class="col-3">
								<p>ID: <span>{{ validation.id }}</span></p>
							</div>
							<div class="col-9">
								<p>Created at: <span>{{ validation.created_at }}</span></p>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<p>Status: <span class="rounded-4 py-1 px-3 text-light fw-bolder" :class="{
									'bg-success': validation.status == 'ACCEPTED', 'bg-danger': validation.status == 'DENIED',
									'bg-warning': validation.status == 'PENDING'
								}">{{
									validation.status }}</span></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-9">
					<div class="card">
						<legend>User details</legend>
						<div class="row">
							<div class="col-3">
								<p>Full name: <span v-if="validation.user">{{ validation.user.name }} {{
									validation.user.surname1 }} {{ validation.user.surname2 ?
											validation.user.surname2 : '' }}</span></p>
							</div>
							<div class="col-3">
								<p>Country: <span v-if="validation.user">{{ validation.user.country }}</span></p>
								<!-- TODO: Save the country of the user -->
							</div>
							<div class="col-3">
								<p>ID Card number: <span v-if="validation.user">{{ validation.user.dni }}</span></p>
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<p>Birthdate: <span v-if="validation.user">{{ validation.user.birthdate }}</span></p>
							</div>
							<div class="col-3">
								<p>Gender:
									<template v-if="validation.user">
										<span v-if="validation.user.gender == 'M'">Male</span>
										<span v-else-if="validation.user.gender == 'F'">Female</span>
										<span v-else-if="validation.user.gender == 'N'">Neutral gender</span>
									</template>
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="card">
							<legend>Images</legend>
							<div class="row">
								<div v-for="image in validation.images" class="col-4">
									<img class="col-12" :src="image.original_url" alt="">
									<!--TODO: Añadir alt a la imagen-->
									<p class="col-12 text-center">{{ image.file_name }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div v-if="validation.status == 'PENDING'" class="row justify-content-end mt-3">
					<!-- <button :disabled="isLoading" class="btn btn-primary w-100" @click="submitForm">
							<div v-show="isLoading" class=""></div>
							<i class="pi pi-check"></i>
							<span v-if="isLoading">Processing...</span>
							<span v-else>Save user</span>
						</button> -->
					<Button :disabled="isLoading" class="col-2 me-3" label="Approve validation" icon="pi pi-check"
						@click="checkApprovePermission(validation.id)" />
					<Button :disabled="isLoading" class="col-2" label="Decline validation" severity="danger"
						icon="pi pi-times" @click="checkDeclinePermission(validation.id)" />
				</div>
			</div>
		</div>
	</div>

</template>

<script setup>

import { ref, onMounted, inject } from "vue";
import useValidations from "../../../composables/validations";
import { useAbility } from '@casl/vue'
import { useRoute } from "vue-router";

const { validation, getValidation, approveValidation, declineValidation, isLoading } = useValidations();
const { can } = useAbility()

const route = useRoute()
const swal = inject('$swal');

onMounted(() => {
	getValidation(route.params.id);
});

const checkApprovePermission = (id) => {
	if (can('validation-approve')) {
		isLoading.value = true;
		approveValidation(id);
	} else {
		swal({
			icon: 'error',
			title: 'You don\'t have permission to perform this action'
		});
	}
}

const checkDeclinePermission = (id) => {
	if (can('validation-decline')) {
		isLoading.value = true;
		declineValidation(id);
	} else {
		swal({
			icon: 'error',
			title: 'You don\'t have permission to perform this action'
		});
	}
}

</script>

<style></style>