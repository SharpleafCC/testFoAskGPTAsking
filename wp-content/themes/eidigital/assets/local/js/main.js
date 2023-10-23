import Cookies from 'js-cookie';
import { ageGate } from './components/agegate';
import { accordion } from './components/accordion';
import { header, mobile, subMenu} from './components/header';
import {forms} from './components/forms';
import { popups } from './components/popups';
import { announcementBar } from './components/announcement-bar';
import { anchorScroll } from './components/anchor-scroll';
import { entranceAnimation } from './components/entrance-animation';
import { pinSection } from './components/pin-section';
import { videos } from './components/videos';
import youtubeVideo from './videos/youtubeVideo';
import { parallaxSection } from './components/parallax-effect';
//import $ from 'jquery';

const app = {
	init() {
		accordion.init();
		ageGate.init();
		header.init();
		mobile.init();
		subMenu.init();
		forms.init();
		popups.init();
		announcementBar.init();
		anchorScroll.init();
		entranceAnimation.init();
		pinSection.init();
		videos.init();
		parallaxSection.init();
	},
}

// The DOM is fully loaded.
addEventListener('DOMContentLoaded', (event) => {
	// Add JS that works with only DOM loaded here.

	// This function needs to add a script to the DOM to load the youtube iframe API
	youtubeVideo();
	
	// The page is fully loaded.
	addEventListener('load', (event) => {
		// Add JS that requires the full page to be loaded. 
		app.init();	
	});
});



/**
 * Debounce events
 * @param {*} fn
 * @param {*} delay
 */
 function debounce(fn, delay) {
	var timer = null;

	return function () {
		var context = this,
			args = arguments;

		clearTimeout(timer);

		timer = setTimeout(function () {

			fn.apply(context, args);

		}, delay);
	};
}

// Export code to be able to call objects on sub pages.
export { app, Cookies };