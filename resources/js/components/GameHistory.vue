<template>
	<div id="mainContent" class="d-flex">
		<aside class="sidebar">
			<ul>
				<li>
					<RouterLink to="/my-account" class="router-link-active">ACCOUNT DETAILS</RouterLink>
				</li>
				<li><a href="#">BALANCE HISTORY</a></li>
				<li class="active"><a href="#">GAME HISTORY</a></li>
				<li><a href="#">ACHIEVEMENTS</a></li>
				<li><a><router-link to="/verify-identity" class="dropdown-item">VERIFY IDENTITY</router-link></a></li>
			</ul>
		</aside>
		<div class="container py-3">
			<DataView :value="gameHistory" paginator :rows="6">
				<template #header>
					<div class="row d-flex justify-content-between">
						<h3 class="col m-0">Game history</h3>
					</div>
				</template>
				<template #list="{ items }">
					<div class="history-row row d-flex align-items-center m-0 px-3 py-2" v-for="(data, index) in items"
						:key="index">
						<div class="col-1">
							<img class="result-icon"
								:src="`/images/${data.result == 'WIN' ? 'WinIcon' : 'LoseIcon'}.webp`" alt="">
						</div>
						<div class="col-7">
							<h4 class="m-0">{{ data.game_room_name }}</h4>
							<span class="history-date">{{ data.created_at }}</span>
						</div>
						<div class="col-4">
							<div class="row">
								<div class="col-6 d-flex flex-column align-items-center">
									<p class="m-0">Bet amount</p>
									<span class="d-flex align-items-center">{{ data.bet_amount }} <img
											class="chips-icon ms-1" src="/images/chips2.png" alt="Icon of chips">
									</span>
								</div>
								<div class="col-6 d-flex flex-column align-items-center">
									<p class="m-0">Winnings amount</p>
									<span class="d-flex align-items-center">{{ data.win_amount }} <img
											class="chips-icon ms-1" src="/images/chips2.png" alt="Icon of chips">
									</span>
								</div>
							</div>
						</div>
					</div>
				</template>
				<template #empty>
					<div class="row m-0 p-2">
						<p>No games have been played.</p>
					</div>
				</template>
			</DataView>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { RouterLink } from 'vue-router';
import { authStore } from '../store/auth';
import DataView from 'primevue/dataview';
// import DatePicker from 'primevue/datepicker';
import useUsers from '@/composables/users';

const { gameHistory, getGameHistory } = useUsers();
const dates = ref(null);

onMounted(() => {
	getGameHistory(authStore().user.id);
	document.getElementById('mainContent').classList.add('ml-4');
	if (window.innerWidth <= 768) {
		document.getElementById('mainContent').style.paddingLeft = '0';
	} else {
		document.getElementById('mainContent').style.paddingLeft = '230px';
	}
});

onBeforeUnmount(() => {
	document.getElementById('mainContent').classList.remove('ml-4');
});
</script>

<style scoped>
#mainContent {
	display: flex;
	padding: 0 20px 0 20px;
	background-color: #2A2A2A;
}

.active {
	text-decoration: underline;
	color: #ffffff;
	font-weight: bold;
}

.sidebar {
	width: 200px;
	margin-right: 20px;
	border-right: 1px solid #3B3B3B;
	padding: 20px 0 20px 0;
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

.chips-icon {
	width: 16px;
	height: 16px;
	border-radius: 50%;
	object-fit: cover;
}

.history-row {
	border-bottom: 1px solid #313131;
}

.history-date {
	color: #8a8a8a;
}

.result-icon {
	width: 86px;
	height: 86px;
	border-radius: 50%;
	object-fit: cover;
}
</style>