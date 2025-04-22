<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-transparent ps-0 pe-0">
          <h5 class="mb-3">Permissions</h5>
        </div>

        <DataTable :value="permissions.data" paginator :rows="25" stripedRows dataKey="id" size="small"
          v-model:filters="filters" :globalFilterFields="['id', 'name', 'created_at']" :sortField="orderColumn"
          :sortOrder="orderDirection === 'asc' ? 1 : -1">
          <template #header>
            <Toolbar pt:root:class="toolbar-table">
              <template #start>
                <div class="flex flex-column sm:flex-row gap-2 w-full sm:w-auto">
                  <IconField class="w-full sm:w-20rem">
                    <InputIcon class="pi pi-search" />
                    <InputText v-model="filters['global'].value" placeholder="Buscar" class="w-full" />
                  </IconField>

                  <Button type="button" icon="pi pi-filter-slash" label="Clear" class="filter-btn" outlined
                    @click="initFilters" />
                  <Button type="button" icon="pi pi-refresh" class="filter-btn" outlined @click="getRoles" />
                </div>
              </template>


              <template #end>
                <Button v-if="can('permission-create')" icon="pi pi-plus" label="Create Permission"
                  @click="$router.push({ name: 'permissions.create' })" class="w-full sm:w-auto"
                  :style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }" />
              </template>
            </Toolbar>
          </template>

          <template #empty>No permissions found.</template>

          <Column field="id" header="ID" sortable />
          <Column field="name" header="Title" sortable />
          <Column field="created_at" header="Created At" sortable />

          <Column header="Actions">
            <template #body="slotProps">
              <div class="flex flex-wrap gap-2">
                <router-link v-if="can('permission-edit')"
                  :to="{ name: 'permissions.edit', params: { id: slotProps.data.id } }">
                  <Button icon="pi pi-pencil" severity="info" size="small" />
                </router-link>
                <Button v-if="can('permission-delete')" icon="pi pi-trash" severity="danger"
                  @click.prevent="deletePermission(slotProps.data.id)" size="small" />
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
import usePermissions from "@/composables/permissions";
import { useAbility } from '@casl/vue';
import { FilterMatchMode } from "@primevue/core/api";

const { permissions, getPermissions, deletePermission } = usePermissions();
const { can } = useAbility();

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const initFilters = () => {
  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  };
};

const orderColumn = ref("id");
const orderDirection = ref("asc");

onMounted(() => {
  getPermissions();
});
</script>

<style scoped>
.filter-btn {
  height: 2.5rem;
}
</style>
