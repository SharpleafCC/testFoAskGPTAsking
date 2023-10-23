<?php
class HeaderNavigation {

    private $headerNavigationMenuTitle = 'Header';
    private $headerMenuItems = [];
    private $headerSubMenuItems = [];
    private $headerTopLevelMenuItems = [];

    public function __construct() {
        $this->headerMenuItems = wp_get_nav_menu_items( $this->headerNavigationMenuTitle );
        $this->headerSubMenuItems = $this->setSubOrTopLevelMenuItemsArray( $this->headerMenuItems, false );
        $this->headerTopLevelMenuItems = $this->setSubOrTopLevelMenuItemsArray( $this->headerMenuItems, true );
    }

    /**
     * @param array $menuList - the Menu that we will separate based on top/sub level menu items
     * @param boolean $isTopLevel - Whether we want top level items. If true, we will only return top level items. If false, we will return sub menu items
     */
    private function setSubOrTopLevelMenuItemsArray( $menuList, $isTopLevel ) {
        $menu = [];

        if ( !empty($menuList) && is_iterable($menuList) ) {
            $menu = array_filter( $menuList, function( $menuItem ) use ( $isTopLevel ){
                if ( $isTopLevel ) {
                    return $menuItem->menu_item_parent == 0;
                } else {
                    return $menuItem->menu_item_parent != 0;
                }
            });
        }

        return array_values($menu);
    }

    public function getHeaderTopLevelMenuItems() {
        return $this->headerTopLevelMenuItems;
    }

    public function getHeaderSubMenuItems() {
        return $this->headerSubMenuItems;
    }

    /**
     * @param boolean $isMobile - Whether we are expecting the HTML for the mobile menu or the desktop menu
     */
    public function createHeaderNavigationHtml( $isMobile ) {
        global $wp;
        
        // Gets current url
        $currentUrl =  home_url($wp->request) . '/';

        if ( empty($this->headerTopLevelMenuItems) ) {
            return '';
        }

        $mainListClass = ($isMobile === true) ? 'flex-col lg:hidden w-full pt-[100px]' : 'hidden lg:flex flex-row ';

        // flex flex-col items-stretch lg:items-center justify-center lg:flex-row
        $navigationHtml = '<ul class="main-nav header__list m-0 list-none ' . $mainListClass . '">' ."\n";

        foreach ( $this->headerTopLevelMenuItems as $item ) {
            $menuId = $item->ID;
            $currentLink = $item->url;
            $activeMenuClass = '';
            $title = $item->title;           

            // If the current url matches the menu link set active class
			if ($currentUrl == $currentLink) {
				$activeMenuClass = 'current-menu-item ';
            }

            $subMenuItems = $this->createSubMenuHtml( $menuId, $isMobile );
            $dropdownIcon = '';

            if ( !empty($subMenuItems) ) {
                $activeMenuClass .= 'header__item--has-sub-menu ';
                $dropdownIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 stroke-green inline-block ml-auto m-0 lg:ml-2 -mt-0.5 transition-all duration-300">
                <path stroke-linecap="square" stroke-linejoin="square" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg>';
            }

            $mainListItemClass = '';
            $mainListLinkClass = '';

            // Mobile top level links need bottom borders and top level links need larger font sizes
            if ( $isMobile === true ) {
                $mainListItemClass = 'border-b-[1px] border-b-black-400 ';
                $mainListLinkClass = 'text-2xl block ';
            } else {
                $mainListLinkClass = 'text-sm inline ';
            }

            $navigationHtml .= '<li class="item header__item overflow-hidden lg:mr-[30px] lg:last-of-type:mr-0 desktop:mr-[80px] ' . $mainListItemClass . $activeMenuClass . esc_attr(implode(' ', $item->classes)) . '" data-id="' . $menuId . '" data-name="' . $item->post_name . '">' . "\n";
            $navigationHtml .= '<a class="header__item-link py-5 pl-5 lg:pl-0 text-left no-underline ' . $mainListLinkClass . '" href="' . $currentLink . '" target="' . $item->target . '"><div class="flex justify-between items-center"><span class="uppercase font-subtitle text-white">' . $title . '</span>' . $dropdownIcon . '</div></a>' . "\n";
            $navigationHtml .= $subMenuItems;
            $navigationHtml .= '</li>';
        }

        $navigationHtml .= '</ul>';

        return $navigationHtml;
    }

    public function createSubMenuHtml( $parentItemId, $isMobile ) {
        $subMenuHtml = '';

        if ( empty($this->headerSubMenuItems) ) {
            return '';
        }

        $subMenuItems = array_filter( $this->headerSubMenuItems, function( $item ) use ( $parentItemId ){
            return $item->menu_item_parent == $parentItemId;
        });

        if ( !empty($subMenuItems) ) {
            if ( $isMobile ) {
                $subMenuHtml .= '<div class="sub__menu-cover sub__menu-cover--mobile relative block overflow-hidden max-h-0 transition-all duration-300">' . "\n" . '<ul class="sub__menu relative pb-5 pl-0 visible opacity-100 top-0 left-[50%] w-full min-w-[150px] flex-col z-[4] -translate-x-2/4 transition-all duration-500">' . "\n";
            } else {
                $subMenuHtml .= '<ul class="header__submenu--desktop top-[100%] opacity-0 invisible -z-[1] absolute w-screen bg-black transition-all duration-500 list-none left-0 border-t-1 border-gray-700 -mt-1 ml-0 flex items-center justify-center flex-row flex-nowrap mx-auto p-5">' . "\n" . '';
            }

            foreach ($subMenuItems as $item) {
                $subMenuImage = get_field('sub_menu_navigation_item_image', $item->ID);

                // Mobile sub menu links need to be a bit larger
                $subMenuSpanClass = '';
                $subMenuListItemClass = '';

                if ( in_array('explore-all', $item->classes) ) {
                    $subMenuListItemClass = 'lg:hidden ';
                }

                if ( $isMobile === true ) {
                    $subMenuSpanClass = 'text-xl';
                }

                $subMenuHtml .= '<li class="item header__sub-item group overflow-hidden mr-5 m-0 w-full lg:max-w-[130px] ' . $subMenuListItemClass . esc_attr(implode(' ', $item->classes)) . '">' . "\n";
                $subMenuHtml .= '<a class="header__submenu-item-link no-underline flex flex-col pl-10 lg:pl-0 lg:py-0 text-left" href="' . $item->url . '" target="' . $item->target . '" class="title">';
            
                // Add the image (if present) to the sub menu item
                if ( $subMenuImage ) {
                    $subMenuHtml .= '<img class="hidden lg:inline-block w-full max-w-[125px] h-auto transition-all duration-300 group-hover:scale-105" src="' . $subMenuImage['url'] . '" alt="' . $subMenuImage['alt'] . '">';
                }

                $subMenuHtml .= '<span class="pt-2 text-sm font-subtitle text-gray-700 group-hover:text-green-300 uppercase no-underline ' . $subMenuSpanClass . '">' . $item->title . '</span></a>' . "\n";
                $subMenuHtml .= '</li>';
            }

            if ( !$isMobile ) {
                $subMenuHtml .= '' . "\n" . '</ul>' . "\n";
            } else {
                $subMenuHtml .= '</ul>' . "\n" . '</div>';
            }
        }

        return $subMenuHtml;
    }
}
?>