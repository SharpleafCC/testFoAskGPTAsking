<?php
// A collection of methods that help the BackgroundImage ACF Group create useable data in our modules
if ( !class_exists('BackgroundImage') ) {
    class BackgroundImage {
        // Variables containing the ACF names of the fields in this module. Not necessarily needed to pull data
        private $backgroundImageMobileAcfName = 'background_image_mobile';
        private $backgroundImageDesktopAcfName = 'background_image_desktop';
        private $backgroundImageSettingsMobileAcfName = 'background_image_settings_mobile';
        private $backgroundImageSettingsDesktopAcfName = 'background_image_settings_desktop';

        public $backgroundImageMobile = '';
        public $backgroundImageDesktop = '';
        public $backgroundImageSettingMobile = '';
        public $backgroundImageSettingsDesktop = '';

        /**
         * This method accepts the main background image ACF fields and will return all data as CSS variables to be used in a module.
         * 
         * @param array $backgroundImageMobile - Desktop image array taken from an ACF field
         * @param array $backgroundImageDesktop - Desktop image array taken from an ACF field
         * @param array $backgroundImageMobileSettings - Array of settings for the mobile image containing background_color, background_size, background_repeat, and background_position
         * @param array $backgroundImageDesktopSettings - Array of settings for the desktop image containing background_color, background_size, background_repeat, and background_position
         * @param boolean $returnAsString - If set to true (default), the method will return the full string containing all variables. If false, an array is returned which allows for replacing values if needed
         * 
         * @return mixed String of variables if last parameter is true, array of each variable in its own key/value pair if last parameter is false
         */
        public static function createBackgroundImageVariables( $backgroundImageMobile, $backgroundImageDesktop, $backgroundImageMobileSettings, $backgroundImageDesktopSettings, $returnAsString = true ) {
            $returnData = [];

            if ( is_array($backgroundImageMobile) && !empty($backgroundImageMobile) ) {
                if ( array_key_exists('url', $backgroundImageMobile) ) {
                    $returnData['mobile_url'] = '--backgroundImageMobile:url(' . $backgroundImageMobile['url'] . ');';
                }
            }

            if ( is_array($backgroundImageDesktop) && !empty($backgroundImageDesktop) ) {
                if ( array_key_exists('url', $backgroundImageDesktop) ) {
                    $returnData['desktop_url'] = '--backgroundImageDesktop:url(' . $backgroundImageDesktop['url'] . ');';
                }
            }

            // Mobile variables
            if ( is_array($backgroundImageMobileSettings) ) {
                // Background Color
                if ( array_key_exists('background_color', $backgroundImageMobileSettings) ) {
                    $returnData['mobile_bg_color'] = '--backgroundColorMobile:' . $backgroundImageMobileSettings['background_color'] . ';';
                }

                // Background Size
                if ( array_key_exists('background_size', $backgroundImageMobileSettings) ) {
                    $returnData['mobile_bg_size'] = '--backgroundSizeMobile:' . $backgroundImageMobileSettings['background_size'] . ';';
                }

                // Background Repeat
                if ( array_key_exists('background_repeat', $backgroundImageMobileSettings) ) {
                    $returnData['mobile_bg_repeat'] = '--backgroundRepeatMobile:' . $backgroundImageMobileSettings['background_repeat'] . ';';
                }

                // Background Position
                if ( array_key_exists('background_position', $backgroundImageMobileSettings) ) {
                    $mobilePositionValue = ($backgroundImageMobileSettings['background_position'] !== 'custom') ? $backgroundImageMobileSettings['background_position'] : $backgroundImageMobileSettings['background_custom_position'];

                    $returnData['mobile_bg_position'] = '--backgroundPositionMobile:' . $mobilePositionValue . ';';
                }
            }

            // Desktop variables
            if ( is_array($backgroundImageDesktopSettings) ) {
                // Background Color
                if ( array_key_exists('background_color', $backgroundImageDesktopSettings) ) {
                    $returnData['desktop_bg_color'] = '--backgroundColorDesktop:' . $backgroundImageDesktopSettings['background_color'] . ';';
                }

                // Background Size
                if ( array_key_exists('background_size', $backgroundImageDesktopSettings) ) {
                    $returnData['desktop_bg_size'] = '--backgroundSizeDesktop:' . $backgroundImageDesktopSettings['background_size'] . ';';
                }

                // Background Repeat
                if ( array_key_exists('background_repeat', $backgroundImageDesktopSettings) ) {
                    $returnData['desktop_bg_repeat'] = '--backgroundRepeatDesktop:' . $backgroundImageDesktopSettings['background_repeat'] . ';';
                }

                // Background Position
                if ( array_key_exists('background_position', $backgroundImageDesktopSettings) ) {
                    $desktopPositionValue = ($backgroundImageDesktopSettings['background_position'] !== 'custom') ? $backgroundImageDesktopSettings['background_position'] : $backgroundImageDesktopSettings['background_custom_position'];

                    $returnData['desktop_bg_position'] = '--backgroundPositionDesktop:' . $desktopPositionValue . ';';
                }
            }

            if ( $returnAsString === true ) {
                return implode('', $returnData);
            } else {
                return $returnData;
            }
        }
    }
}
?>