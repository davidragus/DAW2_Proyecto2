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
					color: '#ffffff',
					confirmButtonColor: '#ff0000'
				})
					.then(() => {
						validation.value.status = response.data.data.status;
					})
			})
			.catch(error => {
				swal({
					icon: 'success',
					title: error.data.message,
					background: '#2A2A2A',
					color: '#ffffff',
					confirmButtonColor: '#ff0000'
				})
			})
			.finally(() => isLoading.value = false);
	};

	const declineValidation = async (id) => {
		axios.put('/api/validation/decline/' + id)
			.then(response => {
				swal({
					icon: 'success',
					title: response.data.message,
					background: '#2A2A2A',
					color: '#ffffff',
					confirmButtonColor: '#ff0000'
				})
					.then(() => {
						validation.value.status = response.data.data.status;
					})
			})
			.catch(error => {
				swal({
					icon: 'success',
					title: error.data.message,
					background: '#2A2A2A',
					color: '#ffffff',
					confirmButtonColor: '#ff0000'
				})
			})
			.finally(() => isLoading.value = false);
	};
	const deleteValidation = async (id, index) => {
		swal({
			title: 'Are you sure?',
			text: 'You won\'t be able to revert this action!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it!',
			confirmButtonColor: '#ef4444',
			timer: 20000,
			timerProgressBar: true,
			reverseButtons: true
		})
			.then(result => {
				if (result.isConfirmed) {
					axios.delete('/api/validations/' + id)
						.then(response => {
							validations.value.data.splice(index, 1);

							swal({
								icon: 'success',
								title: 'User deleted successfully',
								background: '#2A2A2A',
								color: '#ffffff',
								confirmButtonColor: '#ff0000'
							})
						})
						.catch(error => {
							swal({
								icon: 'error',
								title: 'Something went wrong',
								background: '#2A2A2A',
								color: '#ffffff',
								confirmButtonColor: '#ff0000'
							})
						})
				}
			})
	}

	return {
		validation,
		validations,
		getValidations,
		getValidation,
		approveValidation,
		declineValidation,
		isLoading,
		deleteValidation
	};
}