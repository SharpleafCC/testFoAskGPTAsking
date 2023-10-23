<?php
$data = $args['data'];
$theme_color = $data['theme_color'];
$form_args = [
	'form_name' => 'Form Module',
	'submit_button_copy' 	=> $data['submit_button_copy'],
	'success_header'    	=> $data['success_header'],
	'success_message'		=> $data['success_message'],
	'theme_color'			=> $theme_color,
]
?>
<section class="<?= $data['class_name'] ?> crystals-wrap relative <?= $data['margin']['small'] ?> md:<?= $data['margin']['medium'] ?> lg:<?= $data['margin']['large'] ?> <?= $data['padding']['small'] ?> md:<?= $data['padding']['medium'] ?> lg:<?= $data['padding']['large'] ?>">
	<?php if (!empty($data['add_crystals'])) : ?>
		<span class="crystal crystal-left hidden lg:block"></span>
	<?php endif; ?>
	<div id="<?= $data['id'] ?>" class="container relative flex flex-col items-center justify-center content-center w-full max-w-4xl mx-auto <?= $data['visibility'] ?> <?= $data['acf_fc_layout'] ?>">

		<?php if ($data['title']) : ?>
			<h2 class="text-white text-center mt-12 lg:mt-0 mb-5"><?= $data['title'] ?></h2>
		<?php endif; ?>

		<?php if ($data['copy']) : ?>
			<p class="text-gray-400 text-base font-body text-center"><?= $data['copy'] ?></p>
		<?php endif; ?>

		<div class="flex w-full md:w-fit mt-10 mb-20 text-white">
			<?php get_template_part('template-parts/forms/' . $data['form_selection'], null, $form_args); ?>
		</div>

	</div>
	<?php if (!empty($data['add_crystals'])) : ?>
		<span class="crystal crystal-right hidden lg:block"></span>
	<?php endif; ?>
</section>