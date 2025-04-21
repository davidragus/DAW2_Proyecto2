import { ref, reactive } from 'vue'
import { authStore } from "../store/auth";
import { isEmpty } from 'lodash';

export default function useChat() {
	const messages = ref([]);

	const isLoading = ref(false);

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
		if (isLoading.value) return;
		if (isEmpty(message.message)) return;

		isLoading.value = true;
		axios.post('/api/sendMessage', { message });
		message.message = '';
		isLoading.value = false;
	}

	return {
		messages,
		message,
		getMessages,
		sendMessage
	}
}
