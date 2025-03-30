import { ref, reactive } from 'vue'
import { authStore } from "../store/auth";

export default function useChat() {
	const messages = ref([]);

	const { user } = authStore();
	const message = reactive({
		user: user.id,
		message: ''
	});

	const getMessages = async () => {
		axios.get('/api/getMessages')
			.then(response => {
				messages.value = response.data.data;
			});
	}

	const sendMessage = async () => {
		axios.post('/api/sendMessage', { message })
			.then(response => {
				messages.value.unshift(response.data.data);
			});
		message.message = '';
	}

	return {
		messages,
		message,
		getMessages,
		sendMessage
	}
}
