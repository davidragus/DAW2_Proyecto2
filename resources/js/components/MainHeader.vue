<template>
	<nav class="navbar py-0">
		<div class="d-flex align-items-center">
			<SideBarButton @toggle-sidebar="$emit('toggle-sidebar')" />
			<div class="vertical-separator"></div>
			<WebsiteLogo />
		</div>
		<template v-if="!authStore().user?.name">
			<LoginAndRegister />
		</template>
	</nav>
</template>

<script setup>
import SideBarButton from "../components/SideBarButton.vue";
import LoginAndRegister from "../components/LoginAndRegister.vue";
import WebsiteLogo from "../components/WebsiteLogo.vue";
import useAuth from "@/composables/auth";
import { authStore } from "../store/auth";

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
}

.vertical-separator {
	height: 24px;
	border-right: 1px solid #353535;
	margin-right: 30px;
}
</style>