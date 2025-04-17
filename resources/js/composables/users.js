import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useUsers() {
	const users = ref([])
	const user = ref({
		name: ''
	})
	const gameHistory = ref([]);

	const router = useRouter()
	const validationErrors = ref({})
	const isLoading = ref(false)
	const swal = inject('$swal')

	const getUsers = async (
		page = 1,
		search_id = '',
		search_title = '',
		search_global = '',
		order_column = 'id',
		order_direction = 'asc'
	) => {
		axios.get('/api/users?page=' + page +
			'&search_id=' + search_id +
			'&search_title=' + search_title +
			'&search_global=' + search_global +
			'&order_column=' + order_column +
			'&order_direction=' + order_direction)
			.then(response => {
				users.value = response.data;
			})
	}

	const getUsersWithTasks = async () => {
		axios.get('/api/userswithtasks')
			.then(response => {
				users.value = response.data;
			})
	}

	const getUser = async (id) => {
		axios.get('/api/users/' + id)
			.then(response => {
				user.value = response.data.data;
			})
	}

	const createUserDB = async (id) => {
		return axios.put('/api/users/db/create/' + id);
	}

	const deleteUserDB = async (id) => {
		return axios.put('/api/users/db/delete/' + id);
	}

	const changeUserPasswordDB = async (id) => {
		return axios.put('/api/users/db/password/' + id);
	}

	const createUserProceduredDB = async (id) => {
		return axios.put('/api/users/db/procedure/' + id);
	}
	const storeUser = async (user) => {
		if (isLoading.value) return;

		isLoading.value = true
		validationErrors.value = {}

		let serializedUser = new FormData()
		for (let item in user) {
			if (user.hasOwnProperty(item)) {
				if (Array.isArray(user[item]) && user[item].length > 0) {
					user[item].forEach((value, index) => {
						serializedUser.append(`${item}[${index}]`, value);
					});
				} else if (user[item]) {
					serializedUser.append(item, user[item])
				}
			}
		}

		axios.post('/api/users', serializedUser,
			{
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			}
		)
			.then(response => {
				router.push({ name: 'users.index' })
				swal({
					icon: 'success',
					title: 'User saved successfully'
				})
			})
			.catch(error => {
				if (error.response?.data) {
					validationErrors.value = error.response.data.errors
				}
			})
			.finally(() => isLoading.value = false)
	}

	const updateUser = async (user) => {
		if (isLoading.value) return;

		isLoading.value = true
		validationErrors.value = {}

		let serializedUser = new FormData()
		for (let item in user) {
			if (user.hasOwnProperty(item)) {
				if (Array.isArray(user[item]) && user[item].length > 0) {
					user[item].forEach((value, index) => {
						serializedUser.append(`${item}[${index}]`, value);
					});
				} else if (user[item]) {
					serializedUser.append(item, user[item])
				}
			}
		}

		axios.post('/api/users/' + user.id, serializedUser,
			{
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			})
			.then(response => {
				swal({
					icon: 'success',
					title: 'User updated successfully'
				})
			})
			.catch(error => {
				if (error.response?.data) {
					validationErrors.value = error.response.data.errors
				}
			})
			.finally(() => isLoading.value = false)
	}

	const deleteUser = async (id, index) => {
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
					axios.delete('/api/users/' + id)
						.then(response => {
							users.value.data.splice(index, 1);

							swal({
								icon: 'success',
								title: 'User deleted successfully'
							})
						})
						.catch(error => {
							swal({
								icon: 'error',
								title: 'Something went wrong'
							})
						})
				}
			})
	}
	const submitVerifyIdentity = async (id, validationImages) => {
		const formData = new FormData();

		// Agregar las imágenes al FormData
		validationImages.forEach((file, index) => {
			if (file) {
				formData.append(`validationImages[${index}]`, file);
			}
		});

		try {
			// Enviar la solicitud al backend
			const response = await axios.post(`/api/user/${id}/submit-validation`, formData, {
				headers: {
					"Content-Type": "multipart/form-data",
				},
			});

			// Mostrar mensaje de éxito
			swal({
				icon: "success",
				title: "Validation submitted successfully!",
				showConfirmButton: false,
				timer: 1500,
				background: "#2A2A2A",
				color: "#ffffff",
			});

			// Actualizar los datos del usuario
			await getUser(id);

			return response.data; // Devolver la respuesta si es necesario
		} catch (error) {
			// Manejar errores y asignar mensajes de error si existen
			if (error.response?.data?.errors) {
				validationErrors.value = error.response.data.errors;
			} else {
				swal({
					icon: "error",
					title: "Something went wrong",
					text: error,
					background: "#2A2A2A",
					color: "#ffffff",
				});
			}
			throw error; // Relanzar el error para manejarlo en el componente si es necesario
		}
	};

	const getChips = async (userId) => {
		return axios.get('/api/users/getChips/' + userId)
			.then(response => {
				return response.data.chips;
			});
	};

	const updateChips = async (userId, chips) => {

		return axios.put('/api/users/updateChips/' + userId, {
			chips: chips
		})
			.then(response => {
				return response.data.chips;
			});
	}

	const getGameHistory = async (userId) => {
		return axios.get('/api/users/getGameHistory/' + userId)
			.then(response => {
				gameHistory.value = response.data.data;
			});
	};

	return {
		users,
		user,
		gameHistory,
		getUsers,
		getUsersWithTasks,
		getUser,
		createUserDB,
		deleteUserDB,
		changeUserPasswordDB,
		createUserProceduredDB,
		storeUser,
		updateUser,
		deleteUser,
		validationErrors,
		isLoading,
		submitVerifyIdentity,
		getChips,
		updateChips,
		getGameHistory,
	}
}
