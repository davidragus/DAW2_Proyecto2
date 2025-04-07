<template>
	<button id="lang-container" class="bottom-buton" @click="showModal = true">
		<img :src="flagImage" alt="england" class="icon">
		<p class="color-white">{{ valueLocale }}</p>
	</button>
	<Dialog v-model:visible="showModal" header="Select Language" :modal="true"
		:style="{ width: '400px', backgroundColor: '#212121', color: 'white', borderColor: '#3B3B3B' }" appendTo="self">
		<div class="languages-container">
			<button v-for="(value, key) in locales" :key="key"
				class="language-item d-flex flex-column justify-content-center align-items-center"
				@click="setLocale(key)">
				<!-- Cambiar por banderas -->
				<img :src="`/images/${key}.webp`" alt="" class="icon">
				<p>{{ value }}</p>
			</button>
		</div>
	</Dialog>
</template>

<script setup>
import { ref, computed } from "vue";
import { langStore } from "@/store/lang";
import { useI18n } from "vue-i18n";

const i18n = useI18n({ useScope: "global" });
const locale = computed(() => langStore().locale);

let valueLocale = ref((locale.value.toUpperCase()));
const locales = computed(() => langStore().locales);
const showModal = ref(false);
function setLocale(newLocale) {
	if (i18n.locale !== newLocale) {
		langStore().setLocale(newLocale);
		valueLocale = newLocale.toUpperCase();
		showModal.value = false;
	}
}
const flagImage = computed(() => {
	return `/images/${locale.value}.webp`;
});
</script>

<style scoped>
.bottom-buton {
	width: 49%;
	height: 75px;
	background-color: #212121;
	border: none;
	border-right: 2px solid #3B3B3B;
	border-top: 2px solid #3B3B3B;
	border-top-right-radius: 25px;
}

.color-white {
	color: white;
}

.icon {
	width: 24px;
	height: 24px;
}

.languages-container {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 10px;
	padding: 10px;
}

.language-item {
	background: #313131;
	color: white;
	padding: 8px;
	border-radius: 5px;
	text-align: center;
	cursor: pointer;
	transition: background 0.3s;
	border: none;
	font-size: 16px;
}

.language-item:hover {
	background: #414141;
}
</style>