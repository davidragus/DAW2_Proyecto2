<template>
	<main id="mainContent" class="container-fluid pr-0">
	  <Toast />
  
	  <!-- Main Banner -->
	  <div class="banner-background d-flex col-12">
		<div class="banner-filter"></div>
		<div class="container h-100 d-flex align-items-center">
		  <div class="row w-100 d-flex justify-content-center align-items-end">
			<div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-start">
			  <h1 class="banner-title">NEW ROULETTE GAME !</h1>
			  <p class="banner-text">You can now play roulette at Royal Flush Casino</p>
			  <Button class="banner-button" buttonColor="yellow" buttonStyle="filled">
				Click here to be a winner!!
			  </Button>
			</div>
		  </div>
		</div>
	  </div>
  
	  <!-- Featured Games -->
	  <div class="container games-container">
		<div class="row d-flex justify-content-center">
		  <div class="col-11 container-category-title d-flex justify-content-between align-items-center">
			<h5 class="category-title">FEATURED GAMES</h5>
			<div v-show="!isMobile" class="nav-arrows-container">
			  <button class="nav-arrow" disabled>
				<i class="fas fa-chevron-left"></i>
			  </button>
			  <button class="nav-arrow" disabled>
				<i class="fas fa-chevron-right"></i>
			  </button>
			</div>
		  </div>
		  <transition-group
			name="slide-fade"
			tag="div"
			class="row d-flex justify-content-around mt-3 games-row"
		  >
			<RouterLink
			  v-for="game in pagedGames"
			  :key="game.id"
			  class="col-2 game-card"
			  :to="{ name: 'auth.gamerooms', params: { route: game.route_path } }"
			>
			  <img :src="game.image" :alt="game.name" class="game-image" />
			  <p class="game-title">{{ game.name.toUpperCase() }}</p>
			  <div class="bottom-game-line"></div>
			</RouterLink>
			<div
			  v-for="n in emptySlots"
			  :key="`empty-${n}`"
			  class="col-2 game-card"
			></div>
		  </transition-group>
  
		  <div class="col-11 container-category-title d-flex justify-content-between align-items-center">
			<h5 class="category-title">NEW CASINO GAMES</h5>
			<div v-show="!isMobile" class="nav-arrows-container">
			  <button
				class="nav-arrow"
				:disabled="currentPageIndex === 0"
				@click="prevPage"
			  >
				<i class="fas fa-chevron-left"></i>
			  </button>
			  <button
				class="nav-arrow"
				:disabled="(currentPageIndex + 1) * gamesPerPage >= games.length"
				@click="nextPage"
			  >
				<i class="fas fa-chevron-right"></i>
			  </button>
			</div>
		  </div>
  
		  <transition-group
			name="slide-fade"
			tag="div"
			class="row d-flex justify-content-around mt-3 games-row"
		  >
			<RouterLink
			  v-for="game in pagedGames"
			  :key="game.id"
			  class="col-2 game-card"
			  :to="{ name: 'auth.gamerooms', params: { route: game.route_path } }"
			>
			  <img :src="game.image" :alt="game.name" class="game-image" />
			  <p class="game-title">{{ game.name.toUpperCase() }}</p>
			  <div class="bottom-game-line"></div>
			</RouterLink>
			<div
			  v-for="n in emptySlots"
			  :key="`empty-${n}`"
			  class="col-2 game-card"
			></div>
		  </transition-group>
		</div>
	  </div>
	</main>
  </template>
  
  <script setup>
  import { RouterLink } from 'vue-router';
  import Button from '../../components/Button.vue';
  import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import { useToast } from 'primevue';
  import useGames from "../../composables/games";
  
  const isMobile = ref(false);
  const route = useRoute();
  const router = useRouter();
  const toast = useToast();
  
  const { games, getAllGames } = useGames();
  
  // Paging
  const currentPageIndex = ref(0);
  const gamesPerPage = 5;
  
  const pagedGames = computed(() => {
	const start = currentPageIndex.value * gamesPerPage;
	return games.value.slice(start, start + gamesPerPage);
  });
  
  const emptySlots = computed(() => {
	return gamesPerPage - pagedGames.value.length;
  });
  
  const nextPage = () => {
	if ((currentPageIndex.value + 1) * gamesPerPage < games.value.length) {
	  currentPageIndex.value++;
	}
  };
  
  const prevPage = () => {
	if (currentPageIndex.value > 0) {
	  currentPageIndex.value--;
	}
  };
  
  // Initial Setup
  onMounted(() => {
	getAllGames();
	checkMobile();
	window.addEventListener('resize', checkMobile);
  });
  
  function checkMobile() {
	isMobile.value = window.innerWidth <= 768;
  }
  
  // Toast handling on route change
  watch(
	() => route.query.toast,
	(newValue) => {
	  router.push('/');
	  showSticky(newValue);
	}
  );
  
  const showSticky = (toastValue) => {
	if (toastValue === 'denied') {
	  toast.add({ severity: 'error', summary: 'Validation denied', detail: 'Your validation status is DENIED. Repeat verification in "My Account -> Verify Identity".', life: 5000 });
	} else if (toastValue === 'pending') {
	  toast.add({ severity: 'warn', summary: 'Validation pending', detail: 'Your validation status is PENDING.', life: 2000 });
	} else if (toastValue === 'error') {
	  toast.add({ severity: 'error', summary: 'Validation error', detail: 'There was an error with your validation status.', life: 2000 });
	}
  };
  </script>
  
  <style scoped>
  #mainContent {
	background-color: #2A2A2A;
  }
  
  .banner-background {
	height: 300px;
	background-image: url('/images/banner.jpg');
	background-size: cover;
	background-position: center;
	position: relative;
	margin-bottom: 75px;
  }
  
  .banner-filter {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.5);
	z-index: 1;
  }
  
  .banner-title {
	font-weight: bold;
	color: red;
	font-size: 48px;
	z-index: 2;
	width: 75%;
  }
  
  .banner-text {
	font-size: 24px;
	font-weight: bolder;
	color: white;
	z-index: 2;
	width: 60%;
  }
  
  .banner-button {
	padding: 10px 20px;
	font-size: 20px;
	cursor: pointer;
	z-index: 2;
	width: 60%;
  }
  
  .container-category-title {
	border-left: 5px solid red;
	margin-top: 50px;
	background: linear-gradient(90deg, #000000 16%, #2a2a2a 40%);
  }
  
  .category-title {
	margin: 0;
	color: red;
	font-size: 18px;
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
  
  .bottom-game-line {
	width: 100%;
	height: 1px;
	background: linear-gradient(90deg, transparent 0%, #ff0000 50%, transparent 100%);
  }
  
  .nav-arrows-container {
	display: flex;
	gap: 5px;
  }
  
  .nav-arrow {
	background: none;
	border: 1px solid #717171;
	background-color: #000000;
	border-radius: 50%;
	height: 30px;
	width: 30px;
	color: #717171;
	font-size: 16px;
  }
  
  .nav-arrow:disabled {
	background-color: #212121;
  }
  
  @media (max-width: 768px) {
	#mainContent {
	  padding-left: 0;
	}
	.banner-background {
	  height: 200px;
	}
	.banner-title {
	  font-size: 24px;
	  width: 100%;
	}
	.banner-text {
	  font-size: 16px;
	  width: 100%;
	}
	.banner-button {
	  font-size: 16px;
	  width: 100%;
	  text-align: center;
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
  