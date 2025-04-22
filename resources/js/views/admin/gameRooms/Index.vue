<template>
    <div class="grid px-2 sm:px-0">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent ps-0 pe-0">
                    <h5 class="mb-3">Game rooms</h5>
                </div>
                <DataTable v-model:filters="filters" :value="gameRooms" paginator :rows="25" stripedRows dataKey="id"
                    size="small" responsiveLayout="scroll" :class="'custom-datatable'">
                    <template #header>
                        <Toolbar pt:root:class="toolbar-table">
                            <template #start>
                                <div class="flex flex-column sm:flex-row gap-2 w-full sm:w-auto">
                                    <IconField class="w-full sm:w-20rem">
                                        <InputIcon class="pi pi-search" />
                                        <InputText v-model="filters['global'].value" placeholder="Search"
                                            class="w-full" />
                                    </IconField>

                                    <Button type="button" icon="pi pi-filter-slash" label="Clear" class="filter-btn"
                                        outlined @click="initFilters()" />
                                    <Button type="button" icon="pi pi-refresh" class="filter-btn" outlined
                                        @click="getAllGameRooms()" />
                                </div>
                            </template>

                            <template #end>
                                <Button v-if="can('gameRooms-create')" icon="pi pi-plus" label="Create Game Room"
                                    @click="$router.push('game-rooms/create')" class="w-full sm:w-auto"
                                    :style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }" />
                            </template>
                        </Toolbar>
                    </template>

                    <template #empty>No game rooms found.</template>

                    <Column field="id" header="ID" sortable></Column>
                    <Column field="game_name" header="Game" sortable></Column>
                    <Column field="name" header="Name room" sortable></Column>
                    <Column field="max_players" header="Max players" sortable></Column>
                    <Column field="status" header="Status" sortable></Column>

                    <Column header="Actions" class="sm:flex-row">
                        <template #body="slotProps">
                            <div class="flex flex-wrap gap-2">
                                <router-link v-if="can('gameRooms-edit')"
                                    :to="{ name: 'game-rooms.edit', params: { id: slotProps.data.id } }">
                                    <Button icon="pi pi-pencil" severity="info" size="small" />
                                </router-link>
                                <Button icon="pi pi-trash" severity="danger" v-if="can('gameRooms-delete')"
                                    @click.prevent="deleteGameRoom(slotProps.data.id, slotProps.index)" size="small" />
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
import useGameRooms from "@/composables/gameRooms";
import { useAbility } from "@casl/vue";
import { FilterMatchMode } from "@primevue/core/api";

const { gameRooms, getAllGameRooms, deleteGameRoom } = useGameRooms();
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
    getAllGameRooms();
});
</script>

<style scoped>
.custom-datatable div>table>tbody>tr>td {
    background-color: #313131;
    color: #fff;
}

.filter-btn {
    height: 2.5rem;
}
</style>
