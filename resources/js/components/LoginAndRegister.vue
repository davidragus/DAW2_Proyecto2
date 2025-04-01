<template>
  <div class="d-flex gap-3">
    <Login @open-register-dialog="openRegisterDialog" :visible="loginDialogVisible" @update:visible="updateLoginDialogVisible"/>
    <Register @open-login-dialog="openLoginDialog" :visible="registerDialogVisible" @update:visible="updateRegisterDialogVisible"/>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
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

const closeRegisterDialog = () => {
  registerDialogVisible.value = false;
};

const updateRegisterDialogVisible = (newValue) => {
  registerDialogVisible.value = newValue;
};

const updateLoginDialogVisible = (newValue) => {
  loginDialogVisible.value = newValue;
};

const route = useRoute();
const router = useRouter();
watch(
  () => route.query.openModal,
  (newValue) => {
    if (newValue === 'login') {
      router.push('/')
      openLoginDialog();
    } else if (newValue === 'register') {
      router.push('/')
      openRegisterDialog();
    }
  },
  { immediate: true }
);
watch(
  () => route.query.closeModal,
  (newValue) => {
    if (newValue === 'login') {
      updateLoginDialogVisible(false);
      router.push({ query: { ...route.query, closeModal: undefined } });
    } else if (newValue === 'register') {
      console.log("cerrandoRegister")
      closeRegisterDialog();
      router.push({ query: { ...route.query, closeModal: undefined } });
    }
  },
  { immediate: true }
);
</script>

<style scoped>
</style>
