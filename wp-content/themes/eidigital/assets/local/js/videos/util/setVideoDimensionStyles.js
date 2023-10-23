/**
 * Accepts a video or iframe element and its width and height will be set by javascript so it can be full screen. Meant to set the video as a background video
 * 
 * @param {HTMLElement} video The video element (or iframe if the video is being pulled from vimeo or youtube) that will have styling applied to make it a background video
 * @param {Number} aspectRatio The aspect ratio the video should have. By default its 16:9 for desktop and 9:16 for mobile
 */
const setVideoDimensionStyles = ( video, aspectRatio = 0.5625 ) => {
    if ( video && typeof aspectRatio == 'number' ) {

        const browserWidth = document.documentElement.clientWidth;

        if ( browserWidth < 768 ) {
            video.style.width = `${browserWidth}px`;
            video.style.height = `${browserWidth / aspectRatio}px`;
        } else {
            video.style.width = `${browserWidth}px`;
            video.style.height = `${browserWidth * aspectRatio}px`;
        }
    }
};

export default setVideoDimensionStyles;