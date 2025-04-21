<template>
	<nav v-if="!isMobile" id="sideBar" class="d-flex flex-column justify-content-between align-items-center">
		<ul class="mt-5 w-75">
			<li>
				<RouterLink :to="{ name: 'home' }" class="d-block container-fluid"><i class="pi pi-home"
						style="font-size: 1.5rem"></i><span :class="{ 'd-none': !visible }">HOME</span></RouterLink>
			</li>
			<hr>
			<li v-for="game in games" :key="game.name">
				<RouterLink v-if="game.active" :to="`/games/${game.route_path}`"
					class="d-flex container-fluid align-items-center">
					<img :src="game.icon" alt="" class="icon" />
					<span class="icon-text" :class="{ 'd-none': !visible }">{{ game.name }}</span>
				</RouterLink>
			</li>
		</ul>
		<div class="w-100">
			<LocaleSwitcher :isMobile="isMobile"></LocaleSwitcher>
			<button v-if="authStore().user?.name" class="bottom-buton live-chat" :class="{ 'd-none': !visible }"
				@click="openDrawer">
				<img src="/images/chat-bot_dark.png" alt="england" class="icon">
				<p class="color-white">LIVE CHAT</p>
			</button>
			<div class="appendToClass"></div>
			<Drawer @hide="closeDrawer" v-if="showDrawer" v-model:visible="chatVisible" position="right"
				header="Live Chat" class="w-25" :pt="{
					root: (options) => ({
						style: {
							'--p-drawer-background': '#212121',
							'--p-drawer-color': '#fff',
							'--p-drawer-border-color': '#212121',
						}
					}),
				}" appendTo="self">
				<LiveChat>
				</LiveChat>
			</Drawer>
		</div>
		<!-- <div class="overlay" id="overlaySideBar"></div> -->
	</nav>
	<nav v-else>
		<Drawer @hide="closeSideBar" v-if="showSideBar" v-model:visible="sideBarVisible" class="w-50" :pt="{
			root: (options) => ({
				style: {
					'--p-drawer-background': '#212121',
					'--p-drawer-color': '#fff',
					'--p-drawer-border-color': '#212121',
					'--p-drawer-content-padding': '0px',
				}
			}),
		}" appendTo="self">
			<div class="d-flex flex-column justify-content-between align-items-center h-100">
				<ul class="mt-5 w-100 px-3 pb-3">
					<li>
						<RouterLink :to="{ name: 'home' }" class="d-block container-fluid"><i class="pi pi-home"
								style="font-size: 1.5rem"></i><span class="ms-2">HOME</span>
						</RouterLink>
					</li>
					<hr>
					<li v-for="game in games" :key="game.name">
						<RouterLink v-if="game.active" :to="`/games/${game.route_path}`"
							class="d-flex container-fluid align-items-center">
							<img :src="game.icon" alt="" class="icon" />
							<span class="ms-2 icon-text">{{ game.name }}</span>
						</RouterLink>
					</li>
				</ul>
				<div class="w-100">
					<LocaleSwitcher></LocaleSwitcher>
					<button v-if="authStore().user?.name" class="bottom-buton live-chat" @click="openDrawer">
						<img src="/images/chat-bot_dark.png" alt="england" class="icon">
						<p class="color-white">LIVE CHAT</p>
					</button>
				</div>
			</div>
		</Drawer>
		<Drawer @hide="closeDrawer" @show="closeSideBar" v-if="showDrawer" v-model:visible="chatVisible"
			position="right" header="Live Chat" class="w-75" :pt="{
				root: (options) => ({
					style: {
						'--p-drawer-background': '#212121',
						'--p-drawer-color': '#fff',
						'--p-drawer-border-color': '#212121',
					}
				}),
			}" appendTo="self">
			<LiveChat>
			</LiveChat>
		</Drawer>
	</nav>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import LocaleSwitcher from './LocaleSwitcher.vue';
import iRoulette from './iRoulette.vue';
import iBlackJack from './iBlackJack.vue';
import iBingo from './icons/iBingo.vue';
import LiveChat from './LiveChat.vue';
import { authStore } from "../store/auth";
import Drawer from 'primevue/drawer';
import Button from 'primevue/button';
import useGames from "../composables/games";
import { get } from 'lodash';
import { RouterLink } from 'vue-router';

const { games, getAllGames } = useGames();

onMounted(() => {
	getAllGames();
});

const { user } = authStore();

const props = defineProps({
	visible: Boolean,
	isMobile: Boolean,
	showBar: Boolean
});
const emit = defineEmits(['toggle-sidebar-mobile']);

const chatVisible = ref(false);
const showDrawer = ref(false);

const sideBarVisible = ref(false);
const showSideBar = ref(false);

watch(() => props.showBar, (newVal) => {
	if (props.isMobile && newVal) {
		openSideBar()
	} else if (props.isMobile && !newVal) {
		closeSideBar()
	}
})

// Estado para el modal de idiomas
const showModal = ref(false);
const hoverBingo = ref(false);

const languages = ref([
	"English", "Español", "Français", "Deutsch",
	"Italiano", "Português", "Nederlands", "中文",
	"日本語", "한국어", "Русский", "العربية"
]);

const openSideBar = () => {
	sideBarVisible.value = true;
	showSideBar.value = true;
}

const closeSideBar = () => {
	if (props.showBar) {
		emit('toggle-sidebar-mobile');
	}
	sideBarVisible.value = false;
	setTimeout(() => {
		showSideBar.value = false;
	}, 300);
}

const openDrawer = () => {
	chatVisible.value = true;
	showDrawer.value = true;
	if (sideBarVisible.value) {
		emit('toggle-sidebar-mobile');
	}
}

// Se debe de usar un temporizador para cambiar el valor de showDrawer. De lo contrario, dará un error al entrar al panel de admin.
const closeDrawer = () => {
	chatVisible.value = false;
	setTimeout(() => {
		showDrawer.value = false;
	}, 300);
}

function optionHover(name) {
	switch (name) {
		case 'bingo':
			hoverBingo.value = true;
			break;
		default:
			break;
	}
};
function optionHoverLeave(name) {
	switch (name) {
		case 'bingo':
			hoverBingo.value = false;
			break;
		default:
			break;
	}
};

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

li:hover .icon-text,
li:hover .icon {
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

.overlay {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.5);
	z-index: 1000;
}
</style>