/**
 * Autoformats a DOB input field to the following format: MM/DD/YYYY
 * @param {String} selector A CSS Selector that will find elements via querySelectorAll
 */
 const autoFormatDateOfBirth = (selector) => {
	let dobFields = document.querySelectorAll(selector);

	// If no max length is set, set it to 10 
	if (dobFields.length > 0) {
		dobFields.forEach( dob => {
			if (dob.type === 'text') {
                // Set the maxlength to 10 if its not already
                let dobFieldMaxLength = dob.getAttribute('maxlength');
				if ( !dobFieldMaxLength || dobFieldMaxLength != 10 ) {
					dob.setAttribute('maxlength', 10);
				}

				['keyup', 'keydown'].forEach( event => {
					dob.addEventListener(event, function(e) {
						if (e.which !== 8) {
							let numCharacters = dob.value.length;
	
							if (numCharacters === 2 || numCharacters === 5) {
								let currentValue = dob.value;
								currentValue += '/';
								dob.value = currentValue;
							}
						}
					});
				});
			} else {
				// If the field isnt type text we can't auto format
				console.error('Cannot auto format DOB input field because it is not type text!');
			}
		})
	}
}

export default autoFormatDateOfBirth;