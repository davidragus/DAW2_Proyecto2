<template>
    <nav :class="{ 'd-none': isMobile, 'd-flex': !isMobile }" id="sideBar" class="flex-column justify-content-between align-items-center">
        <ul class="mt-5 w-75">
            <li><a href="" class="d-block container-fluid">icono<span :class="{ 'd-none': !visible }">HOME</span></a></li>
            <hr>
            <li>
                <a href="" class="d-flex container-fluid align-items-center">
                    <img src="/images/iRoulette.svg" alt="" class="icon color-dark">
                    <span :class="{ 'd-none': !visible }">ROULETTE</span>
                </a>
            </li>
            <li><a href="" class="d-block container-fluid">icono<span :class="{ 'd-none': !visible }">BINGO</span></a></li>
            <li>
                <a href="" class="d-flex container-fluid align-items-center">
                    <iBlackJack class="icon"></iBlackJack>
                    <span :class="{ 'd-none': !visible }">BLACKJACK</span>
                </a>
            </li>
        </ul>
        <div class="w-100">
            <LocaleSwitcher></LocaleSwitcher>
            <button class="bottom-buton live-chat" :class="{ 'd-none': !visible }">
                <img src="/images/chat-bot_dark.png" alt="england" class="icon">
                <p class="color-white">LIVE CHAT</p>
            </button>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';
import LocaleSwitcher from './LocaleSwitcher.vue';
import iRoulette from './iRoulette.vue';
import iBlackJack from './iBlackJack.vue';

defineProps({ visible: Boolean, isMobile: Boolean });

// Estado para el modal de idiomas
const showModal = ref(false);

const languages = ref([
    "English", "Español", "Français", "Deutsch",
    "Italiano", "Português", "Nederlands", "中文",
    "日本語", "한국어", "Русский", "العربية"
]);
</script>

<style scoped>
#sideBar {
    position: fixed;
    left: 0;
    background-color: #212121;
    width: 230px;
    height: calc(100% - var(--main-header-height));
    box-shadow: 5px 0 20px 10px rgba(0, 0, 0, 0.5);
}

ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

a {
    color: white;
    font-size: 18px;
}

a:hover {
    color: red;
}

.icon {
    width: 24px;
    height: 24px;
}

.bottom-buton {
    width: 49%;
    height: 75px;
    background-color: #212121;
    border: none;
    border-right: 2px solid #3B3B3B;
    border-top: 2px solid #3B3B3B;
    border-top-right-radius: 25px;
}

.color-white {
    color: white;
}

/* Estilos para la lista de idiomas */
.languages-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    padding: 10px;
}

.language-item {
    background: #f0f0f0;
    padding: 8px;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    transition: background 0.3s;
}

.language-item:hover {
    background: #d4d4d4;
}

@media (max-width: 768px) {
    #sideBar {
        width: 100%;
        height: auto;
        position: relative;
        box-shadow: none;
    }
    ul {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .bottom-buton {
        width: 100%;
        height: 50px;
        border-top-right-radius: 0;
    }
}
</style>
