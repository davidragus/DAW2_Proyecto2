<template>
	<div>
		<Navbar />
		<SideBar />
		<router-view></router-view>
	</div>
</template>
<script setup>
import Navbar from './Nav.vue'
import SideBar from "../components/SideBar.vue";

</script>