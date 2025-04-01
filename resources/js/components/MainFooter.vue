<template>
    <footer id="mainFooter" class="d-flex flex-column">
        <div class="d-flex flex-row w-100 justify-content-between p-5 footer-content">
            <ul>
                <li :class="{ 'clickable': isMobile }" class="li-title" @click="toggleList(0)"><strong>Casino games</strong></li>
                <li v-show="isListVisible[0] || !isMobile"><a href="">Roulette</a></li>
                <li v-show="isListVisible[0] || !isMobile"><a href="">Blackjack</a></li>
                <li v-show="isListVisible[0] || !isMobile"><a href="">Bingo</a></li>
            </ul>
            <ul>
                <li :class="{ 'clickable': isMobile }" class="li-title" @click="toggleList(1)"><strong>Who we are</strong></li>
                <li v-show="isListVisible[1] || !isMobile"><a href="">Help</a></li>
                <li v-show="isListVisible[1] || !isMobile"><a href="">Affiliates</a></li>
                <li v-show="isListVisible[1] || !isMobile"><a href="">Site map</a></li>
            </ul>
            <ul>
                <li :class="{ 'clickable': isMobile }" class="li-title" @click="toggleList(2)"><strong>Safe play</strong></li>
                <li v-show="isListVisible[2] || !isMobile"><a href="">Privacy policy</a></li>
                <li v-show="isListVisible[2] || !isMobile"><a href="">User agreement</a></li>
                <li v-show="isListVisible[2] || !isMobile"><a href="">Bonus policy</a></li>
                <li v-show="isListVisible[2] || !isMobile"><a href="">Return percentage</a></li>
                <li v-show="isListVisible[2] || !isMobile"><a href="">Disconnection policy</a></li>
                <li v-show="isListVisible[2] || !isMobile"><a href="">Authorized game</a></li>
            </ul>
            <ul>
                <li :class="{ 'clickable': isMobile }" class="li-title" @click="toggleList(3)"><strong>Real money</strong></li>
                <li v-show="isListVisible[3] || !isMobile"><a href="">Payments</a></li>
                <li v-show="isListVisible[3] || !isMobile"><a href="">Charges</a></li>
                <li v-show="isListVisible[3] || !isMobile"><a href="" @click.prevent="routeVerifyIndentity">Verify identity</a></li>
            </ul>
            <ul>
                <li class="li-title"><strong>Royal Flush Casino</strong></li>
                <li><a href=""><img src="/images/logo.svg" alt="" id="logo-footer"></a></li>
            </ul>
        </div>
        <div class="ps-5 pb-3">
            <p class="text-white">Copyright © 2025. Royal Flush Casino S.A.</p>
        </div>
    </footer>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const isListVisible = ref([false, false, false, false]);
const isMobile = ref(false);

function toggleList(index) {
    if (isMobile.value) {
        isListVisible.value = isListVisible.value.map((_, i) => i === index ? !isListVisible.value[i] : false);
    }
}

function checkMobile() {
    isMobile.value = window.innerWidth <= 768;
}

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});
function routeVerifyIndentity() {
    console.log("pushing")
    router.push({ path: '/verify-identity'})
}
</script>

<style scoped>
    #mainFooter ul>li {
        list-style: none;
        color: white;
        margin-top: 10px;
    }
    #mainFooter ul>li>a {
        color: white;
        text-decoration: none;
        font-weight: 100;
    }
    #mainFooter {
        background-color: #000000;
    }
    .li-title {
        margin-bottom: 20px;
    }
    .clickable {
        cursor: pointer;
    }
    @media (max-width: 768px) {
        #logo-footer {
            width: 100px;
        }
        #mainFooter {
            padding-left: 0;
        }
        .footer-content {
            flex-direction: column !important;
        }
        #mainFooter ul {
            margin-bottom: 20px;
        }
        #mainFooter ul>li.li-title {
            display: block;
        }
    }
</style>