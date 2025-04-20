<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent ps-0 pe-0">
                    <h5 class="float-start mb-0">Roles</h5>
                </div>

                <DataTable
                    :value="roles.data"
                    paginator
                    :rows="25"
                    stripedRows
                    dataKey="id"
                    size="small"
                    v-model:filters="filters"
                    :globalFilterFields="['id', 'name', 'created_at']"
                    sortField="created_at"
                    :sortOrder="-1"
                >
                    <template #header>
                        <Toolbar pt:root:class="toolbar-table">
                            <template #start>
                                <IconField>
                                    <InputIcon class="pi pi-search" />
                                    <InputText v-model="filters['global'].value" placeholder="Buscar" />
                                </IconField>
                                <Button
                                    type="button"
                                    icon="pi pi-filter-slash"
                                    label="Clear"
                                    class="ml-1 filter-btn"
                                    outlined
                                    @click="initFilters"
                                />
                                <Button
                                    type="button"
                                    icon="pi pi-refresh"
                                    class="h-100 ml-1 filter-btn"
                                    outlined
                                    @click="getRoles"
                                />
                            </template>
                            <template #end>
                                <Button
                                    v-if="can('role-list')"
                                    icon="pi pi-plus"
                                    label="Create Role"
                                    @click="$router.push({ name: 'roles.create' })"
                                    class="float-end"
                                    :style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }"
                                />
                            </template>
                        </Toolbar>
                    </template>

                    <template #empty>No roles found.</template>

                    <Column field="id" header="ID" sortable />
                    <Column field="name" header="Title" sortable />
                    <Column field="created_at" header="Created at" sortable />

                    <Column class="pe-0 me-0 icon-column-2">
                        <template #body="slotProps">
                            <router-link
                                v-if="can('role-edit')"
                                :to="{ name: 'roles.edit', params: { id: slotProps.data.id } }"
                            >
                                <Button icon="pi pi-pencil" severity="info" size="small" class="mr-1" />
                            </router-link>
                            <Button
                                v-if="can('role-delete')"
                                icon="pi pi-trash"
                                severity="danger"
                                @click.prevent="deleteRole(slotProps.data.id)"
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
import useRoles from "../../../composables/roles";
import { useAbility } from "@casl/vue";
import { FilterMatchMode } from "@primevue/core/api";

const { roles, getRoles, deleteRole } = useRoles();
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
    getRoles();
});
</script>
