<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent ps-0 pe-0">
                    <h5 class="float-start mb-0">Achievements</h5>
                </div>
                <DataTable
                    v-model:filters="filters"
                    :value="achievements.data"
                    paginator
                    :rows="25"
                    stripedRows
                    dataKey="id"
                    size="small"
                    :class="'custom-datatable'"
                >
                    <template #header>
                        <Toolbar pt:root:class="toolbar-table">
                            <template #start>
                                <IconField>
                                    <InputIcon class="pi pi-search"></InputIcon>
                                    <InputText v-model="filters['global'].value" placeholder="Search" />
                                </IconField>
                                <Button
                                    type="button"
                                    icon="pi pi-filter-slash"
                                    label="Clear"
                                    class="ml-1 filter-btn"
                                    outlined
                                    @click="initFilters()"
                                />
                                <Button
                                    type="button"
                                    icon="pi pi-refresh"
                                    class="h-100 ml-1 filter-btn"
                                    outlined
                                    @click="getAchievements()"
                                />
                            </template>
                            <template>
                                <Button
                                    v-if="can('achievement-create')"
                                    icon="pi pi-plus"
                                    label="Create Achievement"
                                    @click="$router.push('achievements/create')"
                                    class="float-end"
                                />
                            </template>
                            <template #end>
								<Button v-if="can('achievement-create')" icon="pi pi-plus" label="Create achievement"
									@click="$router.push('achievements/create')" class="float-end" :style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }"/>
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

                    <Column class="pe-0 me-0 icon-column-2">
                        <template #body="slotProps">
                            <router-link
                                v-if="can('achievement-edit')"
                                :to="{ name: 'achievements.edit', params: { id: slotProps.data.id } }"
                            >
                                <Button icon="pi pi-pencil" severity="info" size="small" class="mr-1" />
                            </router-link>
                            <Button
                                icon="pi pi-trash"
                                severity="danger"
                                v-if="can('achievement-delete')"
                                @click.prevent="deleteAchievement(slotProps.data.id, slotProps.index)"
                                size="small"
                            />
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
import { FilterMatchMode, FilterService } from "@primevue/core/api";

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
.custom-datatable div > table > tbody > tr > td {
    background-color: #313131;
    color: #fff;
}
</style>