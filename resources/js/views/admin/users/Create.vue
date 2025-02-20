<template>
	<UserForm />
	<!-- <div class="row justify-content-center my-5">
		<div class="col-md-10">
			<div class="card border-0 shadow-sm">
				<div class="card-body">
					<form @submit.prevent="submitForm">
						<div class="mb-3">
							<label for="user-title" class="form-label">Name</label>
							<input v-model="user.name" id="user-title" type="text" class="form-control">
							<div class="text-danger mt-1">
								{{ errors.name }}
							</div>
							<div class="text-danger mt-1">
								<div v-for="message in validationErrors?.name">
									{{ message }}
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="email" class="form-label">Email</label>
							<input v-model="user.email" id="email" type="email" class="form-control">
							<div class="text-danger mt-1">
								{{ errors.email }}
							</div>
							<div class="text-danger mt-1">
								<div v-for="message in validationErrors?.email">
									{{ message }}
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="password" class="form-label">Password</label>
							<input v-model="user.password" id="password" type="password" class="form-control">
							<div class="text-danger mt-1">
								{{ errors.password }}
							</div>
							<div class="text-danger mt-1">
								<div v-for="message in validationErrors?.password">
									{{ message }}
								</div>
							</div>
						</div>
						Role
						<div class="mb-3">
							<label for="user-category" class="form-label">
								Role
							</label>
							<v-select multiple v-model="user.role_id" :options="roleList" :reduce="role => role.id" label="name" class="form-control" />
							<div class="text-danger mt-1">
								{{ errors.role_id }}
							</div>
							<div class="text-danger mt-1">
								<div v-for="message in validationErrors?.role_id">
									{{ message }}
								</div>
							</div>
						</div>
						Buttons
						<div class="mt-4">
							<button :disabled="isLoading" class="btn btn-primary">
								<div v-show="isLoading" class=""></div>
								<span v-if="isLoading">Processing...</span>
								<span v-else>Save</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div> -->
</template>
<script setup>
import UserForm from "../../../layouts/UserForm.vue";
</script>