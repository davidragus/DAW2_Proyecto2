<template>
	<button
		class="relative overflow-hidden w-full p-link flex align-items-center p-2 pl-0 text-color hover:surface-200 border-noround">

		<Avatar :image="authStore().user.avatar ? authStore().user.avatar : '/images/EmptyAvatar.webp'" class="mr-3"
			shape="circle" />
		<span class="inline-flex flex-column">
			<span class="font-bold">{{ authStore().user?.name }}</span>
			<span>
				<span v-for="rol in authStore().user?.roles" class="text-sm mr-2">{{ rol.name }}</span>
			</span>
		</span>
	</button>

	<ul class="layout-menu">
		<template v-for="(item, i) in model" :key="item">
			<app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
			<li v-if="item.separator" class="menu-separator"></li>
		</template>
	</ul>
</template>

<script setup>
import { ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';
import { useAbility } from '@casl/vue'
import { authStore } from "../store/auth";

const { can } = useAbility();
const auth = authStore();
//const user = computed(() => auth.user.value)
//console.log(auth.user);
const model = ref([
	{
		label: 'Home',
		permision: 'all',
		items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/admin', permision: 'all' }]
	},
	{
		label: 'Usuarios',
		items: [
			{ label: 'Users', icon: 'pi pi-fw pi-id-card', to: '/admin/users', permision: 'user-list' },
			{ label: 'Validations', icon: 'pi pi-fw pi-verified', to: '/admin/validations', permision: 'validation-list' },
			{ label: 'Roles', icon: 'pi pi-fw pi-check-square', to: '/admin/roles', permision: 'role-list' },
			{ label: 'Permisos', icon: 'pi pi-fw pi-bookmark', to: '/admin/permissions', permision: 'permission-list' }
		]
	},
	{
		label: 'Achievements',
		items: [
			{ label: 'Achievements', icon: 'pi pi-fw pi-id-card', to: '/admin/achievements', permision: 'achievement-list' }

		]
	},
	{
		label: 'Games',
		items: [
			{ label: 'Games', icon: 'pi pi-fw pi-bolt', to: '/admin/games', permision: 'games-list' },
			{ label: 'Game rooms', icon: 'pi pi-fw pi-address-book', to: '/admin/game-rooms', permision: 'gameRooms-list' }

		]
	}
]);
</script>


<style lang="scss" scoped></style>