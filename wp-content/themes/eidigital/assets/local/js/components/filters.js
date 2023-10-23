const filters = {
	filterResults() {
		const filter_label = document.querySelectorAll('form.filteren label');
		const checkList = document.getElementsByClassName('filteren__filter--radio--container')[0];

		filter_label.forEach(function (item, index) {
			item.addEventListener('click', function handleClick(e) {
				var labels = document.querySelectorAll('form.filteren label');
				document.querySelectorAll('form.filteren label').forEach(el=>el.classList.remove('active'));
				this.classList.add('active');

				// Close dropdown
				if (checkList) {
					checkList.classList.remove('is-showing');
				}
			});
		});


		if(checkList) {
			checkList.getElementsByClassName('anchor')[0].onclick = function (e) {
				if (checkList.classList.contains('is-showing')) {
					checkList.classList.remove('is-showing');
				}
				else {
					checkList.classList.add('is-showing');
				}
			}

			// Clicked outside dropdown close it
			window.onclick = function (event) {
				if (checkList.classList.contains('is-showing') && event.target !== checkList.getElementsByClassName('anchor')[0]) {
					checkList.classList.remove('is-showing');
				}
			}

		}
	},

	anchorFilterenToSection () {
		const filterenAnchor = document.querySelector('.query_posts:not(.popup .query_posts)')
		const filterenWrapper = document.querySelector('.filteren__wrapper:not(.popup .filteren__wrapper)');

		if (!filterenWrapper) {
			return;
		}

		if ( filterenAnchor ) {
			const filterenAnchorTopPosition = filterenWrapper.getBoundingClientRect().top + window.scrollY;

			if (filterenAnchorTopPosition > 0) {
				window.addEventListener( 'filteren_posts', () => {
					window.scrollTo({
						top: filterenAnchorTopPosition - 100,
						left: 0,
						behavior: 'smooth'
					});
				});
			}

		}

	},

	offset(el) {
    var rect = el.getBoundingClientRect()
    return { top: rect.top + window.screenY, left: rect.left + window.scrollX }
	}
}


export { filters };