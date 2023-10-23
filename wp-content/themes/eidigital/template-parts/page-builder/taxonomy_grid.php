<?php $data = $args['data']; ?>
<section class="<?= $data['acf_fc_layout'] ?> container w-full max-w-7xl mx-auto my-20 grid grid-cols-1 md:grid-cols-2 gap-7 pb-10 text-white">
	<?php foreach($data['items'] as $item): ?>
		<?php 
		$term = get_term_by('id', $item->term_id, $item->taxonomy); 
		$image = get_field('featured_image', $item);

		$permalink = esc_url( get_category_link( $item->term_id) );
		$target = "_self";

		?>		
		<a class="block" href="<?=  $permalink ?>" target="<?= $target ?>">
			<div class="w-full block">
				<div class="overflow-hidden">
					<?php if(get_field('featured_image', $item)): ?>
						<picture>
							<source srcset="<?= $image['sizes']['card'] ?>">
							<img class="transition-all duration-300 hover:scale-105" alt="<?= $image['alt'] ?>">
						</picture>
					<?php endif; ?>
				</div>
				<div class="bg-black-400 p-6">
					<div class="flex items-center justify-between">
						<span class="text-2xl font-subtitle uppercase text-gray-900 text-white hover:text-green-300"><?= $term->name ?></span>
						<span class="text-green hover:text-green-300 text-xl">
							<svg class="w-7 h-7 fill-green" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12.7 11.6"><path d="M9.3 6.1H.8V4.7h8.5L5.3.8h2l4.6 4.6L7.3 10h-2l4-3.9z"/></svg>
						</span>
					</div>
				</div>
			</div>
		</a>
	<?php endforeach; ?>
</section>