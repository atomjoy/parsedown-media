import Splide from '@splidejs/splide'

/*
// Load css in app.css
@import '@splidejs/splide/css';

// In component don't run twice
import { loadSplide } from '@/utils/loadSplide'
onMounted(() => {
    loadSplide()
})
*/

export async function loadSplide() {
	if (document.querySelectorAll('.splide').length > 0) {
		// @ts-ignore
		setTimeout(() => {
			let spl = new Splide('.splide', { type: 'loop' })
			if (spl) {
				spl.mount()
			}
		}, 1000)
	}
}
