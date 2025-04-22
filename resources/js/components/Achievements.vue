<template>
	<div id="mainContent" class="d-flex ms-4" :class="isMobile ? 'flex-column mr-4' : ''">
		<MyAccountSidebar :isMobile="isMobile" />
		<div class="container">
			<div class="d-flex flex-wrap gap-3">
				<!-- Logros obtenidos -->
				<div v-for="achievement in userAchievements" :key="achievement.id" class="achievement-card text-center">
					<img :src="achievement.image" class="achievement-img" :alt="achievement.name"
						@click="openAchievementDialog(achievement)" />
					<h6 class="text-white mt-2">{{ achievement.name }}</h6>
				</div>

				<!-- Logros no obtenidos -->
				<div v-for="achievement in achievements" :key="achievement.id" class="achievement-card text-center">
					<img :src="achievement.image" class="achievement-img" style="filter: grayscale(100%);"
						:alt="achievement.name" @click="openAchievementDialog(achievement)" />
					<h6 class="text-white mt-2">{{ achievement.name }}</h6>
				</div>
			</div>
		</div>


		<!-- Modal para mostrar detalles del logro -->
		<div v-if="selectedAchievement" class="modal-overlay" @click.self="closeAchievementDialog">
			<div class="modal-content">
				<div class="d-flex justify-content-center">
					<!-- Si el selectedAchivement no tiene obtained date la imagen en blanco y neggro -->
					<img :src="selectedAchievement.image" class="achievement-modal-img"
						:style="!selectedAchievement.obtained_date ? 'filter: grayscale(100%);' : ''"
						:alt="selectedAchievement.name + ' achievement image'" />
				</div>
				<h2 class="text-white">{{ selectedAchievement.name }}</h2>
				<p class="text-white">{{ selectedAchievement.description }}</p>
				<!-- <p class="text-white">Achievement Type: {{ selectedAchievement.achievement_type }}</p> -->
				<p class="text-white">Reward: {{ selectedAchievement.reward_amount }} chips</p>
				<p v-if="selectedAchievement.obtained_date" class="text-white">Obtained on: {{
					selectedAchievement.obtained_date }}</p>
				<button class="btn button-red mt-3" @click="closeAchievementDialog">Close</button>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { RouterLink } from 'vue-router';
import axios from 'axios';
import { authStore } from '../store/auth';
import MyAccountSidebar from './MyAccountSidebar.vue';

// Estado para logros y logro seleccionado
const achievements = ref([]);
const userAchievements = ref([]);
const selectedAchievement = ref(null);

const isMobile = ref(false);

const checkMobile = () => {
	isMobile.value = window.innerWidth <= 768;
	if (!isMobile.value) {
		document.getElementById('mainContent').style.paddingLeft = '230px';
	}
};

// Función para obtener logros desde la API
const fetchAchievements = async () => {
	try {
		const response = await axios.get('/api/achievements');
		// Excluimos los logros que el usuario ya tiene
		response.data.data = response.data.data.filter(achievement => !userAchievements.value.some(userAchievement => userAchievement.id === achievement.id));
		achievements.value = response.data.data;
		// console.log('Achievements:', achievements.value);
	} catch (error) {
		console.error('Error fetching achievements:', error);
	}
};

const fetchUserAchievements = async (userId) => {
	try {
		const response = await axios.get(`/api/users/${userId}/achievements`);
		// console.log('User achievements response:', response.data.achievements);
		userAchievements.value = response.data.achievements;
		// Formateamos la fecha de obtención del logro
		userAchievements.value.forEach(userAchievements => {
			userAchievements.obtained_date = new Date(userAchievements.obtained_date).toLocaleDateString();
		});
		// console.log('User achievements:', userAchievements.value);
	} catch (error) {
		console.error('Error fetching user achievements:', error);
	}
};

// Función para abrir el modal con los detalles del logro
const openAchievementDialog = (achievement) => {
	selectedAchievement.value = achievement;
	// console.log("Achievement opened:", selectedAchievement.value);
};

// Función para cerrar el modal
const closeAchievementDialog = () => {
	selectedAchievement.value = null;
};

// Configuración de estilos al montar y desmontar el componente
onMounted(() => {
	checkMobile();
	fetchUserAchievements(authStore().user.id);
	fetchAchievements();
	window.addEventListener('resize', checkMobile);
});

onBeforeUnmount(() => {
	document.getElementById('mainContent').classList.remove('ml-4');
});
</script>

<style scoped>
#mainContent {
	display: flex;
	padding: 0 0;
	background-color: #2A2A2A;
}

.achievement-img {
	width: 150px;
	height: 150px;
	object-fit: cover;
	border-radius: 50%;
	cursor: pointer;
	transition: transform 0.2s ease;
}

.achievement-img:hover {
	transform: scale(1.1);
}

.modal-overlay {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.8);
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 1000;
}

.modal-content {
	background-color: #2A2A2A;
	padding: 20px;
	border-radius: 10px;
	text-align: center;
	max-width: 500px;
	width: 90%;
}

.achievement-modal-img {
	width: 200px;
	height: 200px;
	object-fit: cover;
	border-radius: 50%;
	margin-bottom: 20px;
}

.text-white {
	color: #ffffff;
}

.button-red {
	background-color: #ff0000;
	color: white;
	border: none;
	padding: 10px;
	cursor: pointer;
	width: 100%;
	text-align: center;
}

.container {
	padding: 20px 0 20px 0;
}
</style>