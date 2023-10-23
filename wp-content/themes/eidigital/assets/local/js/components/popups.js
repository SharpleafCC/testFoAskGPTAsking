import Cookies from 'js-cookie';

const popups = {
	init() {
		this.selectPopups();
		this.openViaUTM();
		this.handleModals();
		this.changePopupFormLocation();
	},

	selectPopups() {
		var popups = document.querySelectorAll('.popup');

		// Hold on to this
		let app = this;

		popups.forEach(function (item, index) {
			popups[index].setAttribute('index', index);
			let requiredConditions = 0;
			let conditionsMet = 0;

			// Get the data attribute of the current popup for the cookie. This allows us to have more than one popup across the site using different cookie names to track them.
			var cookie_id = popups[index].getAttribute('data-cookieid');

			// Toggle popup based on cookie existence OR if the data-attribute is set or not
			if (!Cookies.get(cookie_id) || (typeof popups[index].getAttribute('data-expiration-days') === 'null')) {
				if (popups[index].hasAttribute('data-display-on-load')) {
					app.showPopup(app, popups[index]);
				}
				else if (popups[index].hasAttribute('data-display-by-rules')) {
					//delay time
					if (popups[index].hasAttribute('data-display-time-amount')) {
						requiredConditions++;
						popups[index].setAttribute('requiredConditions', requiredConditions);
						popups[index].setAttribute('conditionTimerMet', false);
						let delay_time = Number(popups[index].getAttribute('data-display-time-amount'));
						
						popups[index]['timer'] = setTimeout(function () {
							if (!popups[index]['conditionTimerMet']) {
								conditionsMet++;
								popups[index].setAttribute('conditionsMet', conditionsMet);
								popups[index].setAttribute('conditionTimerMet', true);
							}
							app.showPopup(app, popups[index]);
						}, delay_time * 1000);
					}
					//scroll amount
					if (popups[index].hasAttribute('data-display-scroll-amount')) {
						requiredConditions++;
						popups[index].setAttribute('requiredConditions', requiredConditions);
						popups[index].setAttribute('conditionScrollMet', false);
						// On scroll check percentage
						window.addEventListener('scroll', function() {
							if(popups[index].getAttribute('conditionScrollMet') == 'false') {
								// Get scroll percentage
								var scrollPercent = (document.documentElement.scrollTop + document.body.scrollTop) / (document.documentElement.scrollHeight - document.documentElement.clientHeight) * 100;

								if (scrollPercent >= popups[index].getAttribute('data-display-scroll-amount')) {
									if (popups[index].getAttribute('conditionScrollMet') == "false") {
										conditionsMet++;
										popups[index].setAttribute('conditionsMet', conditionsMet);
										popups[index].setAttribute('conditionScrollMet', true);
									}
									app.showPopup(app, popups[index]);
								} else {
									//if they're no longer scrolled down far enough to trigger the popup, decrement the scroll conditions counter and set the flag to false so it can be incremented again later.
									if (popups[index].getAttribute('conditionScrollMet') == "true") {
										conditionsMet--;
										popups[index].setAttribute('conditionsMet', conditionsMet);
										popups[index].setAttribute('conditionScrollMet', false);
									}
								}
							}
						});						
					}
					//exit intent
					if (popups[index].hasAttribute('data-display-on-exit')) {
						requiredConditions++;
						popups[index].setAttribute('requiredConditions', requiredConditions);
						popups[index].setAttribute('conditionExitMet', false);

						document.addEventListener('mouseout', e => {
							if (!e.toElement && !e.relatedTarget) {
								if (popups[index].getAttribute('conditionExitMet') == 'false') {
									conditionsMet++;
									popups[index].setAttribute('conditionsMet', conditionsMet);
									popups[index].setAttribute('conditionExitMet', true);
								}
								const shouldShowExitIntent =
									!e.toElement &&
									!e.relatedTarget &&
									e.clientY < 10;
	
								if (shouldShowExitIntent) {
									app.showPopup(app, popups[index]);
								}
							}
						});

						//if the mouse re-enters the screen, unset the exit intent flags
						document.addEventListener('mouseenter', e => {
							if (popups[index]['conditionExitMet'] == 'true') {
								conditionsMet--;
								popups[index].setAttribute('conditionsMet', conditionsMet);
								popups[index].setAttribute('conditionExitMet', false);
							}
						});
					}
				}
			}
		});
	},

	// Open a popup based on trigger class from the UTM param /?popup-open=TRIGGER_CLASS
	openViaUTM() {
		// Hold on to this
		let app = this;

		// Set if we want to remove popup from DOM on close.
		let remove = false;

		// Check for UTM
		var trigger_class = getQueryVariable('popup-open');

		if(trigger_class.length > 0) {
			// Get popup
			let popup = document.querySelector("[data-popup-trigger='" + trigger_class + "']");

			// Show popup
			app.showPopup(app, popup, remove);
		}
	},

	handleModals() {
		// Hold on to this
		let app = this;

		// Set if we want to remove popup from DOM on close.
		let remove = false;

		// Get all possible modal windows on page load.
		var popup_modal = document.querySelectorAll('.popup--trigger');

		// Loop over each one and set click event
		popup_modal.forEach(item => {
			item.addEventListener('click', function handleClick(event) {
				event.stopPropagation();

				// Get trigger class
				var trigger_class = item.getAttribute('data-trigger');

				// Get popup
				let popup = document.querySelector("[data-popup-trigger='" + trigger_class + "']");

				// Show popup
				app.showPopup(app, popup, remove);
			});
		});
	},

	showPopup(app, popup, remove) {
		let requiredConditions = Number(popup.getAttribute('requiredConditions'));
		let conditionsMet = Number(popup.getAttribute('conditionsMet'));

		//if the all-conditions flag is not set or (the all-conditions flag has been set and the required amount of conditions have been met)
		if (typeof popup.getAttribute('data-all-conditions') === 'undefined' || (typeof popup.getAttribute('data-all-conditions') !== 'undefined' && (conditionsMet >= requiredConditions))) {

			if (typeof popup['timer'] !== 'undefined') {
				clearTimeout(popup['timer']);
			}

			// Popup CSS ID
			var popup_id = '#' + popup.getAttribute('id');

			// Set fly-in duration, if enabled
			if (typeof popup.getAttribute("data-fly-in-duration") !== 'undefined') {
				var css_duration = popup.getAttribute("data-fly-in-duration");
				popup.querySelector('.popup__content').style.animationDuration = css_duration + 'ms';
			}

			// fade in, if set
			if (typeof popup.getAttribute("data-fade-in-duration") !== 'undefined') {
				var css_duration = popup.getAttribute("data-fade-in-duration");
				app.fadeIn(popup, css_duration);
			}

			// Show popup
			popup.classList.add('active');

			// Get close button
			const popup_close = document.querySelectorAll('.close--popup');

			// Add event listener for close button on click
			popup_close.forEach(close => {
				close.addEventListener('click', function handleClick(event) {
					app.closePopup(app, popup, remove);
				});
			});

			// Esc Key Pressed 
			document.addEventListener('keydown', function (e) {
				if (e.key == "Escape" || e.key == "Esc") {
					app.closePopup(app, popup, remove);
				}
			});

			// Clicked outside popup
			window.onclick = function (event) {
				var popup_inside = document.getElementById(popup.getAttribute('id'));

				if (event.target === popup_inside) {
					app.closePopup(app, popup, remove);
				}
			}
		}
	},

	closePopup(app, popup, remove) {
		popup.classList.remove('active');

		const closePopup = new CustomEvent("close_popup", {
			bubbles: true,
			cancelable: true,
			detail: {
				name: popup.getAttribute('data-popup-trigger'),
				isClose: true,
			}
		});

		if (typeof popup.getAttribute('data-expiration-days') !== 'undefined') {
			var expiration_days = popup.getAttribute('data-expiration-days');
			var cookie_id = popup.getAttribute('data-cookieid');
			app.setPopupCookie(cookie_id, expiration_days);
		}

		// IF Remove the entire popup from the DOM so we can't trigger it again.
		if(remove) {
			popup.remove();
		}

		document.querySelector('body').classList.remove('popup-is-active')

		window.dispatchEvent(closePopup);
	},

	fadeIn(element, duration = 600) {
		element.style.display = '';
		element.style.opacity = 0;
		var last = +new Date();
		var tick = function () {
			element.style.opacity = +element.style.opacity + (new Date() - last) / duration;
			last = +new Date();
			if (+element.style.opacity < 1) {
				(window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
			}
		};
		tick();
	},

	setPopupCookie(cookie_id, expiration_days) {
		var expiration_days = Number(expiration_days);
		// Set cookie for X day(s).
		Cookies.set(cookie_id, 'true', {
			expires: expiration_days,
		});
	},

	changePopupFormLocation() {
		const popups = document.querySelectorAll('section.popup');
		const popupsWithForms = [...popups].filter( popup => popup.querySelector('form') ); // Make the NodeList an array so we can use .filter

		if ( popupsWithForms.length > 0 ) {
			popupsWithForms.forEach( popup => {
				const locationField = popup.querySelector('input[name="page_location"]');
				const pageTitle = document.title;

				if ( pageTitle && locationField ) {
					locationField.value = pageTitle;
				} 
			});
		}
	}
}

// Get Query Variable allows us to Pull out a query var from the url
function getQueryVariable(variable) {
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i = 0; i < vars.length; i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
			return pair[1];
		}
	}
	return false;
}

// // The page is fully loaded.
// addEventListener('load', (event) => {
// 	// Add JS that requires the full page to be loaded. 
// 	popups.init();
// });

export { popups };