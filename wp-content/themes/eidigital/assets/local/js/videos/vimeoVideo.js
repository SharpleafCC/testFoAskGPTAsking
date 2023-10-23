import Player from '@vimeo/player';
import setVideoDimensionStyles from './util/setVideoDimensionStyles';
import handleBackgroundVideoBrowserResizing from './util/handleBackgroundVideoBrowserResizing';

/**
 * Looks for vimeo videos on the page via the classes `.video-component.video-component--vimeo` and uses the vimeo JS library to initialize the video. Then sets the video as a background video if necessary
 */
const vimeoVideo = () => {
    const allVimeoElements = document.querySelectorAll('.video-component.video-component--vimeo');

    if ( allVimeoElements.length < 1 ) return;
    
    allVimeoElements.forEach(vimeoPlayer => {
        const elementId = vimeoPlayer.id;
        const vimeoSettingsJsonString = vimeoPlayer.dataset.vimeoSettings;
        let vimeoSettings = {};

        if ( vimeoSettingsJsonString ) {
            vimeoSettings = JSON.parse(vimeoSettingsJsonString);
        }

        if ( 'height' in vimeoSettings && vimeoSettings.height == null ) {
            delete vimeoSettings.height;
        }

        if ( 'width' in vimeoSettings && vimeoSettings.width == null ) {
            delete vimeoSettings.width;
        }

        try {
            // Check if the vimeoSettings object has items in it, one of them key/value pairs is `id`, and id is not empty
            if ( Object.keys(vimeoSettings).length > 0 && 'id' in vimeoSettings && vimeoSettings.id != '' ) {

                // Set up the vimeo player
                const player = new Player(elementId, vimeoSettings);

                // When the vimeo player is ready (aka the iframe is created and injected into our vimeo div), get the iframe and set it as a background video if needed
                player.ready().then(() => {
                    const playerIframe = vimeoPlayer.querySelector('iframe');

                    if ( vimeoPlayer.classList.contains('video-component--dynamic-background-video') ) {
                        playerIframe.classList.add('video-component');

                        setVideoDimensionStyles(playerIframe);
                        handleBackgroundVideoBrowserResizing(playerIframe);
                    }
                });
            } else {
                // `id` is required to create the vimeo iframe. throw an error if that key/value pair is not found in our settings variable
                throw new Error(`Vimeo settings expects the ID of a video. Check the JSON string on your vimeo div to ensure the id key is there and is set to a Vimeo video's ID.`);
            }
        } catch (e) {
            console.error(e);
        }
    });
};

export default vimeoVideo;