<template>
	<div class="grid">
		<div class="col-12">
			<div class="card">

				<div class="card-header bg-transparent ps-0 pe-0">
					<h5 class="float-start mb-0">Validations</h5>
				</div>

				<!-- v-model:filters="filters" Esto va en el datatable -->
				<!-- :globalFilterFields="['id', 'alias', 'name', 'surname1', 'surname2', 'email', 'created_at', 'type.name']" -->

				<DataTable :value="validations.data" paginator :rows="25" stripedRows dataKey="id" size="small">

					<template #header>
						<Toolbar pt:root:class="toolbar-table">
							<template #start>
								<!-- <IconField>
									<InputIcon class="pi pi-search"> </InputIcon>
									<InputText v-model="filters['global'].value" placeholder="Buscar" />
								</IconField> -->

								<!-- <Button type="button" icon="pi pi-filter-slash" label="Clear" class="ml-1" outlined
									@click="initFilters()" /> -->
								<Button type="button" icon="pi pi-refresh" class="h-100 ml-1 filter-btn" outlined
									@click="getValidations()" />
							</template>
						</Toolbar>

					</template>

					<template #empty> No validations found. </template>

					<Column field="id" header="ID" sortable></Column>
					<!-- <Column field="dni" header="Identity Card Number" sortable></Column> -->
					<Column field="user.email" header="User" sortable></Column>
					<Column field="status" header="Validation status" sortable>
						<template #body="{ data }">
							<span class="rounded-4 py-1 px-3 text-light fw-bolder" :class="{
								'bg-success': data.status == 'ACCEPTED', 'bg-danger': data.status == 'DENIED',
								'bg-warning': data.status == 'PENDING'
							}">{{
								data.status }}</span>
						</template>
					</Column>
					<Column field="created_at" header="Created at" sortable></Column>

					<Column class="pe-0 me-0 icon-column-2">
						<template #body="slotProps">

							<router-link v-if="can('validation-show')"
								:to="{ name: 'validations.show', params: { id: slotProps.data.id } }">
								<Button icon="pi pi-eye" severity="info" size="small" class="mr-1" />
							</router-link>
							<!-- <router-link v-if="can('validation-show')"
								:to="{ name: 'users.edit', params: { id: slotProps.data.id } }">
								<Button icon="pi pi-pencil" severity="info" size="small" class="mr-1" />
							</router-link> -->
							<router-link v-if="can('validation-delete')">
							<Button icon="pi pi-trash" severity="danger" v-if="can('user-delete')"
								@click.prevent="deleteValidation(slotProps.data.id, slotProps.index)" size="small" />
							</router-link>

						</template>
					</Column>

				</DataTable>

			</div>
		</div>
	</div>
</template>

<script setup>

import { ref, onMounted } from "vue";
import useValidations from "../../../composables/validations";
import { useAbility } from '@casl/vue'
import { FilterMatchMode, FilterService } from "@primevue/core/api";


const { validations, getValidations, deleteValidation } = useValidations();
const { can } = useAbility()

// const filters = ref({
// 	global: { value: null, matchMode: FilterMatchMode.CONTAINS },
// });

// const initFilters = () => {
// 	filters.value = {
// 		global: { value: null, matchMode: FilterMatchMode.CONTAINS }
// 	};
// };

onMounted(() => {
	getValidations();
})

</script>