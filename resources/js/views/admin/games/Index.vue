<template>
	<div class="grid">
		<div class="col-12">
			<div class="card">
				<div class="card-header bg-transparent ps-0 pe-0">
					<h5 class="float-start mb-0">Games</h5>
				</div>
				<!-- v-model:filters="filters" Esto va en el datatable -->
				<!-- :globalFilterFields="['id', 'alias', 'name', 'surname1', 'surname2', 'email', 'created_at', 'type.name']" -->
				<DataTable :value="games" paginator :rows="25" stripedRows dataKey="id" size="small" v-model:filters="filters"
				:globalFilterFields="['id', 'alias', 'name', 'surname1', 'surname2', 'email', 'created_at', 'type.name']">

					<template #header>
						<Toolbar pt:root:class="toolbar-table">
							<template #start>
								<IconField>
									<InputIcon class="pi pi-search"> </InputIcon>
									<InputText v-model="filters['global'].value" placeholder="Buscar" />
								</IconField>

								<Button type="button" icon="pi pi-filter-slash" label="Clear" class="ml-1 filter-btn" outlined
									@click="initFilters()" />
								<Button type="button" icon="pi pi-refresh" class="h-100 ml-1 filter-btn" outlined
									@click="getAllGames()" />
							</template>
							<template #end>
                                <Button v-if="can('game-create')" icon="pi pi-plus" label="Create Game"
                                    @click="$router.push('games/create')" class="float-end" :style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }" />
                            </template>
						</Toolbar>

					</template>

					<template #empty> No games found. </template>

					<Column field="id" header="ID" sortable></Column>
					<!-- <Column field="dni" header="Identity Card Number" sortable></Column> -->
					<Column field="name" header="Name" sortable></Column>
					<Column field="route_path" header="Route" sortable></Column>
					<Column field="created_at" header="Created at" sortable></Column>
					<Column field="updated_at" header="Updated at" sortable></Column>

					<Column class="pe-0 me-0 icon-column-2">
						<template #body="slotProps">

							<router-link v-if="can('games-show')"
								:to="{ name: 'games.show', params: { id: slotProps.data.id } }">
								<Button icon="pi pi-eye" severity="info" size="small" class="mr-1" />
							</router-link>
							<router-link v-if="can('validation-show')"
								:to="{ name: 'games.edit', params: { id: slotProps.data.id } }">
								<Button icon="pi pi-pencil" severity="info" size="small" class="mr-1" />
							</router-link>

							<Button icon="pi pi-trash" severity="danger" v-if="can('user-delete')"
								@click.prevent="deleteGame(slotProps.data.id, slotProps.index)" size="small" />

						</template>
					</Column>

				</DataTable>

			</div>
		</div>
	</div>
</template>

<script setup>

import { ref, onMounted } from "vue";
import useGames from "../../../composables/games";
import { useAbility } from '@casl/vue'
import { FilterMatchMode, FilterService } from "@primevue/core/api";


const { games, getAllGames, deleteGame } = useGames();
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
	getAllGames();
})

</script>