import { ref, reactive, inject } from 'vue'
import { useRouter } from "vue-router";
import { AbilityBuilder, createMongoAbility } from '@casl/ability';
import { ABILITY_TOKEN } from '@casl/vue';
//import store from '../store'
import { authStore } from "../store/auth";

let user = reactive({
	name: '',
	email: '',
})

export default function useAuth() {
	const processing = ref(false)
	const validationErrors = ref({})
	const router = useRouter()
	const swal = inject('$swal')
	const ability = inject(ABILITY_TOKEN)
	const auth = authStore()


	const loginForm = reactive({
		email: '',
		password: '',
		remember: false
	})

	const forgotForm = reactive({
		email: '',
	})

	const resetForm = reactive({
		email: '',
		token: '',
		password: '',
		password_confirmation: ''
	})

	const registerForm = reactive({
		username: '',
		name: '',
		surname1: '',
		surname2: '',
		email: '',
		dni: '',
		gender: '',
		phone_number: '',
		password: '',
		password_confirmation: '',
		day: '',
		month: '',
		year: '',
		country: '',
		validated: false,
		terms: false,
		validationImages: [],
		country: ''
	});

	const submitLogin = async () => {
		if (processing.value) return

		processing.value = true
		validationErrors.value = {}

		await axios.post('/login', loginForm)
			.then(async response => {
				// console.log('await auth.getUser()');
				await auth.getUser()
				// console.log('uth.user.value');
				// console.log(auth.user.value);
				//await store.dispatch('auth/getUser')
				await loginUser()
				swal({
					icon: 'success',
					title: 'Login correcto',
					showConfirmButton: false,
					timer: 1500,
					background: '#2A2A2A',
					color: '#ffffff'
				})
				await router.push({ name: 'admin.index' })
			})
			.catch(error => {
				if (error.response?.data) {
					validationErrors.value = error.response.data.errors
				}
			})
			.finally(() => processing.value = false)
	}

	const submitRegister = async () => {
		if (processing.value) return

		processing.value = false
		validationErrors.value = {}

		let serializedUser = new FormData()
		for (let item in registerForm) {
			if (registerForm.hasOwnProperty(item)) {
				if (Array.isArray(registerForm[item]) && registerForm[item].length > 0) {
					registerForm[item].forEach((value, index) => {
						serializedUser.append(`${item}[${index}]`, value);
					});
				} else if (registerForm[item]) {
					serializedUser.append(item, registerForm[item])
				}
			}
		}
		await axios.post('/register', serializedUser,{
				headers: {
					'Content-Type': 'multipart/form-data'
				}
		})
			.then(async response => {
				// await store.dispatch('auth/getUser')
				// await loginUser()
				router.replace({ path: '/', query: {closeModal: 'register'} })
				swal({
					icon: 'success',
					title: 'Registration successfully',
					showConfirmButton: false,
					timer: 1500,
					background: '#2A2A2A',
					color: '#ffffff'
				})
				// await router.push({ name: 'auth.login' })
			})
			.catch(error => {
				if (error.response?.data) {
					validationErrors.value = error.response.data.errors
				}
			})
			.finally(() => processing.value = false)
	}

	const submitForgotPassword = async () => {
		if (processing.value) return

		processing.value = true
		validationErrors.value = {}

		await axios.post('/api/forget-password', forgotForm)
			.then(async response => {
				swal({
					icon: 'success',
					title: 'We have emailed your password reset link! Please check your mail inbox.',
					showConfirmButton: false,
					timer: 1500
				})
				// await router.push({ name: 'admin.index' })
			})
			.catch(error => {
				if (error.response?.data) {
					validationErrors.value = error.response.data.errors
				}
			})
			.finally(() => processing.value = false)
	}

	const submitResetPassword = async () => {
		if (processing.value) return

		processing.value = true
		validationErrors.value = {}

		await axios.post('/api/reset-password', resetForm)
			.then(async response => {
				swal({
					icon: 'success',
					title: 'Password successfully changed.',
					showConfirmButton: false,
					timer: 1500
				})
				await router.push({ name: 'auth.login' })
			})
			.catch(error => {
				if (error.response?.data) {
					validationErrors.value = error.response.data.errors
				}
			})
			.finally(() => processing.value = false)
	}

	const loginUser = () => {
		//const auth = authStore(); //TODO test
		// console.log('loginUser auth Compostable ' + auth.user)
		// console.log(auth.user)
		user = auth.user
		//user = store.state.auth.user
		// Cookies.set('loggedIn', true)
		getAbilities()
	}

	const getUser = async () => {
		const auth = authStore();
		// console.log('getUser')

		if (auth.authenticated) {
			await auth.getUser()
			// console.log(auth.user.value)
			// console.log(auth.authenticated)
			await loginUser()
		}
	}

	const logout = async () => {
		if (processing.value) return

		processing.value = true

		axios.post('/logout')
			.then(response => {
				user.name = ''
				user.email = ''
				auth.logout()
				//store.dispatch('auth/logout')
				router.push({ name: 'auth.login' })
			})
			.catch(error => {
				// swal({
				//     icon: 'error',
				//     title: error.response.status,
				//     text: error.response.statusText
				// })
			})
			.finally(() => {
				processing.value = false
				// Cookies.remove('loggedIn')
			})
	}

	const getAbilities = async () => {
		await axios.get('/api/abilities')
			.then(response => {
				const permissions = response.data
				const { can, rules } = new AbilityBuilder(createMongoAbility)

				can(permissions)

				ability.update(rules)
			})
	}

	return {
		loginForm,
		registerForm,
		forgotForm,
		resetForm,
		validationErrors,
		processing,
		submitLogin,
		submitRegister,
		submitForgotPassword,
		submitResetPassword,
		user,
		getUser,
		logout,
		getAbilities
	}
}
