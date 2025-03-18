import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useValidations() {
	const validations = ref([]);
	const validation = ref({});

	const router = useRouter();
	const isLoading = ref(false);
	const swal = inject('$swal');

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

	const getValidation = async (id) => {
		axios.get('/api/validation/' + id)
			.then(response => {
				validation.value = response.data.data;
			})
	}

	const approveValidation = async (id) => {
		axios.put('/api/validation/approve/' + id)
			.then(response => {
				swal({
					icon: 'success',
					title: response.data.message,
					background: '#2A2A2A',
					color: '#ffffff'
				})
			})
			.catch(error => {
				swal({
					icon: 'success',
					title: error.data.message,
					background: '#2A2A2A',
					color: '#ffffff'
				})
			});
	};

	const declineValidation = async (id) => {
		axios.put('/api/validation/decline/' + id)
			.then(response => {
				swal({
					icon: 'success',
					title: response.data.message,
					background: '#2A2A2A',
					color: '#ffffff'
				})
			})
			.catch(error => {
				swal({
					icon: 'success',
					title: error.data.message,
					background: '#2A2A2A',
					color: '#ffffff'
				})
			});
	};

	return {
		validation,
		validations,
		getValidations,
		getValidation,
		approveValidation,
		declineValidation,
		isLoading
	};
}