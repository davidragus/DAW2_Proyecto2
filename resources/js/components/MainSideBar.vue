<template>
	<nav id="sideBar" class="d-flex flex-column justify-content-between align-items-center">
		<ul class="mt-5 w-75">
			<li><a href="">HOME</a></li>
			<hr>
			<li><a href="">ROULETTE</a></li>
			<li><a href="">BINGO</a></li>
			<li><a href="">BLACKJACK</a></li>
		</ul>
		<div>

		</div>
	</nav>
</template>

<script setup>
</script>

<style scoped>
#sideBar {
	position: fixed;
	left: 0;
	background-color: #212121;
	width: 230px;
	height: calc(100% - var(--main-header-height));
}

ul {
	list-style-type: none;
	padding: 0;
	margin: 0;
}

a {
	color: white;
	font-size: 18px;
}

a:hover {
	color: red;
}

img {
	color: white;
}
</style>