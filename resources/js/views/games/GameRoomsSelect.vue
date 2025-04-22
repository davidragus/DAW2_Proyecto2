<template>
	<main id="mainContent" class="container-fluid pr-0">
		<div class="container game-rooms-container">
			<div class="col-12 container-category-title d-flex justify-content-between align-items-center">
				<h5 class="category-title">GAME ROOMS</h5>
			</div>
			<div class="container d-flex mt-3 games-row">
				<RouterLink v-for="(data, index) in gameRooms" class="col-2 game-card"
					:to="{ name: `auth.${route.params.route}.gameroom`, params: { id: data.id } }">
					<img :src="`/images/${route.params.route}Game.webp`" alt="Bingo" class="game-image" />
					<p class="game-title">{{ data.name }}</p>
					<div class="bottom-game-line"></div>
				</RouterLink>
			</div>
		</div>
	</main>
</template>

<script setup>

import { ref, onBeforeMount,watch } from "vue";
import { useRoute } from "vue-router";
import useGameRooms from "@/composables/gameRooms";

const route = useRoute();
const { getGameRooms, gameRooms } = useGameRooms();

onBeforeMount(async () => {
	await getGameRooms(route.params.route);
});

watch(() => route.params.route, async (newRoute) => {
	await getGameRooms(newRoute);
});

</script>

<style scoped>
.container-category-title {
	border-left: 5px solid red;
	margin-top: 50px;
	background: rgb(0, 0, 0);
	background: linear-gradient(90deg, #000000 16%, #2a2a2a 40%);
}

.category-title {
	margin: 0;
	color: red;
	font-size: 18px;
	display: flex;
	align-items: center;
}

.game-card {
	height: 200px;
	width: 150px;
	text-align: center;
	margin: 10px;
}

.game-card:hover {
	transform: scale(1.1);
	transition: transform 0.1s;
}

.game-image {
	width: 100%;
	object-fit: cover;
	border-radius: 25%;
	border: 1px solid #000000;
}

.game-title {
	margin-top: 10px;
	font-size: 16px;
	color: white;
}

.games-row {
	flex-wrap: wrap;
}

@media (max-width: 768px) {
	#mainContent {
		padding-left: 0;
	}

	.games-row {
		flex-wrap: nowrap;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}

	.game-card {
		flex: 0 0 auto;
	}
}
</style>