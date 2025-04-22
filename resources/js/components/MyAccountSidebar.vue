<template>
	<aside v-if="!isMobile" class="sidebar">
		<ul>
			<li :class="{ active: $route.path === '/my-account' }">
				<RouterLink to="/my-account">ACCOUNT DETAILS</RouterLink>
			</li>
			<li :class="{ active: $route.path === '/balance-history' }">
				<RouterLink to="/balance-history">BALANCE HISTORY</RouterLink>
			</li>
			<li :class="{ active: $route.path === '/game-history' }">
				<RouterLink to="/game-history">GAME HISTORY</RouterLink>
			</li>
			<li :class="{ active: $route.path === '/achievements' }">
				<RouterLink to="/achievements">ACHIEVEMENTS</RouterLink>
			</li>
			<li :class="{ active: $route.path === '/verify-identity' }">
				<RouterLink to="/verify-identity">VERIFY IDENTITY</RouterLink>
			</li>
		</ul>
	</aside>
	<div v-else>
		<div class="collapse" id="menu">
			<ul class="d-flex flex-column align-items-center">
				<li class="w-100" :class="{ active: $route.path === '/my-account' }">
					<RouterLink to="/my-account">ACCOUNT DETAILS</RouterLink>
				</li>
				<li class="w-100" :class="{ active: $route.path === '/balance-history' }">
					<RouterLink to="/balance-history">BALANCE HISTORY</RouterLink>
				</li>
				<li class="w-100" :class="{ active: $route.path === '/game-history' }">
					<RouterLink to="/game-history">GAME HISTORY</RouterLink>
				</li>
				<li class="w-100" :class="{ active: $route.path === '/achievements' }">
					<RouterLink to="/achievements">ACHIEVEMENTS</RouterLink>
				</li>
				<li class="w-100" :class="{ active: $route.path === '/verify-identity' }">
					<RouterLink to="/verify-identity">VERIFY IDENTITY</RouterLink>
				</li>
			</ul>
		</div>
		<div class="collapse-button d-flex justify-content-center" data-bs-toggle="collapse" data-bs-target="#menu"
			@click="toggleDropdown">
			<i class="text-white pi" :class="dropdownVisible ? 'pi-chevron-up' : 'pi-chevron-down'"></i>
		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue';

const dropdownVisible = ref(false);

function toggleDropdown() {
	dropdownVisible.value = !dropdownVisible.value;
}

defineProps({
	isMobile: Boolean
});
</script>

<style scoped>
.active {
	text-decoration: underline;
	color: #ffffff;
	font-weight: bold;
}

.sidebar {
	width: 200px;
	margin-right: 20px;
	border-right: 1px solid #3B3B3B;
	padding: 20px 0 20px 0;
}

.sidebar ul {
	list-style: none;
	padding: 0;
}

.sidebar ul li {
	margin-bottom: 10px;
}

.sidebar ul li a {
	text-decoration: none;
	color: #ffffff;
}

.sidebar ul li a:hover {
	text-decoration: none;
	color: #ffffff;
	font-weight: bold;
	transition: 0.2s;
}

#menu ul {
	list-style: none;
	padding: 0;
	border-left: 1px solid #3B3B3B;
	border-right: 1px solid #3B3B3B;
}

#menu ul li {
	display: flex;
	justify-content: center;
	padding: 10px 0;
	border-bottom: 1px solid #3B3B3B;
}

#menu ul li:last-child {
	border: none;
}

#menu ul li a {
	text-decoration: none;
	color: #ffffff;
}

#menu ul li a:hover {
	text-decoration: none;
	color: #ffffff;
	font-weight: bold;
	transition: 0.2s;
}

.collapse-button {
	padding: 5px 0;
	border: 1px solid #3B3B3B;
	border-bottom-right-radius: 10px;
	border-bottom-left-radius: 10px;
}
</style>