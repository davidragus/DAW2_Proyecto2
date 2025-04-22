<template>
	<div class="grid">
		<div class="col-12">
			<div class="card">
				<div class="card-header bg-transparent ps-0 pe-0">
					<h5 class="mb-3">Games</h5>
				</div>

				<DataTable :value="games" paginator :rows="25" stripedRows dataKey="id" size="small"
					v-model:filters="filters"
					:globalFilterFields="['id', 'alias', 'name', 'surname1', 'surname2', 'email', 'created_at', 'type.name']"
					responsiveLayout="scroll">
					<template #header>
						<Toolbar pt:root:class="toolbar-table">
							<template #start>
								<div class="flex flex-column sm:flex-row gap-2 w-full sm:w-auto">
									<IconField class="w-full sm:w-20rem">
										<InputIcon class="pi pi-search" />
										<InputText v-model="filters['global'].value" placeholder="Buscar"
											class="w-full" />
									</IconField>

									<Button type="button" icon="pi pi-filter-slash" label="Clear" class="filter-btn"
										outlined @click="initFilters()" />
									<Button type="button" icon="pi pi-refresh" class="filter-btn" outlined
										@click="getAllGames()" />
								</div>
							</template>

							<template #end>
								<Button v-if="can('game-create')" icon="pi pi-plus" label="Create Game"
									@click="$router.push('games/create')" class="w-full sm:w-auto"
									:style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }" />
							</template>
						</Toolbar>
					</template>

					<template #empty>
						No games found.
					</template>

					<Column field="id" header="ID" sortable></Column>
					<Column field="name" header="Name" sortable></Column>
					<Column field="route_path" header="Route" sortable></Column>
					<Column field="active" header="Game Active" sortable></Column>
					<Column field="created_at" header="Created at" sortable></Column>
					<Column field="updated_at" header="Updated at" sortable></Column>

					<Column header="Actions" class="sm:flex-row">
						<template #body="slotProps">
							<div class="flex flex-wrap gap-2">
								<router-link v-if="can('games-show')"
									:to="{ name: 'games.show', params: { id: slotProps.data.id } }">
									<Button icon="pi pi-eye" severity="info" size="small" />
								</router-link>

								<router-link v-if="can('validation-show')"
									:to="{ name: 'games.edit', params: { id: slotProps.data.id } }">
									<Button icon="pi pi-pencil" severity="info" size="small" />
								</router-link>

								<Button icon="pi pi-trash" severity="danger" v-if="can('user-delete')"
									@click.prevent="deleteGame(slotProps.data.id, slotProps.index)" size="small" />
							</div>
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
import { useAbility } from "@casl/vue";
import { FilterMatchMode } from "@primevue/core/api";

const { games, getAllGames, deleteGame } = useGames();
const { can } = useAbility();

const filters = ref({
	global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const initFilters = () => {
	filters.value = {
		global: { value: null, matchMode: FilterMatchMode.CONTAINS },
	};
};

onMounted(() => {
	getAllGames();
});
</script>

<style scoped>
.filter-btn {
	height: 2.5rem;
}
</style>
