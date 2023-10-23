const header = {
	init() {
		// Set up gsap timeline const and menu flag
		const tl = gsap.timeline();
		const menuFlag = true;

		// Sticky header and buy now button scroll logic.
		window.addEventListener('scroll', () => this.checkIfHeaderIsFixed());
		window.addEventListener('resize', () => this.checkIfHeaderIsFixed());
		this.checkIfHeaderIsFixed();
		this.headerScroll();
		this.navSetup(tl, menuFlag);		
	},

	navSetup(tl, menuFlag) {
		const mobileMenu = document.querySelector('#mobile-menu');

		tl.to('.mobile-menu-panel li a', {
			translateY: '100%',
			duration: 0.1,
		})

		tl.to('.menu-overlay', {
			width: '0',
			duration: 0.1,
		})

		mobileMenu.addEventListener('click', () => {
			if(menuFlag) {
				this.openMenu(tl);
				menuFlag = false;
			}
			else {
				this.closeMenu(tl);
				menuFlag = true;
			}
		})
	},

	openMenu(tl) {
		tl.to('.menu-overlay', {
			width: '100%',
			duration: 0.3,
		})

		tl.to('.mobile-menu-panel li a', {
			translateY: '0',
			duration: 0.4,
		})
	},

	closeMenu(tl) {
		tl.to('.mobile-menu-panel li a', {
			translateY: '100%',
			duration: 0.3,
		})

		tl.to('.menu-overlay', {
			width: '0',
			duration: 0.4,
		})		
	},

	headerScroll() {
		const headerNav = document.querySelector('header');
		const announcement = document.querySelector('.announcement');
		const headerHeight = headerNav.getBoundingClientRect().height;
		let totalHeight = headerHeight;
		let headerAnimSelectors = '#header, .announcement';

		if (announcement) {
			totalHeight = headerHeight + announcement.getBoundingClientRect().height;
			if (announcement.classList.contains('bottom')) {
				headerAnimSelectors = '#header';
			}
		}

		const showAnim = gsap.from(headerAnimSelectors, {
			y: -totalHeight,
			paused: true,
			duration: 0.2
		}).progress(1);

		ScrollTrigger.create({
			start: "top top",
			end: 99999,
			onUpdate: (self) => {
				self.direction === -1 ? showAnim.play() : showAnim.reverse()
			}
		});
	},

	checkIfHeaderIsFixed() {
		const headerNav = document.querySelector('header');
		const announcement = document.querySelector('.announcement');		

		//gsap.fromTo(headerNav, {y: '-100%'}, {y: '0%', duration: 1});

		if (headerNav.classList.contains('is-fixed')) {
			this.sticky();
		} 
		else {
			if (announcement) {			
				if (announcement.classList.contains('below_header')) {
					const headerHeight = headerNav.getBoundingClientRect().height
					announcement.style.top = headerHeight + 'px'

					let scrollPos = window.pageYOffset

					if (scrollPos > (headerHeight - 10)) {
						announcement.style.top = 0 + 'px'
					}
				}
			}
		}
	},

	sticky() {
		const announcementHeight = document.querySelector('.announcement') ? document.querySelector('.announcement').clientHeight : 0;

		// Get scroll position from top of document
		let scrollPos = window.pageYOffset,
			headerNav = document.querySelector('header'),
			buyNowBtn = document.querySelector('.button__buynow'),
			announcement = document.querySelector('.announcement');


		if (scrollPos > 20) {
			// Sets the header color ON SCROLL from the admin settings. 
			headerNav.style.backgroundColor = headerNav.getAttribute('data-sticky-color');
			if (announcement) {
				// Add sticky class
				announcement.classList.add('is-sticky');

				// Set annoucement bar background color ON Scroll from the admin settings. 
				announcement.style.backgroundColor = announcement.getAttribute('data-sticky-color');
			}

			if (announcement && announcement.classList.contains('below_header')) {
				const headerHeight = headerNav.getBoundingClientRect().height
				announcement.style.top = headerHeight + 'px'
			}

			if (announcement && !announcement.classList.contains('is-sticky')) {
				headerNav.style.top = 0 + 'px'
			}
		} else {

			// Sets the header color based on admin field.
			headerNav.style.backgroundColor = headerNav.getAttribute('data-top-color');

			if (announcement) {
				announcement.style.backgroundColor = announcement.getAttribute('data-top-color');
			}

			if (announcement && announcement.classList.contains('above_header') && headerNav.previousElementSibling.classList.contains('announcement')) {
				headerNav.style.top = announcementHeight + 'px';
			}

			if (announcement && announcement.classList.contains('below_header')) {
				const headerHeight = headerNav.getBoundingClientRect().height
				announcement.style.top = headerHeight + 'px'
			}
		}

		// If user refreshes and not at top.
		if (buyNowBtn) {
			if (scrollPos > 20) {
				buyNowBtn.classList.add('active');
			}
			else {
				buyNowBtn.classList.remove('active');
			}
		}
	}
}

const subMenu = {
	init() {

		const desktopNavigationItemWithSubMenu = document.querySelectorAll('.header__nav--desktop .sub__menu--wrapper');

		if (desktopNavigationItemWithSubMenu.length > 0) {
			desktopNavigationItemWithSubMenu.forEach(menuItem => {
				const subMenu = menuItem.querySelector('ul.sub__menu');

				if (subMenu) {
					menuItem.addEventListener('mouseover', (e) => {
						subMenu.classList.add('active');
					});

					menuItem.addEventListener('mouseout', (e) => {
						subMenu.classList.remove('active');
					});
				}
			});
		}

		const mobileNavigationItemWithSubMenu = document.querySelectorAll('.header__nav--mobile .sub__menu--wrapper > a');

		if (mobileNavigationItemWithSubMenu.length > 0) {
			mobileNavigationItemWithSubMenu.forEach(menuItem => {
				menuItem.addEventListener('click', (e) => {
					e.preventDefault();

					menuItem.classList.toggle('sub__menu--active');

					const nextSibling = menuItem.nextElementSibling;

					if (nextSibling && nextSibling.classList.contains('sub__menu-cover')) {
						nextSibling.classList.toggle('sub__menu-cover--show');
					}
				});
			});
		}
	},
};

const mobile = {
	init() {
		const mobileMenu = document.querySelector('#mobile-menu')

		mobileMenu.addEventListener('click', () => {
			mobileMenu.classList.toggle('open');
			document.querySelector('.mobile-menu-panel').classList.toggle('is-active');
			document.querySelector('body').classList.toggle('mobile-menu-is-active');
			document.querySelector('header').classList.toggle('mobile-menu-is-active');
		})
	}
}

export { header, mobile, subMenu };