<template>
  <div>
    <Button @click="visible = true" class="mx-3" buttonColor="red" buttonStyle="filled" buttonSize="normal">LOG IN</Button>
    
    <Dialog v-model:visible="visible" modal header="LOG IN" :style="{ width: '400px' }" class="login-dialog" @update:visible="onDialogClose">
      <div class="login-container">
        <form @submit.prevent="submitLogin">
          <input v-model="loginForm.email" type="email" class="form-control mb-2" placeholder="Email" required />
          <div v-if="validationErrors.email" class="text-danger">
            <div v-for="message in validationErrors.email" :key="message">{{ message }}</div>
          </div>

          <input v-model="loginForm.password" type="password" class="form-control mb-2" placeholder="Password" required />
          <div v-if="validationErrors.password" class="text-danger">
            <div v-for="message in validationErrors.password" :key="message">{{ message }}</div>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" v-model="loginForm.remember" id="flexCheckIndeterminate">
            <label class="form-check-label" for="flexCheckIndeterminate">
              Remember me
            </label>
          </div>

          <a href="#" class="forgot-password">Forgot your password?</a>

          <PrimeButton type="submit" label="LOG IN" class="w-100 p-button-primary mt-3" :disabled="processing" />
        </form>
        <p class="text-center mt-3 d-flex justify-content-center">
          You don’t have an account? <a href="#" class="create-account ms-2" @click.prevent="openRegisterDialog">Create one</a>
        </p>
      </div>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, defineEmits, watch } from 'vue';
import Button from "../components/Button.vue";
import PrimeButton from 'primevue/button';
import Dialog from 'primevue/dialog';
import useAuth from '@/composables/auth';

const props = defineProps(['visible']);
const visible = ref(props.visible);

watch(() => props.visible, (newVal) => {
  visible.value = newVal;
});

const onDialogClose = (newValue) => {
  if (!newValue) {
    visible.value = false;
    emit('update:visible', false);
  }
};

const { loginForm, validationErrors, processing, submitLogin } = useAuth();
const emit = defineEmits(['open-register-dialog', 'update:visible']);

const openRegisterDialog = () => {
  visible.value = false;
  emit('update:visible', false);
  emit('open-register-dialog');
};
</script>

<style scoped>
.login-container {
  padding: 20px;
  text-align: center;
}

.form-control {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.text-danger {
  color: red;
  font-size: 12px;
}

.forgot-password,
.create-account {
  display: block;
  font-size: 14px;
  color: blue;
  text-decoration: none;
}

.forgot-password:hover,
.create-account:hover {
  text-decoration: underline;
}
</style>
