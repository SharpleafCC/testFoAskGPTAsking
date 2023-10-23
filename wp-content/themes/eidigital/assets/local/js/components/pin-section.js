const pinSection = {
	init() {
		this.pinSection();
	},

	pinSection() {
		// Loop over each section and set up pinning and scrubbing on the section. 
		gsap.utils.toArray(".section-pin").forEach((section, i) => {
			ScrollTrigger.create({
				trigger: section,
				start: "top top",
				pin: true,
				srub: 1,
				pinSpacing: false,
				// Uncomment markers for debugging
				//markers: true,
			});
		});
	}
}

export { pinSection };