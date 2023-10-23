const interactiveMap = {
	init() {
		this.hotSpots();
		this.dropdown();
	},

	hotSpots() {
		// Query all hot spots on the map.
		const hotSpots =  document.querySelectorAll('.hot-spot');

		// Get all possible modal containers
		const hotSpotsModals =  document.querySelectorAll('.hot-spot--modal');

		// Get all the close buttons
		const closeBtn = document.querySelectorAll('.hotspot-close');

		// Hold on to this
		let app = this;

		// If we have any hotspots
		if (hotSpots.length > 0) {

			// Loop over each hotspot
			hotSpots.forEach((spot) => {

				// Set a event listner for each hotspot on the map for the click event. 
				spot.addEventListener('click', function handleClick(event) {
					// Get the ID of the clicked hot spot. 
					var id = this.getAttribute('data-spot');

					// Get the modal container for the correct hot spot that was clicked on. 
					var currentModal = document.querySelector("[data-spot-container='" + id + "']");

					// Remove hidden class
					currentModal.classList.remove('hidden');
					currentModal.classList.remove('lg:hidden');
					currentModal.classList.add('flex');
					
				});
			});

			// Loop over each close button
			closeBtn.forEach((close) => {
			
				// Add a click event to all close buttons
				close.addEventListener('click', function handleClick(event) {
					
					// Loop over each one and close them just in case. 
					hotSpotsModals.forEach((spot) => {
						// Hide Modal
						spot.classList.remove('flex');
						spot.classList.add('hidden');
						spot.classList.add('lg:hidden');
					});
					
				});
			});

			// Clicked outside popup
			window.onclick = function (event) {
				// If the target is the modal wrapper we want to close the modal. 
				if (event.target.classList.contains('hot-spot--modal')) {
					// Loop over each one and close them just in case. 
					hotSpotsModals.forEach((spot) => {
						// Hide Modal
						spot.classList.remove('flex');
						spot.classList.add('hidden');
						spot.classList.add('lg:hidden');
					});
				}
			}
		}
	},

	dropdown() {
		// Get all possible modal containers
		const hotSpotsModals =  document.querySelectorAll('.hot-spot--modal');

		// Get Dropdown
		const dropdown = document.querySelector('.dropdown-toggle');

		// Get Location wrapper
		const locationsWrapper = document.querySelector('.locations-wrapper');

		// Add click event to the dropdown
		dropdown.addEventListener('click', function handleClick(event) {
			// Toggle classes to show/hide the dropdown
			locationsWrapper.classList.toggle('opacity-0');
			locationsWrapper.classList.toggle('h-0');
			locationsWrapper.classList.toggle('opacity-100');
		});

		// Get all possible locations
		const locations =  document.querySelectorAll('.locations-wrapper .location');

		// Loop over each location
		locations.forEach((location) => {

			// Set a event listner for each location in the dropdown. 
			location.addEventListener('click', function handleClick(event) {
				// Loop over each one and close them just in case. 
				hotSpotsModals.forEach((spot) => {
					// Hide Modal
					spot.classList.remove('flex');
					spot.classList.add('hidden');
					spot.classList.add('lg:hidden');
				});

				// Toggle classes to show/hide the dropdown
				locationsWrapper.classList.toggle('opacity-0');
				locationsWrapper.classList.toggle('h-0');
				locationsWrapper.classList.toggle('opacity-100');

				// Replace the name of location in the dropdown selector
				dropdown.innerHTML = this.innerHTML;

				// Get the ID of the clicked hot spot. 
				var id = this.getAttribute('data-spot');

				// Get the modal container for the correct hot spot that was clicked on. 
				var currentModal = document.querySelector("[data-spot-container='" + id + "']");

				// Remove hidden class
				currentModal.classList.remove('hidden');
				currentModal.classList.remove('lg:hidden');
				currentModal.classList.add('flex');
				
			});
		});
	},
}

// The page is fully loaded.
addEventListener('load', (event) => {
	// Add JS that requires the full page to be loaded. 
	interactiveMap.init();
});

export { interactiveMap };