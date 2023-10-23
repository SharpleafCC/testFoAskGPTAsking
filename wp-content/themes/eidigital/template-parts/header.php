<?php

/**
 * The template for displaying the site header.
 *
 * @package EiDigital
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if ( $args ) {
	extract( $args );
}

$classes = ['header'];

if ( $header['is_sticky'] ) {
	$classes[] = 'is-fixed';
}

if ( $header['is_fixed'] ) {
	$classes[] = 'is-sticky';
}

$styles = [];

if ( isset( $header['background_color'] ) && ! empty( $header['background_color'] ) ) {
	$styles[] = 'background-color:' . $header['background_color'];
}

$data_sticky = '';
if ( isset( $header['sticky_background_color'] ) && ! empty( $header['sticky_background_color'] ) ) {
	$data_sticky = $header['sticky_background_color'];
}

$GLOBALS['section_counter'] = 0; 
$menu_items = wp_get_nav_menu_items('Header');
$menu_lists = [];

$headerNavigation = new HeaderNavigation;
?>
<?php get_template_part('template-parts/components/announcement', null); ?>
<header id="header" class="fixed top-0 left-0 w-full z-30 h-auto transition-all duration-[750ms] [&.is-fixed]:fixed [&.is-sticky]:absolute  <?php echo implode(' ', $classes); ?>  " style="<?php echo implode(' ', $styles); ?>" data-top-color="<?php echo $header['background_color']; ?>" data-sticky-color="<?php echo $data_sticky; ?>">
	<div class="header__content grid grid-cols-header-navigation-mobile lg:grid-cols-header-navigation-desktop grid-rows-[1fr] gap-x-5 items-center m-auto gap-y-0">
		<div class="logo relative h-full z-30">
			<?php
			$custom_logo_id = get_theme_mod('custom_logo');
			$logo = wp_get_attachment_image_src($custom_logo_id, 'full');
			?>
			<a class="header__home-page-link align-bottom leading-none" href="<?php echo esc_url(home_url('/')); ?>" title="<?php esc_attr_e(' Home', bloginfo('name')); ?>" rel="home">
				<?php if (has_custom_logo()) : ?>
					<img class="header-logo block max-w-[100px] w-[75px] " src="<?php echo esc_url($logo[0]); ?>" alt="<?php bloginfo('name'); ?>">
				<?php else : ?>
					<h2><?php bloginfo('name'); ?></h2>
				<?php endif; ?>
			</a>
		</div>
		<nav class="header__nav header__nav--desktop flex justify-end pr-[15px] lg:justify-center lg:pr-0 items-center">
			<div id="mobile-menu" class="block overflow-hidden z-30 relative ml-0 w-[35px] h-[25px] cursor-pointer transition-all duration-500 rotate-0 lg:hidden hide-lg-up">
				<div class="menu-icon-wrapper">
					<span class="mobile-menu__line block absolute h-[2px] w-[34px] left-0 opacity-100 rotate-0 transition-all duration-[250ms] rounded-none bg-white top-1/2 -translate-y-[10px]"></span>
					<span class="mobile-menu__line block absolute h-[2px] w-[34px] left-0 opacity-100 rotate-0 transition-all duration-[250ms] rounded-none bg-white top-1/2 -translate-y-1/2"></span>
					<span class="mobile-menu__line block absolute h-[2px] w-[34px] left-0 opacity-100 rotate-0 transition-all duration-[250ms] rounded-none bg-white top-1/2 translate-y-[8px]"></span>
				</div>
			</div>
			<?= $headerNavigation->createHeaderNavigationHtml( false ); ?>
		</nav>
		
		<div class="menu-overlay fixed top-0 left-0 right-auto bottom-0 w-0">
  			<div class="menu-content w-full h-screen overflow-x-hidden relative">
				<div class="mobile-menu-panel overflow-y-auto lg:hidden h-screen w-full order-3  block bg-black">
					<nav class="header__nav header__nav--mobile flex flex-col items-center justify-end">
						<?= $headerNavigation->createHeaderNavigationHtml( true ); ?>
						<?php get_template_part('template-parts/components/social', null); ?>
					</nav>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="header-separator"></div>
<?php get_template_part('template-parts/components/popup', null); ?>