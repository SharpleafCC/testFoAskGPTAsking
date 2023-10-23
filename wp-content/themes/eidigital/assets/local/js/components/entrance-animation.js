const entranceAnimation = {
	init() {
		this.entranceAnimation();
	},

	entranceAnimation() {
		// Get all sections that have entrance animaiton elements within them. 
		const sections = gsap.utils.toArray('.section-fade');

		// Loop over each section and set up the timelines and animations for the elements with the class name of fade-in-up on them. 
		sections.forEach((section, i) => {
			// Get all the elements that we want to fade in
			let headings = section.querySelectorAll(".fade-in-up");

			// Timeline init
			let tl = gsap.timeline();

			// ScrollTrigger settings
			ScrollTrigger.create({
				trigger: section,
				start: "top 70%",
				end: "bottom 55%",
				// onEnter, onLeave, onEnterBack, and onLeaveBack
				toggleActions: "restart reverse none none",
				animation: tl,
				// Uncomment markers for debugging
				//markers: true,
				onEnter: ({ direction }) => {
					if (direction === 1) { // Scrolling down
						tl.clear()
							.fromTo(headings, {
								opacity: 0,
								y: 60
							}, {
								opacity: 1,
								y: 0,
								duration: 1,
								delay: 0.4,
								stagger: 0.1,
								ease: 'power3.out'
							})
							.play();
					}
				},
				onEnterBack: ({ direction }) => {
					if (direction === -1) { // Scrolling up
						tl.clear()
							.fromTo(headings, {
								opacity: 0,
								y: -60
							}, {
								opacity: 1,
								y: 0,
								duration: 1,
								// delay: 0.4,
								// stagger: 0.1,
								ease: 'power3.out'
							})
							.play();
					}
				}
			});
		});
	}
}

export { entranceAnimation };