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
    if (sideBar) {
        if (isToggled.value) {
            sideBar.style.setProperty('width', '115px');
            const mainContent = document.getElementById('mainContent');
            const mainFooter = document.getElementById('mainFooter');
            if (mainContent) mainContent.style.setProperty('padding-left', '115px');
            if (mainFooter) mainFooter.style.setProperty('padding-left', '115px');
            document.getElementById('lang-container').style.setProperty('width', '95%');
        } else {
            sideBar.style.setProperty('width', '230px');
            const mainContent = document.getElementById('mainContent');
            const mainFooter = document.getElementById('mainFooter');
            if (mainContent) mainContent.style.setProperty('padding-left', '230px');
            if (mainFooter) mainFooter.style.setProperty('padding-left', '230px');
            document.getElementById('lang-container').style.setProperty('width', '49%');
        }
        isToggled.value = !isToggled.value;
    }
}

function toggleSideBarMobile() {
    const sideBar = document.getElementById('sideBar');
    const overlay = document.getElementById('overlaySideBar');
    if (sideBar) {
        if (isToggled.value) {
            overlay.classList.remove('d-none');
            sideBar.classList.add('show-mobile');
        } else {
            overlay.classList.add('d-none');
            sideBar.classList.remove('show-mobile');
        }
        isToggled.value = !isToggled.value;
    }
}

const isMobile = ref(false);

function checkMobile() {
    isMobile.value = window.innerWidth <= 768;
    const mainContent = document.getElementById('mainContent');
    const mainFooter = document.getElementById('mainFooter');
    const sideBar = document.getElementById('sideBar');
    if (sideBar.classList.contains('d-none')) {
        sideBar.classList.remove('d-none');
    }
    if (mainContent && mainFooter) {
        if (isMobile.value) {
            mainContent.style.setProperty('padding-left', '0px');
            mainFooter.style.setProperty('padding-left', '0px');
            if (sideBar) sideBar.style.setProperty('width', '0px');
            if (sideBar) sideBar.classList.add('d-none');
            isToggled.value = false;
        } else {
            isToggled.value = true;
            if (isToggled.value) {
                mainContent.style.setProperty('padding-left', '230px');
                mainFooter.style.setProperty('padding-left', '230px');
                if (sideBar) sideBar.style.setProperty('width', '230px');
            } else {
                mainContent.style.setProperty('padding-left', '115px');
                mainFooter.style.setProperty('padding-left', '115px');
                if (sideBar) sideBar.style.setProperty('width', '115px');
            }
        }
    }
}

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});
</script>

<style scoped></style>