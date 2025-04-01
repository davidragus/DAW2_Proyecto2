<template>
    <div id="mainContent" class="d-flex">
        <aside class="sidebar">
            <ul>
                <li class="active"><a href="#">ACCOUNT DETAILS</a></li>
                <li><a href="#">BALANCE HISTORY</a></li>
                <li><a href="#">GAME HISTORY</a></li>
                <li><a><router-link to="/achievements" class="dropdown-item">ACHIEVEMENTS</router-link></a></li>
            </ul>
        </aside>
        <div class="container">
            <div class="row justify-content-center ">
                <div class="col-11 text-center">
                    <div class="d-flex justify-content-center align-items-center flex-column gap-4">
                        <Avatar v-if="authStore().user.avatar" :image="authStore().user.avatar" size="xlarge"
                            shape="circle" class="user-icon" />
                        <Avatar v-else :label="authStore().user.name.substring(0, 1)" size="xlarge" shape="circle"
                            class="user-icon" />
                        <div
                            class="card flex flex-col items-center gap-6 w-100 justify-content-center align-items-center">
                            <FileUpload mode="basic" @select="onFileSelect" customUpload auto severity="secondary"
                                class="p-button-outlined" />
                            <img v-if="src" :src="src" alt="Image" class="user-icon" style="filter: grayscale(100%)" />
                            <Button v-if="src" label="Upload" @click="uploadImage" class="image-button" />
                        </div>
                    </div>
                    <h2 class="text-white">{{ userCopy.name }} {{ userCopy.surname1 }} {{ userCopy.surname2 }}</h2>
                </div>
                <div class="col-12 col-md-6">
                    <form @submit.prevent="submitForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" v-model="userCopy.name"
                                :class="{ 'is-invalid': errors.name }">
                            <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" class="form-control" id="surname" v-model="userCopy.surname1"
                                :class="{ 'is-invalid': errors.surname1 }">
                            <div v-if="errors.surname1" class="invalid-feedback">{{ errors.surname1 }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="lastSurname" class="form-label">Last surname (optional)</label>
                            <input type="text" class="form-control" id="lastSurname" v-model="userCopy.surname2"
                                :class="{ 'is-invalid': errors.surname2 }">
                            <div v-if="errors.surname2" class="invalid-feedback">{{ errors.surname2 }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="birthdate" v-model="userCopy.birthdate"
                                :class="{ 'is-invalid': errors.birthdate }">
                            <div v-if="errors.birthdate" class="invalid-feedback">{{ errors.birthdate }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Country of residence</label>
                            <Select v-model="userCopy.country" :options="countries" optionLabel="name"
                                optionValue="code" placeholder="Country of residence" class="w-100"
                                :style="{ backgroundColor: '#313131', color: 'white', borderColor: '#414141',  }" />
                            <div v-if="errors.country" class="invalid-feedback">{{ errors.country }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" v-model="userCopy.username"
                                :class="{ 'is-invalid': errors.username }">
                            <div v-if="errors.username" class="invalid-feedback">{{ errors.username }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" v-model="userCopy.email"
                                :class="{ 'is-invalid': errors.email }">
                            <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone number</label>
                            <input type="text" class="form-control" id="phone" v-model="userCopy.phone_number"
                                :class="{ 'is-invalid': errors.phone_number }">
                            <div v-if="errors.phone_number" class="invalid-feedback">{{ errors.phone_number }}</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-3">SAVE DATA</button>
                        <button type="button" class="btn btn-secondary w-100">CHANGE PASSWORD</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { authStore } from "../store/auth";
import useCountries from '@/composables/countries';
import Swal from 'sweetalert2';
import * as yup from 'yup';

const auth = authStore();
const logedUser = computed(() => auth.user);
const userCopy = ref({ ...logedUser.value });

const { getCountries, countries } = useCountries();

const schema = yup.object({
    name: yup.string().required('Name is required'),
    surname1: yup.string().required('Surname is required'),
    surname2: yup.string().nullable(),
    birthdate: yup.date().required('Birthdate is required'),
    country: yup.string().required('Country is required'),
    username: yup.string().required('Username is required'),
    email: yup.string().email('Email must be valid').required('Email is required'),
    phone_number: yup.string().required('Phone number is required'),
});

const errors = ref({});

const validateForm = async () => {
    errors.value = {};
    try {
        await schema.validate(userCopy.value, { abortEarly: false });
        return true;
    } catch (validationErrors) {
        validationErrors.inner.forEach(error => {
            errors.value[error.path] = error.message;
        });
        return false;
    }
};

const updateUser = async () => {
    try {
        const response = await axios.put(`/api/users/${logedUser.value.id}`, {
            name: userCopy.value.name,
            surname1: userCopy.value.surname1,
            surname2: userCopy.value.surname2,
            birthdate: userCopy.value.birthdate,
            country: userCopy.value.country,
            username: userCopy.value.username,
            email: userCopy.value.email,
            phone_number: userCopy.value.phone_number,
            dni: logedUser.value.dni,
        });
        Swal.fire({
            icon: 'success',
            title: 'User updated successfully',
            showConfirmButton: false,
            timer: 1500,
            background: '#2A2A2A',
            color: '#ffffff'
        });
        auth.user = { ...userCopy.value };
    } catch (error) {
        console.error('Error updating user:', error);
        Swal.fire({
            icon: 'error',
            title: 'Failed to update user',
            text: error.response?.data?.message || 'An error occurred',
            background: '#2A2A2A',
            color: '#ffffff'
        });
    }
};

const submitForm = async () => {
    const isValid = await validateForm();
    if (isValid) {
        await updateUser();
    }
};

onMounted(() => {
    getCountries();
    document.getElementById('mainContent').classList.add('ml-4');
    if (window.innerWidth <= 768) {
        document.getElementById('mainContent').style.paddingLeft = '0';
    } else {
        document.getElementById('mainContent').style.paddingLeft = '230px';
    }
});

onUnmounted(() => {
    document.getElementById('mainContent').classList.remove('ml-4');
});
const src = ref(null);
const file = ref(null);

function onFileSelect(event) {
    file.value = event.files[0];
    const reader = new FileReader();

    reader.onload = async (e) => {
        src.value = e.target.result;
    };

    reader.readAsDataURL(file.value);
}
async function uploadImage() {
    try {
        let formData = new FormData();
        formData.append("file", file.value);
        const response = await axios.post('/api/users/updateImg/'+authStore().user.id, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        console.log(response.data.data.avatar);
        authStore().user.avatar = response.data.data.avatar;
        src.value = null;

        Swal.fire({
            icon: 'success',
            title: 'Image uploaded successfully',
            showConfirmButton: false,
            timer: 1500,
            background: '#2A2A2A',
            color: '#ffffff'
        });
    } catch (error) {
        console.error('Error uploading image:', error);
        Swal.fire({
            icon: 'error',
            title: 'Failed to upload image',
            text: error.response?.data?.message || 'An error occurred',
            background: '#2A2A2A',
            color: '#ffffff'
        });
    }
}

// Routes 
const routeToAchivements = () => {
    console.log('routeToAchivements');
    router.push('/achievements');
}
</script>

<style scoped>
#mainContent {
    display: flex;
    padding: 20px;
    background-color: #2A2A2A;
}

.sidebar {
    width: 200px;
    margin-right: 20px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin-bottom: 10px;
}

.sidebar ul li a {
    text-decoration: none;
    color: #ffffff;
}

.sidebar ul li a:hover {
    text-decoration: none;
    color: #ffffff;
    font-weight: bold;
    transition: 0.2s;
}

.user-icon {
    display: block;
    width: 100px;
    /* Ajusta el ancho del avatar */
    height: 100px;
    /* Ajusta la altura del avatar */
    border-radius: 50%;
    /* Asegura que sea redondo */
    overflow: hidden;
    /* Recorta cualquier contenido que sobresalga */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 50px;
    /* Ajusta el tamaño de la inicial si no hay imagen */
    background-color: #ccc;
    /* Color de fondo para avatares sin imagen */
    color: #fff;
    /* Color del texto */
}

h2 {
    margin-bottom: 20px;
}

form {
    background-color: #2A2A2A;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-label {
    color: white;
    font-weight: bold;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.active {
    text-decoration: underline;
    color: #ffffff;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #414141;
    border-radius: 5px;
    background-color: #313131;
    color: white;
}

.form-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #414141;
    border-radius: 5px;
    background-color: #313131;
    color: white;
}

.is-invalid {
    border-color: red;
}

.invalid-feedback {
    color: red;
    font-size: 0.875em;
}

.image-button {
    background-color: #ff0000;
    color: white;
    border: none !important;
    padding: 10px;
    cursor: pointer;
    width: 100%;
    text-align: center;
}

.image-button:hover {
    background-color: #c00000 !important;
    color: white !important;
}
</style>