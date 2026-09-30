<script setup lang="ts">
// npm install @splidejs/splide
// npm install highlight.js
import { onMounted } from 'vue'
import { loadSplide } from '@/utils/loadSplide'
import hljs from 'highlight.js'
import 'highlight.js/styles/github.css'

const props = defineProps<{ payload: string }>()

onMounted(() => {
	loadSplide()
	hljs.highlightAll()
})
</script>

<template>
	<div v-html="props.payload" class="prose markdown mt-1 max-w-none rounded border p-4"></div>
</template>
