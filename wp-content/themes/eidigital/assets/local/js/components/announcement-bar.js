import Cookies from 'js-cookie';

const announcementBar = {
	init() {
		this.setannouncementBarCookie();
	},

	setannouncementBarCookie() {
		const announcement = document.querySelector('.announcement');
		
		if ( announcement ) {
			const closeBtn = announcement.querySelector('.close');
		
			closeBtn.addEventListener('click', function handleClick(event) {
				// Remove the element from the DOM
				announcement.remove(); 
				
				// Get header element
				var header =  document.getElementById('header');
				// Set header css top
				header.style.top = 0;

				// Get duration
				let cookieDuration = announcement.getAttribute('data-cookie-duration');
				
				//get the name that we should set for the cookie
				let announcement_cookie_name = announcement.getAttribute('data-cookie-name');

				// Make sure the number is a nunmber
				cookieDuration = Number(cookieDuration);

				// Set cookie for 1 day.
				Cookies.set(announcement_cookie_name, 'true', {
					expires: cookieDuration,
				});
			})
		}
	}
}


export { announcementBar };