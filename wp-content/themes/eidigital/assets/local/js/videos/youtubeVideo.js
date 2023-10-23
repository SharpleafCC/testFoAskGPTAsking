/**
 * Checks the page for a specific element that needs to become a youtube iframe element. If that element is found on the page
 * the function will check to see if the youtube iframe API script has already been injected into the DOM. If it has not, create
 * the necessary function for the API and inject the script into the DOM to be loaded. Once loaded, the youtube iframe API will call
 * said necessary function and initialize iframes
 * 
 * @link https://developers.google.com/youtube/iframe_api_reference
 * 
 * @returns undefined
 */
const youtubeVideo = () => {
    const allYoutubeVideos = document.querySelectorAll('.video-component.video-component--youtube');

    if ( allYoutubeVideos.length < 1) return;

    const youtubeIframeApiScript = document.querySelector('script[src="https://www.youtube.com/iframe_api"]');

    // Only inject the youtube iframe API if it does not already exist
    if ( youtubeIframeApiScript == undefined || ! window.YT ) {
        const tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        const firstScriptTag = document.getElementsByTagName('script')[0];

        // Declare the onYoutubeIframeAPIReady function in the window before we insert the script so it can be called after its loaded
        window.onYoutubeIframeAPIReady = function( callback ) {
            if ( typeof callback === 'function' ) {
                callback();
            }
        };

        window.basethemeYoutubeVideoIframes = {};

        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    // Wait until the entire DOM is ready and loaded (aka Youtube API is also fully downloaded) before calling onYoutubeIframeAPIReady
    window.addEventListener('load', () => {
        // Call the function with our callback
        window.onYoutubeIframeAPIReady( () => {

            allYoutubeVideos.forEach(youtubeVideo => {
                const youtubePlayerSettings = JSON.parse( youtubeVideo.dataset.youtubeSettings || '{}' );
                const youtubePlayerVarSettings = JSON.parse( youtubeVideo.dataset.youtubePlayervarsSettings || '{}' );

                youtubePlayerSettings.events = {};

                // Change true/false to 0 and 1 because youtube???
                for ( const key in youtubePlayerVarSettings ) {
                    if ( youtubePlayerVarSettings[key] == false) {
                        youtubePlayerVarSettings[key] = 0;
                    } else {
                        youtubePlayerVarSettings[key] = 1;
                    }
                }

                // Set origin for IFrame API Support
                // @see https://developers.google.com/youtube/player_parameters#origin
                youtubePlayerVarSettings.origin = `${window.location.origin}`;

                // If the field to set the video as a background is true, set specific player vars so it can play automatically
                if ( youtubeVideo.classList.contains('video-component--dynamic-background-video') ) {
                    youtubePlayerVarSettings.enablejsapi = 1;
                    youtubePlayerVarSettings.autoplay = 1;
                    youtubePlayerVarSettings.mute = 1;
                    youtubePlayerVarSettings.controls = 0;
                }
    
                youtubePlayerSettings.playerVars = youtubePlayerVarSettings;
    
                // The youtube player object becomes available in the window inside the object `basethemeYoutubeVideoIframes` where the key is the videoId of the youtube video
                window.basethemeYoutubeVideoIframes[`${youtubePlayerSettings.videoId}`] = new window.YT.Player(`${youtubeVideo.id}`, youtubePlayerSettings);
            });
        });
    });
};

export default youtubeVideo;