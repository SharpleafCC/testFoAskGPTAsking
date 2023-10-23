import autoFormatDateOfBirth from "../util/autoFormatDateofBirth";

const forms = {
	init() {
		this.formatDOB();
		this.klaviyoForms();
	},

	formatDOB() {
		let dobSelector = 'input[name="form_fields[dob]"], input[name="dob"]';
		autoFormatDateOfBirth(dobSelector);
	},

	klaviyoForms() {
		// Listen for Klaviyo Forms on Sumit send tracking to GTM
		window.addEventListener("klaviyoForms", function (e) {
			if (e.detail.type == 'submit') {
				if (window.dataLayer && typeof window.dataLayer.push === 'function') {
					window.dataLayer.push({ 'event': 'Klaviyo Form Submission', 'klaviyoForm': e.detail.formId });
				}
			}
		});
	},
}
export { forms };