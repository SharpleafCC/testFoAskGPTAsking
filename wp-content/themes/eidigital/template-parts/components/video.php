<?php
$data = $args['data'];

if ( $data['video_type'] === 'self-hosted' ) {
    if ( !empty($data['video_mobile']) ) { ?>
        <video
            <?= !empty($data['mobile_video_settings']['id']) ? 'id="' . $data['mobile_video_settings']['id'] . '"' : '' ?>
            src="<?= $data['video_mobile']['url'] ?>" 
            class="video-component video-component--mobile block lg:hidden <?= $data['mobile_video_settings']['set_as_background_video'] ? 'absolute top-1/2 left-1/2 h-full w-full object-cover -translate-x-1/2 -translate-y-1/2' : '' ?> <?= !empty($data['mobile_video_settings']['additional_classes']) ? $data['mobile_video_settings']['additional_classes'] : '' ?>"
            <?= $data['mobile_video_settings']['autoplay'] ? 'autoplay' : '' ?>
            <?= $data['mobile_video_settings']['controls'] ? 'controls' : '' ?>
            <?= $data['mobile_video_settings']['loop'] ? 'loop' : '' ?>
            <?= $data['mobile_video_settings']['muted'] ? 'muted' : '' ?>
            <?= $data['mobile_video_settings']['playsinline'] ? 'playsinline' : '' ?>
            height="<?= $data['mobile_video_settings']['height'] ?>"
            width="<?= $data['mobile_video_settings']['width'] ?>"
            type="<?= $data['video_mobile']['mime_type'] ?>"
        ></video><?php
    }

    if ( !empty($data['video_desktop']) ) { ?>
        <video
            <?= !empty($data['desktop_video_settings']['id']) ? 'id="' . $data['desktop_video_settings']['id'] . '"' : '' ?>
            src="<?= $data['video_desktop']['url'] ?>" 
            class="video-component video-component--desktop hidden lg:block <?= $data['desktop_video_settings']['set_as_background_video'] ? 'absolute top-1/2 left-1/2 h-full w-full object-cover -translate-x-1/2 -translate-y-1/2' : '' ?> <?= !empty($data['desktop_video_settings']['additional_classes']) ? $data['desktop_video_settings']['additional_classes'] : '' ?>"
            <?= ($data['desktop_video_settings']['autoplay'] == true) ? 'autoplay' : '' ?>
            <?= $data['desktop_video_settings']['controls'] ? 'controls' : '' ?>
            <?= $data['desktop_video_settings']['loop'] ? 'loop' : '' ?>
            <?= $data['desktop_video_settings']['muted'] ? 'muted' : '' ?>
            <?= $data['desktop_video_settings']['playsinline'] ? 'playsinline' : '' ?>
            height="<?= $data['desktop_video_settings']['height']?>"
            width="<?= $data['desktop_video_settings']['width']?>"
            type="<?= $data['video_desktop']['mime_type'] ?>"
        ></video><?php
    }
} else if ( $data['video_type'] === 'vimeo' ) {
    if ( !empty($data['vimeo_video_settings']['id']) ) { 
        $vimeoSettings = '';
        $excludeSettings = ['html_id', 'set_as_background_video'];

        // Filter our ACF group vimeo_video_settings (all keys are specific vimeo settings while values are usually true/false) with only allowed setting names
        // @see https://developer.vimeo.com/player/sdk/embed
        $vimeoSettings = array_filter($data['vimeo_video_settings'], function( $arrayValue ) use ($excludeSettings) {
            return ! in_array($arrayValue, $excludeSettings);
        }, ARRAY_FILTER_USE_KEY);
        
        ?>
        <div 
            id="<?= $data['vimeo_video_settings']['html_id'] ?>" 
            class="video-component video-component--vimeo <?= ($data['vimeo_video_settings']['set_as_background_video'] == true) ? 'video-component--dynamic-background-video' : '' ?> <?= $data['vimeo_video_settings']['additional_classes'] ?>" 
            data-vimeo-settings='<?= json_encode($vimeoSettings) ?>'>
        </div>
    <?php
    }
} else if ( $data['video_type'] === 'youtube') {
    if ( !empty($data['youtube_video_settings']['videoId']) ) {
        $youtubeVideoMainSettings = ['videoId', 'height', 'width'];
        $youtubeVideoPlayerVars = ['autoplay', 'controls', 'loop', 'playsinline', 'mute'];

        $youtubeSettings = array_filter($data['youtube_video_settings'], function( $settingsName ) use ($youtubeVideoMainSettings) {
            return in_array($settingsName, $youtubeVideoMainSettings);
        }, ARRAY_FILTER_USE_KEY);

        $youtubePlayerVarsSettings = array_filter($data['youtube_video_settings'], function( $settingsName ) use ($youtubeVideoPlayerVars) {
            return in_array($settingsName, $youtubeVideoPlayerVars);
        }, ARRAY_FILTER_USE_KEY);

        $youtubePlayerVarsSettings['enablejsapi'] = true;
    ?>
    <div 
        id="<?= $data['youtube_video_settings']['html_id'] ?>" 
        class="video-component video-component--youtube <?= ($data['youtube_video_settings']['set_as_background_video'] == true) ? 'video-component--dynamic-background-video' : '' ?> <?= $data['youtube_video_settings']['additional_classes'] ?>"
        data-youtube-settings='<?= json_encode($youtubeSettings) ?>'
        data-youtube-playervars-settings='<?= json_encode($youtubePlayerVarsSettings) ?>'
        >
    </div>
    <?php
    }
}
?>