<template>

	<nav class="navbar py-0 bg-dark border-bottom border-body">
		<div class="d-flex align-items-center">
			<SideBarButton />
			<div class="vertical-separator"></div>
			<WebsiteLogo />
		</div>
		<div>
			<LoginAndRegister />
		</div>
	</nav>

	<!-- No eliminar hasta haber creado el header deseado -->
	<!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
		<div class="container">
			<router-link to="/" class="navbar-brand">DAW 2</router-link>
			<a class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</a>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mt-2 mt-lg-0 me-auto mb-2 mb-lg-0">
					<LocaleSwitcher />
				</ul>
				<ul class="navbar-nav mt-2 mt-lg-0 ms-auto">
					<li class="nav-item">
						<router-link to="/" class="nav-link" aria-current="page">{{ $t('home') }}</router-link>
					</li>
					<li class="nav-item">
						<router-link :to="{ name: 'public-posts.index' }" class="nav-link">Blog</router-link>
					</li>
					<template v-if="!authStore().user?.name">
						<li class="nav-item">
							<router-link class="nav-link" to="/login">{{ $t('login') }}</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/register">{{ $t('register') }}</router-link>
						</li>
					</template>
<li v-if="authStore().user?.name" class="nav-item dropdown">
	<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		{{ authStore().user?.name }}
	</a>
	<ul class="dropdown-menu dropdown-menu-end">
		<li><router-link class="dropdown-item" to="/admin">Admin</router-link></li>
		<li><router-link to="/admin/posts" class="dropdown-item">Post</router-link></li>
		<li>
			<hr class="dropdown-divider">
		</li>
		<li><a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a></li>
	</ul>
</li>
</ul>
</div>
</div>
</nav> -->
</template>

<script setup>

import useAuth from "@/composables/auth";
import LocaleSwitcher from "../components/LocaleSwitcher.vue";
import LoginAndRegister from "../components/LoginAndRegister.vue";
import SideBarButton from "../components/SideBarButton.vue";
import Button from "../components/Button.vue";
import WebsiteLogo from "../components/WebsiteLogo.vue";
import { authStore } from "../store/auth";

const { processing, logout } = useAuth();
</script>
<style scoped>
nav {
	position: sticky;
	top: 0;
	height: 70px;
}

.vertical-separator {
	height: 24px;
	border-right: 1px solid #353535;
	margin-right: 30px;
}
</style>