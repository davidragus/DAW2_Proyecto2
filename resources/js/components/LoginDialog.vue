<template>
  <div>
    <Button label="Sign Up" @click="visible = true" class="p-button-primary" />
    <Dialog v-model:visible="visible" modal header="Sign Up" :style="{ width: '400px' }">
      <div class="signup-container p-4">
        <form @submit.prevent="submitForm">
          <div class="mb-3">
            <Dropdown v-model="form.country" :options="countries" placeholder="Country of residence" class="w-100" />
          </div>
          <input v-model="form.nationalId" type="text" class="form-control mb-3" placeholder="National Identity Card Number" required />
          
          <div class="mb-3">
            <input type="file" @change="handleFileUpload" class="form-control" required />
          </div>
          <div class="border rounded">
            <input v-model="form.name" type="text" class="form-control first-name-rounded border-0 border-bottom" placeholder="Name" required />
            <input v-model="form.surname" type="text" class="form-control rounded-0 border-0 border-bottom" placeholder="Surname" required />
            <input v-model="form.secondSurname" type="text" class="form-control border-0" placeholder="Second surname (optional)" />
          </div>
          <div class="d-flex justify-content-center rounded border my-3">
            <div class="gender-option left-gender" @click="form.gender = 'male'" :class="{ active: form.gender === 'male' }">
              ♂
            </div>
            <div class="gender-option" @click="form.gender = 'female'" :class="{ active: form.gender === 'female' }">
              ♀
            </div>
            <div class="gender-option right-gender" @click="form.gender = 'other'" :class="{ active: form.gender === 'other' }">
              ⚧
            </div>
          </div>
          
          <div class="d-flex mb-3">
            <input v-model="form.day" type="text" class="form-control me-1" placeholder="Day (dd)" required />
            <input v-model="form.month" type="text" class="form-control mx-1" placeholder="Month (mm)" required />
            <input v-model="form.year" type="text" class="form-control ms-1" placeholder="Year (yyyy)" required />
          </div>
          
          <input v-model="form.phone" type="text" class="form-control mb-3" placeholder="Phone number" required />
          <input v-model="form.email" type="email" class="form-control mb-3" placeholder="Email" required />
          <input v-model="form.username" type="text" class="form-control mb-3" placeholder="Username" required />
          
          <div class="d-flex mb-3">
            <input v-model="form.password" type="password" class="form-control me-1" placeholder="Password" required />
            <input v-model="form.confirmPassword" type="password" class="form-control ms-1" placeholder="Confirm password" required />
          </div>
          
          <div class="form-check mb-3">
            <input v-model="form.terms" type="checkbox" class="form-check-input" required />
            <label class="form-check-label">I confirm that I am over the majority age of my country and that I accept the Terms and Conditions and the Privacy Policy</label>
          </div>
          
          <Button type="submit" label="SIGN UP" class="w-100 p-button-primary" />
          
          <p class="text-center mt-3">Are you already registered? <a href="#" @click.prevent="visible = false">Log in</a></p>
        </form>
      </div>
    </Dialog>
  </div>
</template>

<script>
import { ref } from 'vue';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';

export default {
  components: { Dropdown, Button, Dialog },
  setup() {
    const visible = ref(false);
    const form = ref({
      country: '',
      nationalId: '',
      name: '',
      surname: '',
      secondSurname: '',
      gender: '',
      day: '',
      month: '',
      year: '',
      phone: '',
      email: '',
      username: '',
      password: '',
      confirmPassword: '',
      terms: false,
      idImage: null,
    });
    
    const countries = ref(['Spain', 'USA', 'UK', 'France', 'Mexico', 'Peru']);
    
    const handleFileUpload = (event) => {
      form.value.idImage = event.target.files[0];
    };
    
    const submitForm = () => {
      if (!form.value.idImage) {
        alert("Please upload your National Identity Card image.");
        return;
      }
      if (form.value.password !== form.value.confirmPassword) {
        alert("Passwords do not match");
        return;
      }
      alert("Form submitted successfully!");
      visible.value = false;
    };
    
    return { visible, form, countries, handleFileUpload, submitForm };
  },
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
