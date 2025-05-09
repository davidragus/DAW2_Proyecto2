<template>
	<nav class="navbar py-0">
		<div class="d-flex align-items-center" :class="{ 'w-100': isMobile }">
			<SideBarButton v-if="!isMobile" @toggle-sidebar="$emit('toggle-sidebar')" />
			<SideBarButtonMobile v-else @toggle-sidebar-mobile="$emit('toggle-sidebar-mobile')" />
			<div class="vertical-separator"></div>
			<router-link to="/" class="logo-link">
				<WebsiteLogo />
			</router-link>
			<div v-if="isMobile" class="ms-auto">
				<div v-if="authStore().user?.name">
					<Avatar v-if="authStore().user.avatar" :image="authStore().user.avatar" size="large" shape="circle"
						@click="openDrawer" />
					<Avatar v-if="!authStore().user.avatar" :label="authStore().user.name.substring(0, 1)" size="large"
						shape="circle" @click="openDrawer" />
				</div>
				<div v-else>
					<LoginAndRegister :isMobile="isMobile" />
				</div>
				<Drawer @hide="closeDrawer" v-if="showDrawer" v-model:visible="userSettings" position="right" header=" "
					class="w-75" :pt="{
						root: (options) => ({
							style: {
								'--p-drawer-background': '#212121',
								'--p-drawer-color': '#fff',
								'--p-drawer-border-color': '#212121',
							}
						})
					}" appendTo="self">
					<div class="d-flex flex-column align-items-center">
						<div class="d-flex align-items-center mb-4">
							<img src="/images/chips2.png" alt="icon of chips" class="icon-24 me-2">
							<span class="text-white me-3 chips-number">{{ authStore().user.chips }}</span>
						</div>
						<CashierDialog :show="showCashierDialog" @update:show="showCashierDialog = $event"
							:isMobile="isMobile">
						</CashierDialog>
						<WithdrawDialog :show="showWithdrawDialog" @update:visible="showWithdrawDialog = $event"
							:isMobile="isMobile">
						</WithdrawDialog>
						<ul class="w-100 mt-3">
							<li class="settings-list"><router-link @click="showDrawer = false"
									v-if="authStore().user.roles.some(user => user.name === 'admin')" to="/admin"
									class="dropdown-item">Admin panel</router-link></li>
							<li class="settings-list"><router-link @click="showDrawer = false" to="/my-account"
									class="dropdown-item">My
									account</router-link></li>
							<li class="settings-list"><a href="#" class="dropdown-item" @click="openCashierDialog">Add
									chips</a></li>
							<li class="settings-list"><a href="#" class="dropdown-item"
									@click="openWithdrawDialog">Withdraw</a></li>
							<li class="settings-list"><a href="#" class="dropdown-item" @click="logout">Logout</a></li>
						</ul>
					</div>
				</Drawer>
			</div>
		</div>
		<div v-if="!isMobile" class="d-flex align-items-center ms-auto">
			<div v-if="!authStore().user?.name">
				<LoginAndRegister />
			</div>
			<div v-else class="d-flex align-items-center position-relative">
				<div class="d-flex align-items-center me-4">
					<div class="d-flex align-items-center">
						<img src="/images/chips2.png" alt="icon of chips" class="icon-24 me-2">
						<span class="text-white me-3 chips-number">{{ authStore().user.chips }}</span>
					</div>
					<CashierDialog :show="showCashierDialog" @update:show="showCashierDialog = $event"></CashierDialog>
					<WithdrawDialog :show="showWithdrawDialog" @update:visible="showWithdrawDialog = $event">
					</WithdrawDialog>
				</div>
				<div ref="loginContainer" class="login-container" @click="toggleDropdown">
					<div class="avatar">
						<Avatar v-if="authStore().user.avatar" :image="authStore().user.avatar" size="xlarge"
							shape="circle" />
						<Avatar v-if="!authStore().user.avatar" :label="authStore().user.name.substring(0, 1)"
							size="large" shape="circle" />
					</div>
				</div>
				<div v-show="dropdownVisible" class="dropdown-menu">
					<router-link v-if="authStore().user.roles.some(user => user.name === 'admin')" to="/admin"
						class="dropdown-item">Admin panel</router-link>
					<div v-if="authStore().user.roles.some(user => user.name === 'admin')" class="dropdown-divider">
					</div>
					<router-link to="/my-account" class="dropdown-item">My account</router-link>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item" @click="openCashierDialog">Add chips</a>
					<a href="#" class="dropdown-item" @click="openWithdrawDialog">Withdraw</a>
					<a href="#" class="dropdown-item" @click="logout">Logout</a>
				</div>
			</div>
		</div>
	</nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import SideBarButton from "../components/SideBarButton.vue";
import SideBarButtonMobile from "../components/SideBarButtonMobile.vue";
import LoginAndRegister from "../components/LoginAndRegister.vue";
import WebsiteLogo from "../components/WebsiteLogo.vue";
import useAuth from "@/composables/auth";
import { authStore } from "../store/auth";
import MyAccount from './MyAccount.vue';
import CashierDialog from '../components/CashierDialog.vue';
import WithdrawDialog from '../components/WithdrawDialog.vue';
import Drawer from 'primevue/drawer';
import Button from './Button.vue';

defineProps({ isMobile: Boolean });

const { user } = authStore();

const showDrawer = ref(false);
const userSettings = ref(false);

const dropdownVisible = ref(false);
const loginContainer = ref(null);
const showCashierDialog = ref(false);
const showWithdrawDialog = ref(false);

const chipsNumber = ref(
	authStore().user.chips
);

const openDrawer = () => {
	userSettings.value = true;
	showDrawer.value = true;
}

// Se debe de usar un temporizador para cambiar el valor de showDrawer. De lo contrario, dará un error al entrar al panel de admin.
const closeDrawer = () => {
	userSettings.value = false;
	setTimeout(() => {
		showDrawer.value = false;
	}, 300);
}

function toggleDropdown() {
	dropdownVisible.value = !dropdownVisible.value;
}

function handleClickOutside(event) {
	if (loginContainer.value && !loginContainer.value.contains(event.target)) {
		dropdownVisible.value = false;
	}
}

onMounted(() => {
	// console.log(authStore().user);
	document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
	document.removeEventListener('click', handleClickOutside);
});

function logout() {
	// Lógica para cerrar sesión
	authStore().logout();
	dropdownVisible.value = false;
	// Recargar la página para que se actualice el estado de la sesión
	document.defaultView.location.reload();
	// Por ejemplo, redirigir a la página de inicio
	// router.push({ name: 'home' });
}
function openCashierDialog() {
	dropdownVisible.value = false;
	showCashierDialog.value = true;
}
function openWithdrawDialog() {
	dropdownVisible.value = false;
	showWithdrawDialog.value = true;
}
</script>

<style scoped>
nav {
	background-color: #1F1F1F;
	position: sticky;
	top: 0;
	height: 70px;
	border-bottom: 2px solid red;
	box-shadow: 0 10px 25px 10px #ff000040;
	z-index: 999;
	padding: 0 15px;
}

.vertical-separator {
	height: 24px;
	border-right: 1px solid #353535;
	margin-right: 30px;
}


.login-container {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 50px;
	height: 50px;
	cursor: pointer;
}

.gray-background {
	background-color: #898989;
}

.icon-24 {
	display: flex;
	justify-content: center;
	align-items: center;
	font-size: 24px;
	width: 24px;
	height: 24px;
}

.dropdown-menu {
	position: absolute;
	top: 60px;
	right: 0;
	background-color: #1F1F1F;
	border: 1px solid #353535;
	border-radius: 5px;
	z-index: 1000;
	width: 200px;
	display: flex;
	flex-direction: column;
}

.dropdown-item {
	padding: 10px 15px;
	color: white;
	text-decoration: none;
	display: block;
}

.dropdown-item:hover {
	background-color: #353535;
}

.dropdown-divider {
	height: 1px;
	background-color: #353535;
	margin: 5px 0;
}

.chips-number {
	font-size: 15px;
}

img {
	display: block;
	width: 100%;
	height: auto;
	border-radius: 50%;
	/* Hace que la imagen sea redonda */
	object-fit: cover;
	/* Asegura que la imagen se ajuste correctamente */
}

.avatar {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 50px;
	height: 50px;
	border-radius: 50%;
	/* Hace que el contenedor del avatar sea redondo */
	overflow: hidden;
}

ul {
	border-top: 1px solid #353535;
	list-style: none;
	padding: 0;
	margin: 0;
}

.settings-list {
	border-bottom: 1px solid #353535;
}

@media (max-width: 768px) {
	nav {
		height: auto;
		padding: 10px 15px;
	}

	.vertical-separator {
		display: none;
	}

	.login-container {
		width: 40px;
		height: 40px;
	}

	.icon-24 {
		font-size: 20px;
	}
}
</style>