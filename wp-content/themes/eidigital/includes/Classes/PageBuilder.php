<?php
class PageBuilder
{

	private $pageBuilderAcfFieldName = 'page-builder';

	public function renderModules($postId = null, $wrapper_start = null, $wrapper_end = null)
	{

		if ($postId === null) {
			$postId = get_queried_object_id();
		}

		if ($postId) {
			$pageBuilder = get_field($this->pageBuilderAcfFieldName, $postId);

			if (!empty($pageBuilder) && is_array($pageBuilder)) {
				foreach ($pageBuilder as $module) {
					$GLOBALS['section_counter']++;
					// var_dump($module);
					if ($wrapper_start) :
						echo $wrapper_start;
					endif;
					get_template_part('template-parts/page-builder/' . $module['acf_fc_layout'], '', ['data' => $module]);
					if ($wrapper_end) :
						echo $wrapper_end;
					endif;
				}
			}
		}
	}

	/**
	 * Use this to place dynamic background images on an HTML element
	 *
	 * @param string $mobileImageUrl - the URL of the mobile image
	 * @param string $desktopImageUrl - the URL of the desktop image
	 * @param string $breakpoint - the breakpoint at which the background image should switch from mobile to desktop. ex: min-width: 60em
	 * @param string $cssSelector - the element that should have the styling applied to it. ex: .hero-section
	 */
	public static function createBackgroundImageString($mobileImageUrl, $desktopImageUrl, $breakpoint, $cssSelector)
	{
		$style = '';

		if (!empty($mobileImageUrl) && !empty($desktopImageUrl) && !empty($breakpoint) && !empty($cssSelector)) {
			$style = "<style> $cssSelector { background-image: url($mobileImageUrl); } @media all and ($breakpoint) { $cssSelector { background-image: url($desktopImageUrl); } }</style>";
		}

		return $style;
	}

	/**
	* Sets all the classes from the admin ACF fields on the Section tag
	*/
	public static function section_styles($data)
	{
		$styles  = $data['flex_direction'];
		$styles .= ' ' . $data['align_items']['small']['align_alignment'];
		$styles .= ' md:' . $data['align_items']['medium']['align_alignment'];
		$styles .= ' lg:' . $data['align_items']['large']['align_alignment'];
		$styles .= ' ' . $data['justify_content'];
		$styles .= ' ' . $data['margin']['small'];
		$styles .= ' md:' . $data['margin']['medium'];
		$styles .= ' lg:' . $data['margin']['large'];
		$styles .= ' ' . $data['padding_top']['small'];
		$styles .= ' md:' . $data['padding_top']['medium'];
		$styles .= ' lg:' . $data['padding_top']['large'];
		$styles .= ' ' . $data['padding_bottom']['small'];
		$styles .= ' md:' . $data['padding_bottom']['medium'];
		$styles .= ' lg:' . $data['padding_bottom']['large'];
		$styles .= ' ' . $data['section_classes'];
		return $styles;
	}

	/**
	* Set content width on items
	*/
	public static function content_width($data)
	{	
		$styles  = $data['content_width']['small'];
		$styles .= ' md:' . $data['content_width']['medium'];
		$styles .= ' lg:' . $data['content_width']['large'];
		return $styles;
	}

	/**
	* Set text alignment on items
	*/
	public static function text_alignment($data)
	{	
		$styles  = $data['text_alignment']['small'];
		$styles .= ' md:' . $data['text_alignment']['medium'];
		$styles .= ' lg:' . $data['text_alignment']['large'];
		return $styles;
	}

}
