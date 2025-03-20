import { ref } from 'vue'

export default function useCountries() {
	const countries = ref([]);

	const getCountries = async () => {
		axios.get('/api/countries')
			.then(response => {
				countries.value = response.data.data;
			});
	}

	return {
		countries,
		getCountries
	}
}
