<template>
	<div id="mainContent" class="d-flex ms-4" :class="isMobile ? 'flex-column mr-4' : ''">
		<MyAccountSidebar :isMobile="isMobile" />
		<div class="container py-3">
			<DataView :value="balanceHistory" paginator :rows="20">
				<template #header>
					<div class="row d-flex justify-content-between">
						<h3 class="col m-0">Balance history</h3>
					</div>
				</template>
				<template #list="{ items }">
					<div class="history-row row d-flex align-items-center m-0 px-3 py-2" v-for="(data, index) in items"
						:key="index">
						<div :class="data.name == 'Deposit' || data.name == 'Withdraw' ? 'col-6 sm:col-8 md:col-10' : 'col-11'">
							<h4 class="m-0">{{ data.name }}</h4>
							<span class="history-date">{{ data.created_at }}</span>
						</div>
						<div v-if="data.real_money" class="col-3 sm:col-2 md:col-1 d-flex justify-content-end align-items-center">
							<span :class="{ 'minus': data.type == 'PLUS', 'plus': data.type == 'MINUS' }"> {{ data.type
								==
								'MINUS' ? '+' : '-' }}{{ data.real_money }} </span> €
						</div>
						<div class="col-3 sm:col-2 md:col-1 d-flex justify-content-end align-items-center">
							<span :class="{ 'plus': data.type == 'PLUS', 'minus': data.type == 'MINUS' }"> {{ data.type
								==
								'PLUS' ? '+' : '-' }}{{ data.amount }} </span> <img class="chips-icon ms-2"
								src="/images/chips2.png" alt="Icon of chips">
						</div>
					</div>
				</template>
				<template #empty>
					<div class="row m-0 p-2">
						<p>No transactions have been found.</p>
					</div>
				</template>
			</DataView>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { authStore } from '../store/auth';
import DataView from 'primevue/dataview';
import MyAccountSidebar from './MyAccountSidebar.vue';
import useUsers from '@/composables/users';

const { balanceHistory, getBalanceHistory } = useUsers();

const isMobile = ref(false);

const checkMobile = () => {
	isMobile.value = window.innerWidth <= 768;
	if (!isMobile.value) {
		document.getElementById('mainContent').style.paddingLeft = '230px';
	}
};

onMounted(() => {
	checkMobile();
	getBalanceHistory(authStore().user.id);
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

.chips-icon {
	width: 16px;
	height: 16px;
	border-radius: 50%;
	object-fit: cover;
}

.history-row {
	border-bottom: 1px solid #313131;
}

@media (max-width: 768px) {
	.history-row {
		justify-content: center;
	}
}

.history-date {
	color: #8a8a8a;
}

.plus {
	color: #00ff00;
}

.minus {
	color: #ff0000;
}
</style>