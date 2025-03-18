<template>
	<div class="grid">
		<div class="col-12">
			<div class="card">

				<div class="card-header bg-transparent ps-0 pe-0">
					<h5 class="float-start mb-0">Users</h5>
				</div>

				<!-- :globalFilterFields="['id', 'alias', 'name', 'surname1', 'surname2', 'email', 'created_at', 'type.name']" -->

				<DataTable v-model:filters="filters" :value="users.data" paginator :rows="25" stripedRows dataKey="id"
					size="small" :class="'custom-datatable'">

					<template #header>
						<Toolbar pt:root:class="toolbar-table">
							<template #start>
								<IconField>
									<InputIcon class="pi pi-search"> </InputIcon>
									<InputText v-model="filters['global'].value" placeholder="Buscar" />
								</IconField>

								<Button type="button" icon="pi pi-filter-slash" label="Clear" class="ml-1" outlined
									@click="initFilters()" />
								<Button type="button" icon="pi pi-refresh" class="h-100 ml-1" outlined
									@click="getUsers()" />
							</template>
							<template #end>
								<Button v-if="can('user-create')" icon="pi pi-plus" label="Create user"
									@click="$router.push('users/create')" class="float-end" />
							</template>
						</Toolbar>

					</template>

					<template #empty> No users found. </template>

					<Column field="id" header="ID" sortable></Column>
					<Column field="dni" header="Identity Card Number" sortable></Column>
					<Column field="validation_status" header="Validation status" sortable>
						<template #body="{ data }">
							<span class="rounded-4 py-1 px-3 text-light fw-bolder" :class="{
								'bg-success': data.validation_status == 'ACCEPTED', 'bg-danger': data.validation_status == 'DENIED',
								'bg-warning': data.validation_status == 'PENDING'
							}">{{
								data.validation_status }}</span>
						</template>
					</Column>
					<Column field="role_name" header="Role" sortable></Column>
					<Column field="username" header="Username" sortable></Column>
					<Column field="name" header="Name" sortable></Column>
					<Column field="surname1" header="First surname" sortable></Column>
					<Column field="surname2" header="Second surname" sortable></Column>
					<Column field="gender" header="Gender" sortable>
						<template #body="{ data }">
							<span v-if="data.gender == 'M'">MALE</span>
							<span v-else-if="data.gender == 'F'">FEMALE</span>
							<span v-else-if="data.gender == 'N'">NEUTRAL</span>
						</template>
					</Column>
					<Column field="birthdate" header="Birth date" sortable></Column>
					<Column field="email" header="Email" sortable></Column>
					<Column field="phone_number" header="Phone" sortable></Column>
					<Column field="created_at" header="Created at" sortable></Column>

					<Column class="pe-0 me-0 icon-column-2">
						<template #body="slotProps">

							<router-link v-if="can('user-edit')"
								:to="{ name: 'users.edit', params: { id: slotProps.data.id } }">
								<Button icon="pi pi-pencil" severity="info" size="small" class="mr-1" />
							</router-link>

							<Button icon="pi pi-trash" severity="danger" v-if="can('user-delete')"
								@click.prevent="deleteUser(slotProps.data.id, slotProps.index)" size="small" />

						</template>
					</Column>

				</DataTable>

			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import useUsers from "../../../composables/users";
import { useAbility } from '@casl/vue'
import { FilterMatchMode, FilterService } from "@primevue/core/api";

const { users, getUsers, deleteUser, resetUserDB } = useUsers()
const { can } = useAbility()

const filters = ref({
	global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const initFilters = () => {
	filters.value = {
		global: { value: null, matchMode: FilterMatchMode.CONTAINS }
	};
};

onMounted(() => {
	getUsers()
})

</script>
<style scoped>
.custom-datatable div > table > tbody > tr > td {
    background-color: #313131;
    color: #fff;
}
</style>