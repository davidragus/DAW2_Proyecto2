<template>
	<div class="h-100">
		<div class="chat-container d-flex flex-column-reverse overflow-scroll mb-3">
			<div class="message-container d-flex mb-3" v-for="message in messages" :key="message.id">
				<Avatar v-if="message.user.avatar" :image="message.user.avatar" size="xlarge" shape="circle" />
				<Avatar v-if="!message.user.avatar" :label="message.user.username.substring(0, 1)" size="large"
					shape="circle" />
				<div class="d-flex flex-column col-10 py-0">
					<div>
						<span>{{ message.user.id == user.id ? 'You' : message.user.username }}</span> <span
							class="fw-light message-date">{{
								message.created_at }}</span>
					</div>
					<p>{{ message.message }}</p>
				</div>
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
import useAuth from '@/composables/auth';


const { message, messages, getMessages, sendMessage } = useChat();
const { user } = useAuth();

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

.message-date {
	font-size: 0.8rem;
	color: #999;
}
</style>