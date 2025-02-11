<template>
  <div class="d-flex">
    <Login @open-register-dialog="openRegisterDialog" :visible="loginDialogVisible" @update:visible="updateLoginDialogVisible"/>
    <Register @open-login-dialog="openLoginDialog" :visible="registerDialogVisible" @update:visible="updateRegisterDialogVisible"/>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Login from "../components/LoginDialog.vue";
import Register from "../components/RegisterDialog.vue";

const registerDialogVisible = ref(false);
const loginDialogVisible = ref(false);

const openRegisterDialog = () => {
  registerDialogVisible.value = true;
};

const openLoginDialog = () => {
  loginDialogVisible.value = true;
};

const updateRegisterDialogVisible = (newValue) => {
  registerDialogVisible.value = newValue;
};

const updateLoginDialogVisible = (newValue) => {
  loginDialogVisible.value = newValue;
};
</script>

<style scoped>
</style>
