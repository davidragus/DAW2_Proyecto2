<template>
  <div>
    <Button @click="visible = true" class="login-button" buttonColor="red" buttonStyle="filled" buttonSize="normal">LOG IN</Button>
    
    <Dialog v-model:visible="visible" modal header="LOG IN" :style="{ width: '400px' , backgroundColor: '#212121', color: 'white' , borderColor: '#3B3B3B'}" class="login-dialog" @update:visible="onDialogClose">
      <div class="login-container">
        <form @submit.prevent="submitLogin">
          <input v-model="loginForm.email" type="email" class="form-control mb-2" placeholder="Email" required />
          <div v-if="validationErrors.email" class="text-danger">
            <div v-for="message in validationErrors.email" :key="message">{{ message }}</div>
          </div>

          <div class="password-container">
            <input :type="passwordFieldType" v-model="loginForm.password" class="form-control mb-2" placeholder="Password" required />
            <i class="fa fa-eye password-toggle" @mousedown="showPassword" @mouseup="hidePassword" @mouseleave="hidePassword"></i>
          </div>
          <div v-if="validationErrors.password" class="text-danger">
            <div v-for="message in validationErrors.password" :key="message">{{ message }}</div>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" v-model="loginForm.remember" id="flexCheckIndeterminate">
            <label class="form-check-label" for="flexCheckIndeterminate">
              Remember me
            </label>
          </div>

          <PrimeButton type="submit" label="LOG IN" class="w-100 p-button-primary mt-3" :disabled="processing" :style="{backgroundColor: 'red', color: 'white', borderColor: 'red'}"/>
          <a href="#" class="forgot-password">Forgot your password?</a>
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
import { useRoute, useRouter } from 'vue-router';
import Button from "../components/Button.vue";
import PrimeButton from 'primevue/button';
import Dialog from 'primevue/dialog';
import useAuth from '@/composables/auth';

const props = defineProps(['visible']);
const visible = ref(props.visible);

watch(() => props.visible, (newVal) => {
  visible.value = newVal;
});

const route = useRoute();
const router = useRouter();

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

const passwordFieldType = ref('password');

const showPassword = () => {
  passwordFieldType.value = 'text';
};

const hidePassword = () => {
  passwordFieldType.value = 'password';
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
  border: 1px solid #414141;
  border-radius: 5px;
  background-color: #313131;
  color: white;

}

.form-control::placeholder {
  color: #bcbcbc;
}

.text-danger {
  color: red;
  font-size: 12px;
}
.forgot-password{
  margin-top: 10px;
}
.forgot-password,
.create-account {
  display: block;
  font-size: 14px;
  color: white;
  text-decoration: underline !important;
}

.forgot-password:hover,
.create-account:hover {
  color: red;
}
.form-check {
  display: flex;
  gap: 10px;
  margin-top: 10px;
  margin-bottom: 10px;
}
.form-check-input{
  background-color: #414141;
  border-color: #313131;
}
.form-check-input:checked{
  background-color:red;
  border-color: #cb0000;
}
.form-check-input:focus{
  border-color: #313131;
  outline: 0;
  box-shadow: 0 0 0 0.25rem #31313133;
}

.login-button {
  font-size: 16px;
  padding: 10px 20px;
}

.password-container {
  position: relative;
}

.password-toggle {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  cursor: pointer;
  color: #bcbcbc;
}

@media (max-width: 768px) {
  .login-button {
    font-size: 14px;
    padding: 5px 10px;
  }
}
</style>
