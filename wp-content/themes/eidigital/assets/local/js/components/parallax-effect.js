const parallaxSection = {
	init() {
		this.parallaxSection();
	},

	parallaxSection() {
		// Get all sections that have entrance animaiton elements within them. 
		const sections = gsap.utils.toArray('.section-parallax');

		// Loop over each section and set up the timelines and animations for the elements with the class name of fade-in-up on them. 
		sections.forEach((section, i) => {
			// Get all the elements that we want to fade in
			let imageLeftTop = section.querySelectorAll(".image-left-top");
			let imageLeftBottom = section.querySelectorAll(".image-left-bottom");
			let imageRightTop = section.querySelectorAll(".image-right-top");
			let imageRightBottom = section.querySelectorAll(".image-right-bottom");

			// Timeline init
			let tl = gsap.timeline();

			/*
			const tl = gsap.timeline({
				scrollTrigger: {
					scrub: 1,
					pin: true,
					trigger: "#pin-windmill",
					start: "50% 50%",
					endTrigger: "#pin-windmill-wrap",
					end: "bottom 50%",
				},
			});

			tl.to("#pin-windmill-svg", {
				rotateZ: 900,
			});
			*/

			// ScrollTrigger settings
			ScrollTrigger.create({
				//scrub: 1,
				//pin: true,
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
						.fromTo(imageLeftTop, {
							y: 0
						}, {
							y: 120,
							duration: 1,
							delay: 0.4,
							stagger: 0.1,
							ease: 'power3.out'
						})
						.fromTo(imageLeftBottom, {
							y: 0
						}, {
							y: -120,
							duration: 1.5,							
							ease: 'power3.out'
						})
						.fromTo(imageRightTop, {
							y: 0
						}, {
							y: 120,
							duration: 1.5,							
							ease: 'power3.out'
						})
						.fromTo(imageRightBottom, {
							y: 0
						}, {
							y: -120,
							duration: .5,							
							ease: 'power3.out'
						})
						.play();
					}
				},
			});
		});

		/*

		gsap.from(".image-left-top", {
			scrollTrigger: {
				trigger: ".section-parallax",
				scrub: true,
				pin: false,
				start: "top top",
				end: "+=100%"
			},
			y: 200,
			duration: 1,
			ease: "none"
		});

		gsap.from(".image-left-bottom", {
			scrollTrigger: {
				trigger: ".section-parallax",
				scrub: true,
				pin: false,
				start: "top top",
				end: "+=100%"
			},
			y: -200,
			duration: 1,
			ease: "none"
		});

		gsap.from(".image-right-top", {
			scrollTrigger: {
				trigger: ".section-parallax",
				scrub: true,
				pin: false,
				start: "top top",
				end: "+=100%"
			},
			y: 200,
			duration: 1,
			ease: "none"
		});

		gsap.from(".image-right-bottom", {
			scrollTrigger: {
				trigger: ".section-parallax",
				scrub: true,
				pin: false,
				start: "top top",
				end: "+=100%"
			},
			y: -200,
			duration: 1,
			ease: "none"
		});
		*/
	}
}

export { parallaxSection };


