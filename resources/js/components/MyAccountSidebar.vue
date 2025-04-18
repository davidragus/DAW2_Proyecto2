<template>
	<aside class="sidebar">
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
</template>

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
</style>