<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent ps-0 pe-0">
                    <h5 class="float-start mb-0">Achievements</h5>
                </div>
                <DataTable v-model:filters="filters" :value="gameRooms" paginator :rows="25" stripedRows
                    dataKey="id" size="small" :class="'custom-datatable'">
                    <template #header>
                        <Toolbar pt:root:class="toolbar-table">
                            <template #start>
                                <IconField>
                                    <InputIcon class="pi pi-search"></InputIcon>
                                    <InputText v-model="filters['global'].value" placeholder="Search" />
                                </IconField>
                                <Button type="button" icon="pi pi-filter-slash" label="Clear" class="ml-1" outlined
                                    @click="initFilters()" />
                                <Button type="button" icon="pi pi-refresh" class="h-100 ml-1" outlined
                                    @click="getAllGameRooms()" />
                            </template>
                            <template #end>
                                <Button v-if="can('gameRooms-create')" icon="pi pi-plus" label="Create Game Room"
                                    @click="$router.push('game-rooms/create')" class="float-end" />
                            </template>
                        </Toolbar>
                    </template>

                    <template #empty>No achievements found.</template>

                    <Column field="id" header="ID" sortable></Column>
                    <Column field="game_name" header="Game" sortable></Column>
                    <Column field="name" header="Name room" sortable></Column>
                    <Column field="max_players" header="Max players" sortable></Column>
                    <Column field="status" header="Status" sortable></Column>

                    <Column class="pe-0 me-0 icon-column-2">
                        <template #body="slotProps">
                            <router-link v-if="can('gameRooms-edit')"
                                :to="{ name: 'game-rooms.edit', params: { id: slotProps.data.id } }">
                                <Button icon="pi pi-pencil" severity="info" size="small" class="mr-1" />
                            </router-link>
                            <Button icon="pi pi-trash" severity="danger" v-if="can('gameRooms-delete')"
                                @click.prevent="deleteGameRoom(slotProps.data.id, slotProps.index)" size="small" />
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
import { FilterMatchMode, FilterService } from "@primevue/core/api";

const { gameRooms, gameRoom, getAllGameRooms, getGameRoom, deleteGameRoom } = useGameRooms();
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
</style>