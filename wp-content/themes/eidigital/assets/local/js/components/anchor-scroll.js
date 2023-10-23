const anchorScroll = {
	init() {
		this.anchorScroll();
	},

	anchorScroll() {
		// Get all anchor links on the page
		let anchorlinks = document.querySelectorAll('a[href^="#"]');

		// Set up event listners for all anchor links
		for (let item of anchorlinks) {
			item.addEventListener('click', (e)=> {
				// Get the hash value
				let hashval = item.hash;
				if ( hashval.length != 1 ) { 
					// Get the target element
					let target = document.querySelector(hashval);

					// Set offset
					const yOffset = 100; 

					// Get the pos based on the target, window and offset
					const y = target.getBoundingClientRect().top + window.pageYOffset - yOffset;

					// Scroll to target
					window.scrollTo({top: y, behavior: 'smooth'});

					// Push history into address bar. 
					history.pushState(null, null, hashval)
				}
				e.preventDefault()
			})
		}
	}
}

export { anchorScroll };