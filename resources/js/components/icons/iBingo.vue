<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        id="eCMcysJQqhn1"
        viewBox="0 0 131 131"
        shape-rendering="geometricPrecision"
        text-rendering="geometricPrecision"
        project-id="3b454dfb82984312848a3cbf35c24ab5"
        export-id="886bc5bd51ce4074bdd6e4216e1d4c66"
        cached="false"
        :class="{ activeHover: activeHover }"
    >
        <rect
            width="100"
            height="125"
            rx="10"
            ry="10"
            transform="translate(15.5 3)"
            fill="none"
            stroke="#fff"
            stroke-width="4"
        />
        <rect
            width="75"
            height="75"
            rx="10"
            ry="10"
            transform="translate(28 40.5)"
            fill="none"
            stroke="#fff"
            stroke-width="4"
        />
        <text
            dx="0"
            dy="0"
            font-family='"eCMcysJQqhn1:::Inter"'
            font-size="15"
            font-weight="800"
            transform="translate(41.6 27.235566)"
            fill="#fff"
            stroke-width="0"
        >
            <tspan y="0" font-weight="800" stroke-width="0">
                <![CDATA[ BINGO ]]>
            </tspan>
        </text>
        <line
            x1="-15"
            y1="0"
            x2="19.463586"
            y2="0"
            transform="matrix(0 2.17621-1 0 53 73.14315)"
            fill="none"
            stroke="#fff"
            stroke-width="4"
        />
        <line
            x1="-15"
            y1="0"
            x2="19.463586"
            y2="0"
            transform="matrix(0 2.17621-1 0 78 73.14315)"
            fill="none"
            stroke="#fff"
            stroke-width="4"
        />
        <line
            x1="-15"
            y1="0"
            x2="19.463586"
            y2="0"
            transform="matrix(2.17621 0.000042-.000019 1 60.64315 65.500633)"
            fill="none"
            stroke="#fff"
            stroke-width="4"
        />
        <line
            x1="-15"
            y1="0"
            x2="19.463586"
            y2="0"
            transform="matrix(2.17621 0.000042-.000019 1 60.64315 90.500633)"
            fill="none"
            stroke="#fff"
            stroke-width="4"
        />
    </svg>
</template>

<script setup>
import { defineProps, watch } from 'vue';
const props = defineProps({
    activeHover: Boolean,
});
watch(() => props.activeHover, (newVal) => {
    if (newVal) {
        document.querySelector('svg').classList.add('activeHover');
    } else{
        document.querySelector('svg').classList.remove('activeHover');
    }
});
</script>

<style scoped>
.activeHover rect,
.activeHover text,
.activeHover line {
    stroke: red;
}
.activeHover text{
    fill: red;
}
</style>