import Cookies from 'js-cookie';

const ageGate = {
	init() {
		// Get agegate element
		let agegate = document.getElementById('agegate');
		if(typeof(agegate) != 'undefined' && agegate != null) {
			// Call Age Gate Logic
			this.checkAgeGate(agegate);
		}
		else {
			// Add back content behind age gate
			document.body.classList.add('age-gate-passsed');
		}
		
	},
	checkAgeGate(agegate) {
		// Allow us to check url params
		const urlParams = new URLSearchParams(window.location.search);

		// Toggle age gate based on cookie existence, url params or status set in admin. 
		if (Cookies.get('age-gate-passed') || document.body.classList.contains('skip-age-gate') || urlParams.has('skipAgeGate')) {
			// Add back content behind age gate
			document.body.classList.add('age-gate-passsed');

			// Remove age gate active class
			agegate.classList.remove('active'); 
			
			// Remove Age Gate From DOM
			agegate.parentNode.removeChild(agegate);

		}
		else {
			// Remove no Scroll
			document.body.classList.add('dialog-prevent-scroll');

			// Open Modal
			agegate.classList.add('active');

			// Age gate buttons
			const ageGateYes = document.querySelector('#age-gate__button--yes');

			// If we have a yes button attach events
			if (ageGateYes) {
				// Click and Touch event listener that sets cookie to pass age gate
				ageGateYes.addEventListener('touchstart', this.handleInteraction, { passive: true });
				ageGateYes.addEventListener('click', this.handleInteraction, { passive: true });
			}

		}
	},

	// Handles both touch or click event for age gate
	handleInteraction(evt) {
		Cookies.set('age-gate-passed', 'true', {
			expires: 365,
		});
		// Add back content behind age gate
		document.body.classList.add('age-gate-passsed');

		// Remove no Scroll
		document.body.classList.remove('dialog-prevent-scroll');

		// Hide Age gate
		agegate.classList.remove('active');
		
		// Remove Age gate form DOM
		agegate.parentNode.removeChild(agegate);
	}
}

export { ageGate };