<template>
	<MainHeader :isMobile="isMobile" @toggle-sidebar="toggleSideBar" @toggle-sidebar-mobile="toggleSideBarMobile" />
	<MainSideBar :isMobile="isMobile" :visible="isToggled"/>
</template>

<script setup>
import MainHeader from '../components/MainHeader.vue';
import MainSideBar from '../components/MainSideBar.vue';
import { ref, onMounted } from 'vue';

const isToggled = ref(true);

function toggleSideBar() {
	const sideBar = document.getElementById('sideBar');
	if (isToggled.value) {
		sideBar.style.setProperty('width', '115px');
		document.getElementById('mainContent').style.setProperty('padding-left', '115px');
		document.getElementById('mainFooter').style.setProperty('padding-left', '115px');
		document.getElementById('lang-container').style.setProperty('width', '95%');
	} else {
		sideBar.style.setProperty('width', '230px');
		document.getElementById('mainContent').style.setProperty('padding-left', '230px');
		document.getElementById('mainFooter').style.setProperty('padding-left', '230px');
		document.getElementById('lang-container').style.setProperty('width', '49%');

	}
	isToggled.value = !isToggled.value;
}
function toggleSideBarMobile() {
	const sideBar = document.getElementById('sideBar');
	if (isToggled.value) {
		sideBar.style.setProperty('width', '80%');
	} else {
		sideBar.style.setProperty('width', '0px');
	}
	isToggled.value = !isToggled.value;
}
const isMobile = ref(false);
function checkMobile() {
    isMobile.value = window.innerWidth <= 768;
	console.log(isMobile.value);
	if(isMobile.value){
		console.log("mobile");
		document.getElementById('mainContent').style.setProperty('padding-left', '0px');
		document.getElementById('mainFooter').style.setProperty('padding-left', '0px');
	} else{
		console.log("desktop");
		if(isToggled.value){
			document.getElementById('mainContent').style.setProperty('padding-left', '230px');
			document.getElementById('mainFooter').style.setProperty('padding-left', '230px');
		} else{
			document.getElementById('mainContent').style.setProperty('padding-left', '115px');
			document.getElementById('mainFooter').style.setProperty('padding-left', '115px');
		}
	}
}

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});

</script>

<style scoped></style>