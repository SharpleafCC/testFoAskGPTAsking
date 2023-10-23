const accordion = {
    init() {
		this.handleAccordion();
	},

    handleAccordion: ( accordionToggleElement = '.accordion__item-title-wrap', accordionContentCover = '.accordion__item-content-cover' ) => {
        const allAccordionToggleElements = document.querySelectorAll(accordionToggleElement);

        if ( allAccordionToggleElements.length > 0 ) {
            allAccordionToggleElements.forEach( toggleElement => {
                toggleElement.addEventListener('click', (e) => {
                    e.preventDefault();

                    const accordionElement = e.target;
                    const accordionCaret = toggleElement.querySelector('.accordion__item-arrow');
                    const accordionContentCoverElement = accordionElement.closest('.accordion__item').querySelector(accordionContentCover);

                    if ( accordionCaret ) {
                        accordionCaret.classList.toggle('active');
                    }

                    if ( accordionContentCoverElement ) {
                        accordionContentCoverElement.classList.toggle('active');
                    }

                });
            });
        }
    }
}

export { accordion };