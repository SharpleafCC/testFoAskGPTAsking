import setVideoDimensionStyles from "./setVideoDimensionStyles";

/**
 * Resizes an iframe or video during the resize event based on its aspect ratio
 * 
 * @param {HTMLElement} video The iframe video that needs to be resized during a resize event
 * @param {Number} aspectRatio The aspect ratio at which the video should be resized. Default is 16:9 and 9:16 on desktop and mobile respectively.
 */
const handleBackgroundVideoBrowserResizing = ( video, aspectRatio = 0.5625 ) => {
    window.addEventListener('resize', (e) => {
        setVideoDimensionStyles(video, aspectRatio);
    });
};

export default handleBackgroundVideoBrowserResizing;