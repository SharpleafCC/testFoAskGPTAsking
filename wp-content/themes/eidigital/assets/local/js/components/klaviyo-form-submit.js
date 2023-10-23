const klaviyoFormSubmit = {
	init() {
		window.addEventListener("klaviyoForms", function (e) {
			if (e.detail.type == 'submit') {
				if (window.dataLayer && typeof window.dataLayer.push === 'function') {
					window.dataLayer.push({ 'event': 'Klaviyo Form Submission', 'klaviyoForm': e.detail.formId });
				}
			}
		});
	}
}
export { klaviyoFormSubmit };