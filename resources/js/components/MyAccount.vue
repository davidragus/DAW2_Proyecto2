<template>
    <div id="mainContent" class="d-flex">
        <aside class="sidebar">
            <ul>
                <li class="active"><a href="#">ACCOUNT DETAILS</a></li>
                <li><a href="#">BALANCE HISTORY</a></li>
                <li><a href="#">GAME HISTORY</a></li>
            </ul>
        </aside>
        <div class="container">
            <div class="row justify-content-center ">
                <div class="col-11 text-center">
                    <img src="/images/user-icon.png" alt="User Icon" class="user-icon">
                    <h2 class="text-white">{{ userCopy.name }} {{ userCopy.surname1 }} {{ userCopy.surname2 }}</h2>
                </div>
                <div class="col-12 col-md-6">
                    <form @submit.prevent="updateUser">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" v-model="userCopy.name" required>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" class="form-control" id="surname" v-model="userCopy.surname1" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastSurname" class="form-label">Last surname (optional)</label>
                            <input type="text" class="form-control" id="lastSurname" v-model="userCopy.surname2" required>
                        </div>
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="birthdate" v-model="userCopy.birthdate" required>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Country of residence</label>
                            <select class="form-select" id="country" v-model="userCopy.country" required>
                                <option>Spain</option>
                                <option>USA</option>
                                <option>UK</option>
                                <option>France</option>
                                <option>Mexico</option>
                                <option>Peru</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" v-model="userCopy.username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" v-model="userCopy.email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone number</label>
                            <input type="text" class="form-control" id="phone" v-model="userCopy.phone_number" required>
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
import Swal from 'sweetalert2';

const auth = authStore();
const logedUser = computed(() => auth.user);
const userCopy = ref({ ...logedUser.value });

const updateUser = async () => {
    try {
        const response = await axios.put(`/api/users/${userCopy.value.id}`, {
            name: userCopy.value.name,
            surname1: userCopy.value.surname1,
            surname2: userCopy.value.surname2,
            birthdate: userCopy.value.birthdate,
            country: userCopy.value.country,
            username: userCopy.value.username,
            email: userCopy.value.email,
            phone_number: userCopy.value.phone_number,
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

onMounted(() => {
    document.getElementById('mainContent').classList.add('ml-4');
    if(window.innerWidth <= 768) {
        document.getElementById('mainContent').style.paddingLeft = '0';
    } else {
        document.getElementById('mainContent').style.paddingLeft = '230px';
    }
});

onUnmounted(() => {
    document.getElementById('mainContent').classList.remove('ml-4');
});
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
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background-color: #ccc;
    display: block;
    margin: 0 auto 10px;
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
</style>