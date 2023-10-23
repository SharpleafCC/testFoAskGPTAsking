<?php
$post_image = get_field('quiz_product_image');
$title_color = get_field('font_color_theme_color');
$explore_link = get_field('quiz_explore_link');
?>
<div class="flex flex-col items-center justify-center gap-4">
	<p class="text-10 text-center text-<?= $title_color ?>">YOUR BEST MATCH IS</p>
	<h2 class="text-20 leading-7 text-center text-<?= $title_color ?>"><?php echo get_the_title(); ?></h2>
	<picture class="max-w-xs mx-auto mb-3 max-h-[240px]" data-background="<?php the_field('quiz_background_image'); ?>" data-background-mobile="<?php the_field('quiz_background_image_mobile'); ?>">
		<source srcset="<?= $post_image['url'] ?>">
		<img class="grid-item__image max-h-[240px]" alt="<?php echo get_the_title(); ?> - Product Image">
	</picture>
	<div class="max-w-sm mx-auto bg-white rounded-xl px-6 py-5 mb-2">
		<p class="text-center text-14 leading-5 mb-2 text-black">You’ve unlocked 10% off!</p>
		<p class="text-center text-16 leading-5 text-black">Use the code <span class="font-bold">DISCOUNTCODEHERE</span> at checkout</p>
	</div>
	<a href="<?= get_the_permalink() ?>" class="w-full max-w-sm mx-auto"><button class="results-shop grow w-full rounded-full px-12 py-4 bg-black text-white uppercase">Shop Now</button></a>

	<?php if(!empty($explore_link['url'])): ?>
		<a href="<?= $explore_link['url'] ?>" class="results-product-cta w-full max-w-sm mx-auto rounded-full px-12 py-4 text-12 text-<?= $title_color ?> border border-solid border-black bg-white text-black hover:text-white text-center uppercase "><?= $explore_link['title'] ?></a>
	<?php endif; ?>
	<button id="restart-quiz" class="px-12 pb-4 text-12 text-<?= $title_color ?> hover:text-accent-blue underline">Re-take Quiz</button>
</div>