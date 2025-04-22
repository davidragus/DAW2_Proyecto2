<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent ps-0 pe-0">
                    <h5 class="mb-3">Achievements</h5>
                </div>
                <DataTable v-model:filters="filters" :value="achievements.data" paginator :rows="25" stripedRows
                    dataKey="id" size="small" class="custom-datatable">
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
                                        @click="getAllAchievements()" />
                                </div>
                            </template>

                            <template #end>
                                <Button v-if="can('achievement-create')" icon="pi pi-plus" label="Create Achievement"
                                    @click="$router.push('achievements/create')" class="w-full sm:w-auto"
                                    :style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }" />
                            </template>
                        </Toolbar>
                    </template>

                    <template #empty>No achievements found.</template>

                    <Column field="id" header="ID" sortable></Column>
                    <Column field="name" header="Name" sortable></Column>
                    <Column field="description" header="Description" sortable></Column>
                    <Column field="achievement_type" header="Type" sortable></Column>
                    <Column field="amount" header="Amount" sortable></Column>
                    <Column field="reward_amount" header="Reward" sortable></Column>
                    <Column field="created_at" header="Created At" sortable></Column>

                    <Column header="Actions" class="sm:flex-row">
                        <template #body="slotProps">
                            <div class="flex flex-wrap gap-2">
                                <router-link v-if="can('achievement-edit')"
                                    :to="{ name: 'achievements.edit', params: { id: slotProps.data.id } }">
                                    <Button icon="pi pi-pencil" severity="info" size="small" />
                                </router-link>
                                <Button icon="pi pi-trash" severity="danger" v-if="can('achievement-delete')"
                                    @click.prevent="deleteAchievement(slotProps.data.id, slotProps.index)"
                                    size="small" />
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
import useAchievements from "../../../composables/achievements";
import { useAbility } from "@casl/vue";
import { FilterMatchMode } from "@primevue/core/api";

const { achievements, getAllAchievements, deleteAchievement } = useAchievements();
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
    getAllAchievements();
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
