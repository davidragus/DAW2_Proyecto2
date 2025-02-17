<template>
	<nav id="sideBar" class="d-flex flex-column justify-content-between align-items-center">
		<ul class="mt-5 w-75">
			<li><a href="" class="d-block container-fluid">icono<span :class="{ 'd-none': !visible }">HOME</span></a></li>
			<hr>
			<li><a href="" class="d-flex container-fluid align-items-center"><img src="/images/iRoulette.svg" alt="" class="icon color-dark"><span :class="{ 'd-none': !visible }">ROULETTE</span></a></li>
			<li><a href="" class="d-block container-fluid">icono<span :class="{ 'd-none': !visible }">BINGO</span></a></li>
			<li><a href="" class="d-flex container-fluid align-items-center"><iBlackJack class="icon"></iBlackJack><span :class="{ 'd-none': !visible }">BLACKJACK</span></a></li>
		</ul>
		<div>
			<button class="bottom-buton">
				<img src="/images/england.png" alt="england" class="icon">
				<p>ENGLISH</p>
			</button>
			<button class="bottom-buton">
				<img src="/images/chat-bot_dark.png" alt="england" class="icon">
				<p>LIVE CHAT</p>
			</button>
		</div>
	</nav>
</template>

<script setup>
import iRoulette from './iRoulette.vue';
import iBlackJack from './iBlackJack.vue';
defineProps({
  visible: Boolean
});
</script>

<style scoped>
#sideBar {
	position: fixed;
	left: 0;
	background-color: #212121;
	width: 230px;
	height: calc(100% - var(--main-header-height));
	box-shadow: 5px 0 20px 10px rgba(0, 0, 0, 0.5);
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
.icon {
	width: 24px;
	height: 24px;
}
.bottom-buton{
	background-color: #212121;
}
</style>