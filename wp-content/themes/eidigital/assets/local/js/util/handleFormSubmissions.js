import createRecommendedAddressContent from "../util/createRecommendedAddressContent";
import tingle from 'tingle.js';
import $ from 'jquery';

const handleFormSubmissions = (cssSelector, formSessionStorage) => {

    const selector = typeof cssSelector === 'string' ? cssSelector : 'form[action="https://eidigital.com"]';

    // This is not tested and might not work yet. 
    let dataForm = document.querySelector(selector);

    // instanciate new modal
	const modal = new tingle.modal({
		footer: false,
		stickyFooter: false,
		closeMethods: ['overlay', 'button', 'escape'],
		closeLabel: "Close",
		cssClass: ['custom-class-1', 'custom-class-2'],
		onOpen: function() {
			// console.log('modal open');
		},
		onClose: function() {
			// console.log('modal closed');
		},
		beforeClose: function() {
			// here's goes some logic
			// e.g. save content before closing the modal
			return true; // close the modal
			return false; // nothing happens
		}
	});

    $(dataForm).on('submit', function(e) {
        e.preventDefault();

        const form = $(this)[0];
        const formName = form.name;
        const formFields = form.querySelectorAll('.ei-form__field');

        // Get the amount of submissions for the current form at the time of submitting
        let amountOfFormSubmissions = formSessionStorage.getItem(formName);

        // If the form has not been tracked for submissions yet, at it to our session storage with the unique identifier
        // being the form name. Set the amount of submissions to 1
        if ( amountOfFormSubmissions == null ) {
            formSessionStorage.setItem(formName, 1);
        }

        resetFormFieldState(formFields, formName);

        if ( form ) {
            const formData = new FormData(form);
            const formUrl = window.location.origin + '/wp-admin/admin-ajax.php';
            const loader = $('.form-loader');

            formData.set('action', 'campaign_form_submission');
            formData.set('form_name', formName);
            formData.set('recommend_address_count', formSessionStorage.getItem(formName));

            // setAdditionalFormData(formData, form);

            $.ajax({
                url: formUrl,
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                beforeSend: function() {
                    if ( $(loader).length ) {
                        $(loader).addClass('form-loader--active');
                    }
                },
                complete: function() {
                    if ( $(loader).length ) {
                        $(loader).removeClass('form-loader--active');
                    }
                },
                success: function(data) {
                    const returnData = JSON.parse(data);
                    console.log(returnData);

                    // We have errors
                    if ( ('error' in returnData) && returnData['error'] === true ) {
                        // Variable for the error message
                        let errorMessage = '';

                        // Non address field validation errors
                        if ( ('invalid_fields' in returnData) && returnData['invalid_fields'].length > 0 ) {
                            returnData['invalid_fields'].forEach( field => {
                                const invalidField = form.querySelector(`[name="${field}"]`);
                                const invalidFieldLabel = $(`form[name="${formName}"] label[for="${field}"][class="ei-form__label-error"]`);

                                if ( invalidField ) {
                                    invalidField.classList.add('ei-form__field--error');
                                }

                                if ( invalidFieldLabel ) {

                                    if ( field === 'zip' ) {
                                        const invalidUspsMessage = $(invalidFieldLabel).data('invalidUsps');
                                        const invalidDefaultMessage = $(invalidFieldLabel).data('invalidDefault');
                                        const invalidType = returnData['invalid_zip'] ? returnData['invalid_zip'] : '';

                                        if ( invalidType ) {
                                            if ( invalidType == 'invalid-usps' ) {
                                                $(invalidFieldLabel).text(invalidUspsMessage);
                                            } else if ( invalidType == 'invalid-default') {
                                                $(invalidFieldLabel).text(invalidDefaultMessage);
                                            }
                                        }
                                    }

                                    $(invalidFieldLabel).fadeIn('slow');
                                }
                            });
                        }

                        // Address validation errors
                        if ( ('invalid_address_fields' in returnData) && returnData['invalid_address_fields'].length > 0 ) {
                            returnData['invalid_address_fields'].forEach( field => {
                                const invalidField = form.querySelector(`input[name=${field}]`);

                                if ( invalidField ) {
                                    invalidField.classList.add('ei-form__field--error');
                                }
                            });
                        }

                        switch ( returnData['error_msg'] ) {
                            case 'Max amount of entries':
                                errorMessage = 'Sorry, we are no longer accepting submissions!';
                                break;
                            case 'Only one entry per email allowed':
                                errorMessage = 'You\'ve already entered the giveaway!';
                                break;
                            case 'Minimum age not met':
                                errorMessage = `You must be ${returnData['minimum_age']} or older to enter!`;
                                break;
                            case 'City/State does not match given zipcode':
                                errorMessage = 'Please check that your city/state/zip code are correct.';
                                break;
                            
                            case 'Address Not Found.':
                            case 'Address not deliverable':
                                errorMessage = 'The address you entered has not been found! Please enter a valid USPS address to proceed.';
                                break;

                            case 'Invalid address2/More information needed such as apt, suite, etc':
                                errorMessage = 'The address you entered has not been found! Please enter the correct address 2 information (apt, suite, etc).';
                                break;
                        }

                        setFormMessage(errorMessage);
                    }

                    // Check to see if we have a recommended address
                    if ( ('recommend_address' in returnData) && returnData['recommend_address']) {
                        if ( ('recommended_address' in returnData) && Object.keys(returnData['recommended_address']).length > 0 ) {
                            if ( formSessionStorage.getItem(formName) == 1 ) {
                                const currentAddress = {
                                    address_1: formData.get('address_1'),
                                    address_2: formData.get('address_2'),
                                    city: formData.get('city'),
                                    state: formData.get('state'),
                                    zip: formData.get('zip'),
                                };

                                const recommendedAddress = {
                                    address_1: returnData['recommended_address'].address_1[0],
                                    address_2: returnData['recommended_address'].address_2[0] ? returnData['recommended_address'].address_2[0] : '',
                                    city: returnData['recommended_address'].city[0],
                                    state: returnData['recommended_address'].state[0],
                                    zip: returnData['recommended_address'].zip[0],
                                };

                                document.addEventListener('click', (e) => {
                                    if ( e.target && e.target.classList.length > 0 ) {
                                        if ( e.target.classList.contains('address-content__wrap') || (e.target.parentNode && e.target.parentNode.classList.length > 0 && e.target.parentNode.classList.contains('address-content__wrap')) ) {
                                            // Handle updating the form with the values if necessary, closing the form, then force submitting the form
                                            const addressType = e.target.dataset.addressType;
                        
                                            if ( addressType === 'recommended-address' ) {
                                                if ( formFields.length > 0 ) {
                                                    formFields.forEach( field => {
                                                        if ( field.name in recommendedAddress ) {
                                                            field.value = recommendedAddress[field.name];
                                                        }
                                                    });
                                                }
                                            }
                                            
                                            amountOfFormSubmissions++
                                            formSessionStorage.setItem(formName, amountOfFormSubmissions);

                                            modal.close();
                                        }
                                    }
                                });

                                modal.setContent(createRecommendedAddressContent(currentAddress, recommendedAddress));

                                modal.open();
                            }
                        }
                    }

                    // Successful submission
                    if ( returnData['error'] == false && !('recommend_address' in returnData) ) {
                        // No errors - successful submission
                        const successShowContainerSelector = form.querySelector('input[name="success_show_container_selector"]') ? form.querySelector('input[name="success_show_container_selector"]').value : '';
                        const successHideContainerSelector = form.querySelector('input[name="success_hide_container_selector"]') ? form.querySelector('input[name="success_hide_container_selector"]').value : '';

                        if ( successHideContainerSelector ) {
                            if ( successHideContainerSelector.charAt(0) == '.' || successHideContainerSelector.charAt(0) == '#') {
                                $(`${successHideContainerSelector} .form-success-hide`).fadeOut('slow');
                            } else {
                                console.error('Function handleFormSubmissions expects form field success_hide_container_selector to be a valid HTML class or ID.');
                            }
                        }

                        if ( successShowContainerSelector ) {
                            if ( successShowContainerSelector.charAt(0) == '.' || successShowContainerSelector.charAt(0) == '#') {
                                $(`${successShowContainerSelector} .form-success-show`).fadeIn('slow');
                            } else {
                                console.error('Function handleFormSubmissions expects form field success_show_container_selector to be a valid HTML class or ID.');
                            }
                        }

                        resetForm(returnData, formFields, formName, formSessionStorage);
                        setFormMessage('Thank you for submitting the form.');

                        if ( window.dataLayer && typeof window.dataLayer.push === 'function' ) {
                            window.dataLayer.push( {'event': 'Custom Form Submission', 'formName': formName} );
                        }
                    }
                }
            });
        }        
    });
}

// const getAllMultiCheckboxNames = (checkedCheckboxes) => {
//     const allCheckboxNames = [];
//     const allMultiCheckboxNames = [];

//     if ( checkedCheckboxes.length ) {
//         checkedCheckboxes.forEach( checkbox => {
//             let checkboxName = checkbox.name;

//             if ( !allCheckboxNames.includes(checkboxName) ) {
//                 allCheckboxNames.push(checkboxName);
//             } else {

//             }
//         });
//     }
// }

/**
 * Loop through all form fields excluding hidden inputs, adding new key/value pairs to the FormData object
 * that tell whether or not the field is required and gives the field type
 * Note: Validating through the front end is not always reliable. Anyone with knowledge of forms can inspect the
 * code and remove a required attribute/etc. Always handle validation server side
 * 
 * @param {FormData Object} formDataObject a FormData object
 * @param {Element} form the form
 */
const setAdditionalFormData = (formDataObject, form) => {
    if ( form && formDataObject ) {
        const formFields = form.querySelectorAll('input, textarea, select');

        if ( formFields.length > 0 ) {
            formFields.forEach( field => {
                let fieldName = field.name;
                let fieldType = field.type;
                let fieldRequired = 'false';

                // if the required attribute is present or a data attribute named required is present, the field is required
                if ( field.required ) {
                    fieldRequired = 'true';
                } else if ( field.dataset.required ) {
                    fieldRequired = 'true';
                }

                formDataObject.set(`${fieldName}_form-handler-type`, `${fieldType}`);
                formDataObject.set(`${fieldName}_form-handler-required`, fieldRequired);
                // formDataObject.set(`${fieldName}`, `${}`);
            });
        }
    }
}

const resetForm = (returnData, formFields, formName, formSessionStorage) => {
    // Completely successful submission where all data has passed validation
    if ( returnData['error'] == false && !('recommend_address' in returnData) ) {
        // Reset our amount of form submissions to 1 so we can recommend an address if possible
        formSessionStorage.setItem(formName, 1);

        if ( formFields.length > 0 ) {
            formFields.forEach( field => {

                // Reset all fields
                switch ( field.type ) {
                    case 'text':
                    case 'tel':
                    case 'email':
                    case 'textarea':
                        field.value = '';
                        break;
                    case 'select':
                    case 'select-one':
                        field.selectedIndex = 0;
                        break;
                    case 'checkbox':
                    case 'radio':
                        field.checked = false;
                        break;
                }
            });
        }
    }
}

const setFormMessage = (message) => {
    const formMessage = document.querySelector('.ei-form__message');

    if ( formMessage ) {
        formMessage.innerText = message;
    }
}

const resetFormFieldState = (formFields, formName) => {

    let formFieldErrorClass = 'ei-form__field--error';

    if ( formFields.length > 0 ) {
        formFields.forEach( field => {
            const fieldName = field.name;

            if ( fieldName ) {
                const fieldErrorLabel = $(`form[name="${formName}"] label[for="${fieldName}"][class*="ei-form__label-error"]`);

                if ( fieldErrorLabel ) {
                    $(fieldErrorLabel).fadeOut('slow');
                } 
            }

            if ( field.classList.contains(formFieldErrorClass) ) {
                field.classList.remove(formFieldErrorClass);
            }
        });
    }
}

export default handleFormSubmissions;