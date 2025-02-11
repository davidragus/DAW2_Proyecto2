<template>
	<nav id="sideBar">
	</nav>
</template>

<script setup>
import { ref } from "vue";

const isToggled = ref(true);

function toggleSideBar() {
	const sideBar = document.getElementById('sideBar');
	if (isToggled.value) {
		sideBar.style.setProperty('width', '115px');
		document.getElementById('mainContent').style.setProperty('padding-left', '115px');
	} else {
		sideBar.style.setProperty('width', '230px');
		document.getElementById('mainContent').style.setProperty('padding-left', '230px');

	}
	isToggled.value = !isToggled.value;
}
</script>

<style scoped>
#sideBar {
	position: fixed;
	left: 0;
	background-color: limegreen;
	width: 230px;
	height: 1080px;
}
</style>