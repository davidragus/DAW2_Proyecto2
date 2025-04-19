<template>
	<div class="admin-dashboard">
		<div class="welcome-card animated-card">
			<h1 class="animated-title">🎉 Welcome to the Admin Dashboard</h1>
			<p class="welcome-message">{{ welcomeMessage }}</p>
		</div>

		<div class="info-section">
			<RouterLink to="/admin/users" class="info-card">
				<h2>Users</h2>
				<p>There are currently <span class="highlight">{{ usersCount }}</span> registered users.</p>
			</RouterLink>

			<RouterLink to="/admin/achievements" class="info-card">
				<h2>Achievements</h2>
				<p><span class="highlight">{{ achievementsCount }}</span> achievements have been created.</p>
			</RouterLink>

			<RouterLink to="/admin/games" class="info-card">
				<h2>Games</h2>
				<p><span class="highlight">{{ gamesCount }}</span> games are available for users.</p>
			</RouterLink>

			<RouterLink to="/admin/validations" class="info-card">
				<h2>Validations</h2>
				<p><span class="highlight">{{ validationsCount }}</span> validations recorded.</p>
			</RouterLink>

			<RouterLink to="/admin/roles" class="info-card">
				<h2>Roles</h2>
				<p><span class="highlight">{{ rolesCount }}</span> roles defined in the system.</p>
			</RouterLink>

			<RouterLink to="/admin/permissions" class="info-card">
				<h2>Permissions</h2>
				<p><span class="highlight">{{ permissionsCount }}</span> permissions configured.</p>
			</RouterLink>

			<RouterLink to="/admin/game-rooms" class="info-card">
				<h2>Game Rooms</h2>
				<p><span class="highlight">{{ gameroomsCount }}</span> game rooms active.</p>
			</RouterLink>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { RouterLink } from 'vue-router';

import useUsers from "../../composables/users";
import useAchievements from "../../composables/achievements";
import useGames from "../../composables/games";
import useValidations from "../../composables/validations";
import useRoles from "../../composables/roles";
import usePermissions from "../../composables/permissions";
import useGamerooms from "../../composables/gamerooms";
import { get } from 'lodash';

// 👇 tus datos originales
const { users, getUsers } = useUsers();
const { achievements, getAllAchievements } = useAchievements();
const { games, getAllGames } = useGames();

// 👇 nuevos datos añadidos
const { validations, getValidations } = useValidations();
const { roles, getRoles } = useRoles();
const { permissions, getPermissions } = usePermissions();
const { gameRooms, getAllGameRooms } = useGamerooms();

// 🔢 contadores
const usersCount = ref(0);
const achievementsCount = ref(0);
const gamesCount = ref(0);
const validationsCount = ref(0);
const rolesCount = ref(0);
const permissionsCount = ref(0);
const gameroomsCount = ref(0);

// 💬 mensaje de bienvenida dinámico
const welcomeMessage = ref('');
const messages = [
	'Manage users, achievements and roles from here.',
	'Administer Royal Flush Casino content.',
	'Make the experience unforgettable!'
];
let messageIndex = 0;
function updateWelcomeMessage() {
	welcomeMessage.value = messages[messageIndex];
	messageIndex = (messageIndex + 1) % messages.length;
}

// ✅ watchers para contar
watch(users, (val) => {
	console.log(val);
	usersCount.value = val?.data?.length || 0;
});

watch(achievements, (val) => {
	achievementsCount.value = val?.data?.length || 0;
});

watch(games, (val) => {
	gamesCount.value = val?.length || 0;
});

watch(validations, (val) => {
	validationsCount.value = val?.data?.length || 0;
});

watch(roles, (val) => {
	rolesCount.value = val?.data?.length || 0;
});

watch(permissions, (val) => {
	permissionsCount.value = val?.data?.length || 0;
});

watch(gameRooms, (val) => {
	gameroomsCount.value = val?.length || 0;
});

// ⏱️ onMounted
onMounted(() => {
	getAllData();
	updateWelcomeMessage();
	setInterval(updateWelcomeMessage, 5000);
});

// const getValidationsPending = async () => {
// 	const validationsPending = ref(validations.value.data.filter((validation) => validation.status === 'PENDING'));
// 	return validationsPending.value.length;
// };

const getAllData = async () => {
	await getUsers();
	await getAllAchievements();
	await getAllGames();
	await getValidations();
	await getRoles();
	await getPermissions();
	await getAllGameRooms();
};
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
	0% { transform: translateY(0); }
	50% { transform: translateY(-20px); }
	100% { transform: translateY(0); }
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
