<template>
	<MainHeader @toggle-sidebar="toggleSideBar" />
	<MainSideBar />
</template>

<script setup>
import MainHeader from '../components/MainHeader.vue';
import MainSideBar from '../components/MainSideBar.vue';

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

<style scoped></style>