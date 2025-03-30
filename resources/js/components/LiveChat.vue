<template>
	<div class="h-100">
		<div class="card chat-container d-flex flex-column-reverse">
			<div class="message-container" v-for="message in messages" :key="message.id">
				<span>{{ message.user.username }} - {{ message.created_at }}</span>
				<p>{{ message.message }}</p>
			</div>
		</div>
		<div class="form-container">
			<form @submit.prevent="sendMessage" class="d-flex">
				<input v-model="message.message" class="form-control me-3" type="text" name="" id="">
				<button>SEND</button>
			</form>
		</div>
	</div>
</template>

<script setup>

import { onMounted } from 'vue';
import Echo from "laravel-echo";
import useChat from '@/composables/chat';

const { message, messages, getMessages, sendMessage } = useChat();

window.Echo = new Echo({
	broadcaster: 'pusher',
	key: import.meta.env.VITE_PUSHER_APP_KEY,
	cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
	forceTLS: true,
	authEndpoint: "/broadcasting/auth",
});

onMounted(() => {
	window.Echo.join('chat')
		.here(() => {
			getMessages();
		})
		.listen('.MessageSent', (event) => {
			console.log('mensaje recibido');
			messages.value.unshift(event.message);
		});
});

</script>

<style scoped>
.chat-container {
	height: 90%;
}

.form-container {
	height: 8%;
}
</style>