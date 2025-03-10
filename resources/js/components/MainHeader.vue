<template>
    <nav class="navbar py-0">
        <div class="d-flex align-items-center">
            <SideBarButton v-if="!isMobile" @toggle-sidebar="$emit('toggle-sidebar')" />
            <SideBarButtonMobile v-else @toggle-sidebar-mobile="$emit('toggle-sidebar-mobile')" />
            <div class="vertical-separator"></div>
            <WebsiteLogo />
        </div>
        <div class="d-flex align-items-center ms-auto">
            <div v-if="!authStore().user?.name">
                <LoginAndRegister />
            </div>
            <div v-else class="d-flex align-items-center position-relative">
                <div ref="loginContainer" class="login-container gray-background p-2 rounded-circle" @click="toggleDropdown">
                    <i class="fa-regular fa-user icon-24"></i>
                </div>
                <div v-show="dropdownVisible" class="dropdown-menu">
                    <a href="#" class="dropdown-item">My account</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">Add chips</a>
                    <a href="#" class="dropdown-item">Withdraw</a>
                    <a href="#" class="dropdown-item" @click="logout">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import SideBarButton from "../components/SideBarButton.vue";
import SideBarButtonMobile from "../components/SideBarButtonMobile.vue";
import LoginAndRegister from "../components/LoginAndRegister.vue";
import WebsiteLogo from "../components/WebsiteLogo.vue";
import useAuth from "@/composables/auth";
import { authStore } from "../store/auth";

defineProps({ isMobile: Boolean });

const dropdownVisible = ref(false);
const loginContainer = ref(null);

function toggleDropdown() {
    dropdownVisible.value = !dropdownVisible.value;
}

function handleClickOutside(event) {
    if (loginContainer.value && !loginContainer.value.contains(event.target)) {
        dropdownVisible.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

function logout() {
    // Lógica para cerrar sesión
    authStore().logout();
    dropdownVisible.value = false;
}
</script>

<style scoped>
nav {
    background-color: #1F1F1F;
    position: sticky;
    top: 0;
    height: 70px;
    border-bottom: 2px solid red;
    box-shadow: 0 10px 25px 10px #ff000040;
    z-index: 999;
    padding: 0 15px;
}

.vertical-separator {
    height: 24px;
    border-right: 1px solid #353535;
    margin-right: 30px;
}

.login-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    cursor: pointer;
}

.gray-background {
    background-color: #898989;
}

.icon-24 {
    font-size: 24px;
}

.dropdown-menu {
    position: absolute;
    top: 60px;
    right: 0;
    background-color: #1F1F1F;
    border: 1px solid #353535;
    border-radius: 5px;
    z-index: 1000;
    width: 200px;
    display: flex;
    flex-direction: column;
}

.dropdown-item {
    padding: 10px 15px;
    color: white;
    text-decoration: none;
    display: block;
}

.dropdown-item:hover {
    background-color: #353535;
}

.dropdown-divider {
    height: 1px;
    background-color: #353535;
    margin: 5px 0;
}

@media (max-width: 768px) {
    nav {
        height: auto;
        padding: 10px 15px;
    }
    .vertical-separator {
        display: none;
    }
    .login-container {
        width: 40px;
        height: 40px;
    }
    .icon-24 {
        font-size: 20px;
    }
}
</style>