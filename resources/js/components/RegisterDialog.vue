<template>
  <div>
    <Button @click="visible = true" class="mx-3" buttonColor="yellow" buttonStyle="outlined" buttonSize="normal">REGISTER</Button>
    <Dialog v-model:visible="visible" modal header="Sign Up" :style="{ width: '400px' }" @update:visible="onDialogClose">
      <div class="signup-container p-4">
        <form @submit.prevent="submitRegister">
          <div class="mb-3">
            <Dropdown v-model="registerForm.country" :options="countries" placeholder="Country of residence" class="w-100" />
          </div>
          <input v-model="registerForm.dni" type="text" class="form-control mb-3" placeholder="National Identity Card Number" required />
          
          <div class="mb-3">
            <input type="file" @change="handleFileUpload" class="form-control" required />
          </div>
          <div class="border rounded">
            <input v-model="registerForm.name" type="text" class="form-control first-name-rounded border-0 border-bottom" placeholder="Name" required />
            <input v-model="registerForm.surname1" type="text" class="form-control rounded-0 border-0 border-bottom" placeholder="Surname" required />
            <input v-model="registerForm.surname2" type="text" class="form-control border-0" placeholder="Second surname (optional)" />
          </div>
          <div class="d-flex justify-content-center rounded border my-3">
            <div class="gender-option left-gender" @click="registerForm.gender = 'M'" :class="{ active: registerForm.gender === 'M' }">
              ♂
            </div>
            <div class="gender-option" @click="registerForm.gender = 'F'" :class="{ active: registerForm.gender === 'F' }">
              ♀
            </div>
            <div class="gender-option right-gender" @click="registerForm.gender = 'N'" :class="{ active: registerForm.gender === 'N' }">
              ⚧
            </div>
          </div>
          
          <div class="d-flex mb-3">
            <input v-model="registerForm.day" type="text" class="form-control me-1" placeholder="Day (dd)" required />
            <input v-model="registerForm.month" type="text" class="form-control mx-1" placeholder="Month (mm)" required />
            <input v-model="registerForm.year" type="text" class="form-control ms-1" placeholder="Year (yyyy)" required />
          </div>
          
          <input v-model="registerForm.phone_number" type="text" class="form-control mb-3" placeholder="Phone number" required />
          <input v-model="registerForm.email" type="email" class="form-control mb-3" placeholder="Email" required />
          <input v-model="registerForm.username" type="text" class="form-control mb-3" placeholder="Username" required />
          
          <div class="d-flex mb-3">
            <input v-model="registerForm.password" type="password" class="form-control me-1" placeholder="Password" required />
            <input v-model="registerForm.password_confirmation" type="password" class="form-control ms-1" placeholder="Confirm password" required />
          </div>
          
          <div class="form-check mb-3">
            <input v-model="registerForm.terms" type="checkbox" class="form-check-input" required />
            <label class="form-check-label">I confirm that I am over the majority age of my country and that I accept the Terms and Conditions and the Privacy Policy</label>
          </div>
          
          <PrimeButton type="submit" label="SIGN UP" class="w-100 p-button-primary" :disabled="processing" />
          
          <p class="text-center mt-3">Are you already registered? <a href="#" @click.prevent="openLoginDialog">Log in</a></p>
        </form>
      </div>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, defineEmits, watch } from 'vue';
import Dropdown from 'primevue/dropdown';
import Button from '../components/Button.vue';
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

const { registerForm, validationErrors, processing, submitRegister } = useAuth();
const countries = ref(['Spain', 'USA', 'UK', 'France', 'Mexico', 'Peru']);
const emit = defineEmits(['open-login-dialog', 'update:visible']);

const handleFileUpload = (event) => {
  registerForm.idImage = event.target.files[0];
};

const openLoginDialog = () => {
  visible.value = false;
  emit('update:visible', false);
  emit('open-login-dialog');
};
</script>

<style scoped>
.signup-container {
  padding: 20px;
}
.gender-option {
  background-color: #ffff;
  flex: 1;
  text-align: center;
  cursor: pointer;
  padding: 0px;
  margin: 0;
  font-size: 20px;
  border: 1px solid transparent;
}
.gender-option.active {
  background-color: #ff0000;
  color: white;
  border: 1px solid #ff0000;
}
.left-gender {
  border-radius: 0.375rem 0 0 0.375rem;
  border-right: 1px solid  #dee2e6;
}
.right-gender {
  border-radius: 0 0.375rem 0.375rem 0;
  border-left: 1px solid  #dee2e6;
}
.border-bottom {
  border-bottom: 1px solid  #dee2e6 !important;
}
.first-name-rounded{
  border-radius: 0.375rem 0.375rem 0 0 ;
}
.form-control:focus{
  box-shadow: none;
  border-color: #ced4da;
}
</style>
