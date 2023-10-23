<?php
$data = $args['data'];

$additionalClasses = $data['cta__classes'];

if ($data['theme_color']):
	switch ($data['theme_color']) {
		case 'default':
			$button_classes = 'block bg-green hover:bg-green-100 text-black py-4 px-12';
			break;
		case 'red':
			$button_classes = 'block bg-red hover:bg-red-300 text-black py-4 px-12';
			break;
		case 'gray':
			$button_classes = 'block bg-gray hover:bg-gray-100 text-black py-4 px-12';
			break;
		case 'teal':
			$button_classes = 'block bg-teal-500 text-black py-4 px-12';
			break;
	}
endif;

if (!empty($data)) { ?>
	<a href="<?= $data['cta__link']['url'] ?>" target="<?= $data['cta__link']['target'] ? '_blank' : '_self' ?>" rel="noreferrer" class="cta uppercase <?= $button_classes ?> <?= $additionalClasses ?>">
		<span class="font-subtitle"><?= $data['cta__link']['title'] ?></span>
	</a>
<?php
}
?>