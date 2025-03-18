import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useValidations() {
	const validations = ref([]);

	const router = useRouter();

	const getValidations = async (
		page = 1,
		// search_id = '',
		// search_title = '',
		// search_global = '',
		order_column = 'created_at',
		order_direction = 'desc'
	) => {
		axios.get('/api/validations?page=' + page +
			// '&search_id=' + search_id +
			// '&search_title=' + search_title +
			// '&search_global=' + search_global +
			'&order_column=' + order_column +
			'&order_direction=' + order_direction)
			.then(response => {
				validations.value = response.data;
			})
	}

	return {
		validations,
		getValidations
	};
}