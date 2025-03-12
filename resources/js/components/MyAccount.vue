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
                    <h2 class="text-white">{{ user.name }} {{ user.surname1 }} {{ user.surname2 }}</h2>
                </div>
                <div class="col-12 col-md-6">
                    <form @submit.prevent="updateUser">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" v-model="user.name">
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" class="form-control" id="surname" v-model="user.surname1">
                        </div>
                        <div class="mb-3">
                            <label for="lastSurname" class="form-label">Last surname (optional)</label>
                            <input type="text" class="form-control" id="lastSurname" v-model="user.surname2">
                        </div>
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="birthdate" v-model="user.birthdate">
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Country of residence</label>
                            <select class="form-select" id="country" v-model="user.country">
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
                            <input type="text" class="form-control" id="username" v-model="user.username">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" v-model="user.email">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone number</label>
                            <input type="text" class="form-control" id="phone" v-model="user.phone_number">
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
import { computed, onMounted, onUnmounted } from 'vue';
import { authStore } from "../store/auth";

const auth = authStore();
const user = computed(() => auth.user);

const updateUser = async () => {
    try {
        const response = await axios.put(`/api/users/${user.value.id}`, {
            name: user.value.name,
            surname1: user.value.surname1,
            surname2: user.value.surname2,
            birthdate: user.value.birthdate,
            country: user.value.country,
            username: user.value.username,
            email: user.value.email,
            phone_number: user.value.phone_number,
        });
        // Actualizar el estado del usuario en Vuex
        store.commit('auth/setUser', response.data);
        alert('User updated successfully');
    } catch (error) {
        console.error('Error updating user:', error);
        alert('Failed to update user');
    }
};

onMounted(() => {
    console.log('onmount3');
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
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-label {
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
</style>