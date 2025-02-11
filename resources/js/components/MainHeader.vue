<template>
	<nav class="navbar py-0">
		<div class="d-flex align-items-center">
			<SideBarButton @toggle-sidebar="$emit('toggle-sidebar')" />
			<div class="vertical-separator"></div>
			<WebsiteLogo />
		</div>
		<div>
			<Button class="mx-3" buttonColor="red" buttonStyle="filled" buttonSize="normal">LOG IN</Button>
			<Button class="mx-3" buttonColor="yellow" buttonStyle="outlined" buttonSize="normal">REGISTER</Button>
		</div>
	</nav>
</template>

<script setup>
import SideBarButton from "../components/SideBarButton.vue";
import Button from "../components/Button.vue";
import WebsiteLogo from "../components/WebsiteLogo.vue";

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