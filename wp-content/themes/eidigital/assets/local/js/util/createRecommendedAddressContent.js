const createRecommendedAddressContent = (currentAddressData = {}, recommendedAddressData = {}) => {
    return `
        <div class="recommended-address">
            <div class="recommended-address__content">
                ${createAddressMarkup('current-address', currentAddressData)}
                ${createAddressMarkup('recommended-address', recommendedAddressData)}
            </div>
        </div>
    `;
};

/**
 * Create the HTML markup for the popup content
 * 
 * @param {String} addressType Either recommended-address or current-address
 * @param {Object} data Data containing address1, address2, city, state, zip
 */
const createAddressMarkup = (addressType, data = {}) => {

    const classPrefix = `${addressType}`
    let addressHtml = '';
    let addressTitle =  `<p class="address-content__title">${ addressType === 'recommended-address' ? 'Recommended Address' : 'Current Address' }</p>`;

    if ( Object.keys(data).length > 0 ) {
        addressHtml += `<div class="${classPrefix} address-content">${addressTitle}<div class="address-content__wrap" data-address-type="${addressType}">`;
        for ( const property in data ) {
            if ( data[property] ) {
                addressHtml += `<p class="${classPrefix}__${property} address-content__item" data-address-type="${addressType}">${data[property]}</p>`;
            }
        }
        addressHtml += `</div></div>`;
    }

    return addressHtml;
}

export default createRecommendedAddressContent;