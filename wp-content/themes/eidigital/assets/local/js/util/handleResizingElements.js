/**
 * Accepts a NodeList of similar elements (should have the same HTML markup) but may have an element with varying
 * height due to text content. Loop through all elements finding the largest height and set that height onto every
 * element passed to the function
 *
 * @param {NodeList} elements elements
 */
const handleResizingElements = (elements) => {
	let tallestHeight = 0;

	if (window.innerWidth < 768) {
		elements.forEach(element => element.style.minHeight = `auto`);
		return
	}

	elements.forEach(element => {
		element.style.minHeight = 'auto';
		if (element.clientHeight > tallestHeight) {
			tallestHeight = element.clientHeight;
		}
	});

	if (tallestHeight != 0) {
		elements.forEach(element => element.style.minHeight = `${tallestHeight}px`);
	}
};

export default handleResizingElements;
