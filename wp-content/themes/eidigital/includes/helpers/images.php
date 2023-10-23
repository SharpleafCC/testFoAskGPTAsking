<?php 
/**
 * Returns an attachment as an acf image
 *
 * Only specify the fields you'll use (reduces query count)
 *
 * To get all fields (and most image sizes):
 * [
 *	'url',
 *	'title',
 *	'alt',
 *	'caption',
 *	'sizes' => ['thumbnail', 'small', 'medium', 'large'],
 * ]
 */
function get_attachment_as_acf_image(? int $attachment_id, ? array $fields = []) {
	$acf_image = [];

	if (in_array('id', $fields)) {
		$acf_image['id'] = $attachment_id;
	}

	if (in_array('url', $fields)) {
		$image_parts = wp_get_attachment_image_src($attachment_id, false, false);
		list($src, $width, $height) = $image_parts;
		if (isset($src)) {
			$acf_image['url'] = $src;
		}
	}

	if (in_array('title', $fields)) {
		$acf_image['title'] = get_the_title($attachment_id);
	}

	if (in_array('alt', $fields)) {
		$acf_image['alt'] = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
	}

	if (in_array('caption', $fields)) {
		$acf_image['caption'] = wp_get_attachment_caption($attachment_id);
	}

	// Get image urls for specified sizes
	if (is_array($fields['sizes'])) {
		$acf_image['sizes'] = [];

		foreach ($fields['sizes'] as $size_id) {

			if ($size_id) {
				$image_parts = wp_get_attachment_image_src($attachment_id, $size_id, false);
				list($src, $width, $height) = $image_parts;
				if (isset($src)) {
					$acf_image['sizes'][$size_id] = $src;
				}
			}

		}
	}

	return $acf_image;
}

function get_image_sources($image_desktop, $image_mobile){
	return(
		'
		<source media="(max-width: '.$image_mobile['sizes']['medium-width'].'px)" srcset="'.esc_url($image_mobile['sizes']['medium']).'" />
		<source media="(max-width: '.$image_mobile['sizes']['medium_large-width'].'px)" srcset="'.esc_url($image_mobile['sizes']['medium_large']).'" />
		<source media="(max-width: '.$image_desktop['sizes']['960w-width'].'px)" srcset="'.esc_url($image_desktop['sizes']['960w']).'" />
		<source media="(max-width: '.$image_desktop['sizes']['full-width-width'].'px)" srcset="'.esc_url($image_desktop['sizes']['full-width']).'" />
		'
	);
}

function get_background_image_sources($image_desktop, $image_mobile, $element_id) {
    return (
        '<style>
            #' . $element_id . ' {
                background-image: url("' . esc_url($image_mobile['sizes']['medium']) . '");
            }
            @media (min-width: ' . ($image_desktop['sizes']['medium-width']  + 1) . 'px) {
                #' . $element_id . ' {
                    background-image: url("' . esc_url($image_mobile['sizes']['medium_large']) . '");
                }
            }
            @media (min-width: ' . ($image_desktop['sizes']['medium_large-width'] + 1) . 'px) {
                #' . $element_id . ' {
                    background-image: url("' . esc_url($image_desktop['sizes']['960w']) . '");
                }
            }
            @media (min-width: ' . ($image_desktop['sizes']['960w-width'] + 1) . 'px) {
                #' . $element_id . ' {
                    background-image: url("' . esc_url($image_desktop['sizes']['full-width']) . '");
                }
            }
        </style>'
    );
}