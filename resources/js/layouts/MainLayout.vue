<template>
	<MainNav />
	<router-view />
	<Footer />
</template>

<script setup>
import MainNav from "./MainNav.vue";
import Footer from "../components/MainFooter.vue";
</script>

<style scoped></style>