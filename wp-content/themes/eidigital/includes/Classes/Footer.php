<?php
class Footer {

    /**
     * Grabs all links inside the social tab in the theme settings and returns the a tag HTML for those links
     * 
     * @return string
     */
    public function socialMediaLinkHtml() {
        $socialMediaLinksHTML = '';

        $socialLinks = get_field('social', 'option');
        
        if ( !empty($socialLinks) ) {
            foreach ( $socialLinks as $link ) {
                $targetValue = $link['link']['target'] ? '_blank' : '_self';

                $socialMediaPlatform = strtolower($link['link']['title']);

                $socialMediaURL = $link['link']['url'];

                $socialMediaLinksHTML .= "<a href=\"$socialMediaURL\" target=\"$targetValue\" rel=\"noreferrer\" class=\"footer__link social-links__link social-links__link--$socialMediaPlatform \">$socialMediaPlatform</a>"; 
            }

            return $socialMediaLinksHTML;
        }

        return '';
    }

    /**
     * @return array - Contains the keys column_1 and column_2 and their respective menu a tag HTML
     */
    public function footerColumnLinks1And2HTML() {

        $footerColumnHtml = [
            'column_1' => '',
            'column_2' => ''
        ];

        $footerColumn1Items =  wp_get_nav_menu_items('Footer Menu 1');
        $footerColumn2Items =  wp_get_nav_menu_items('Footer Menu 2');

        $footerColumnHtml['column_1'] = $this->createColumn1And2HTML( $footerColumn1Items );
        $footerColumnHtml['column_2'] = $this->createColumn1And2HTML( $footerColumn2Items, true );

        return $footerColumnHtml;
    }

    /**
     * Takes the menu items for column 1 and 2 and returns **JUST** the a tag html for each menu
     * 
     * @param object $menuItems - The data returned when using wp_get_nav_menu_items()
     * 
     * @return array 
     */
    public function createColumn1And2HTML( $menuItems, $containsCookieConsent = false ) {
        $footerColumn1HTML = '';

        if ( !empty($menuItems) ) {

            foreach ( $menuItems as $item ) {

                $link = $item->url;
                $title = $item->title;
                $classes = !empty($item->classes) ? implode(' ', $item->classes) : '';
                $target = $item->target ? '_blank' : '_self';

                $footerColumn1HTML .= '<a class="footer__link ' . $classes . '" target="' . $target . '" rel="noreferrer" href="' . $link . '">' . $title . '</a>';
            }

            if ( $containsCookieConsent ) {
                $footerColumn1HTML .= '<div class="footer__link footer__cookie-consent"><div id="teconsent"></div></div>';
            }
        }

        return $footerColumn1HTML;
    }
}
?>