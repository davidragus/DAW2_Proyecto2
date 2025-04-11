<template>
	<div class="admin-dashboard">
		<div class="welcome-card animated-card">
			<h1 class="animated-title">🎉 Bienvenido al Panel de Administración</h1>
			<p class="welcome-message">{{ welcomeMessage }}</p>
		</div>
		<div class="info-section">
			<RouterLink to="/admin/users" class="info-card">
				<h2>Usuarios</h2>
				<p>Actualmente hay <span class="highlight">{{ usersCount }}</span> usuarios registrados.</p>
			</RouterLink>
			<RouterLink to="/admin/achievements" class="info-card">
				<h2>Logros</h2>
				<p>Se han creado <span class="highlight">{{ achievementsCount }}</span> logros en la plataforma.</p>
			</RouterLink>
			<RouterLink to="/admin/games" class="info-card">
				<h2>Juegos</h2>
				<p>Disponibles <span class="highlight">{{ gamesCount }}</span> juegos para los usuarios.</p>
			</RouterLink>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import useUsers from "../../composables/users";
import useAchievements from "../../composables/achievements";
import useGames from "../../composables/games";
import { RouterLink } from 'vue-router';

const { achievements, getAllAchievements } = useAchievements();
const { users, getUsers, deleteUser, resetUserDB } = useUsers();
const { games, getAllGames } = useGames();

const usersCount = ref(0);
const achievementsCount = ref(0);
const gamesCount = ref(0);

// Mensaje dinámico
const welcomeMessage = ref('');
const messages = [
	'Gestiona usuarios, logros y roles desde aquí.',
	'Administra el contenido de Royal Flush Casino.',
	'¡Haz que la experiencia sea inolvidable!'
];

let messageIndex = 0;

function updateWelcomeMessage() {
	welcomeMessage.value = messages[messageIndex];
	messageIndex = (messageIndex + 1) % messages.length;
}

watch(users, (newUsers) => {
	usersCount.value = newUsers.data.length;
});

watch(achievements, (newAchievements) => {
	achievementsCount.value = newAchievements.data.length;
})

watch(games, (newGames) => {
	gamesCount.value = newGames.data.length;
});

onMounted(() => {
	getAllAchievements();
	getUsers();
	getAllGames();
	updateWelcomeMessage();
	setInterval(updateWelcomeMessage, 5000);
});

</script>

<style scoped>
.admin-dashboard {
	display: flex;
	flex-direction: column;
	align-items: center;
	padding: 2rem;
	background: #2A2A2A;
	color: #FFFFFF;
	min-height: 100vh;
}

.welcome-card {
	text-align: center;
	background: linear-gradient(135deg, #500000, #2A2A2A, #500000);
	padding: 2rem;
	border-radius: 10px;
	box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
	color: #FFFFFF;
	margin-bottom: 2rem;
	animation: fadeIn 1s ease-in-out;
}

.animated-card {
	animation: moveUpDown 2s infinite ease-in-out;
}

@keyframes moveUpDown {
	0% {
		transform: translateY(0);
	}

	50% {
		transform: translateY(-20px);
	}

	100% {
		transform: translateY(0);
	}
}

.animated-title {
	font-size: 2.5rem;
	margin-bottom: 1rem;
}

.welcome-message {
	font-size: 1.2rem;
	font-style: italic;
	animation: fadeIn 1s ease-in-out;
}

.info-section {
	display: flex;
	gap: 1.5rem;
	justify-content: center;
	flex-wrap: wrap;
	width: 100%;
	max-width: 1200px;
}

.info-card {
	background: #3A3A3A;
	padding: 1.5rem;
	border-radius: 10px;
	text-align: center;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
	flex: 1;
	min-width: 250px;
	transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.info-card:hover {
	transform: translateY(-5px);
	box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.info-card h2 {
	font-size: 1.5rem;
	margin-bottom: 0.5rem;
	color: #FFD700;
}

.info-card p {
	font-size: 1rem;
	color: #B0B0B0;
}

.highlight {
	color: #FFD700;
	font-weight: bold;
}

@keyframes fadeIn {
	from {
		opacity: 0;
		transform: translateY(-10px);
	}

	to {
		opacity: 1;
		transform: translateY(0);
	}
}
</style>