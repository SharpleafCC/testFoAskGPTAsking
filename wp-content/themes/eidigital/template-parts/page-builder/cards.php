<?php
$data = $args['data'];

// How many cards are there in the repeater field?
$number_cards = count($data['cards']);

// If we have 2 cards set max width smaller so that they don't get too big. 
if($number_cards == 2):
	$width = 'max-w-7xl';
else:
	$width = 'max-w-screen-xl';
endif;
?>
<section class="my-20 <?= $data['acf_fc_layout'] ?>">
	<div class="container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?= $number_cards ?> gap-7 mx-auto pb-10 text-white <?= $width ?>">
		<?php foreach($data['cards'] as $item): ?>
			<a class="block" href="<?= $item['link']['url'] ?>" target="<?= $item['link']['target'] ?>" title="<?= $item['link']['title'] ?>">
				<div class="w-full block">
					<div class="overflow-hidden">
						<?php if($item): ?>
							<picture>
								<source srcset="<?= $item['image']['sizes']['card'] ?>">
								<img class="transition-all duration-300 hover:scale-105" alt="<?= $item['image']['alt'] ?>">
							</picture>
						<?php endif; ?>
					</div>
					<div class="bg-black-400 p-6">
						<div class="flex items-center justify-between">
							<span class="text-2xl font-normal font-subtitle uppercase text-gray-900 text-white hover:text-green-300"><strong><?= $item['title'] ?></strong></span>
							<span class="text-green hover:text-green-300 text-xl">
								<svg class="w-7 h-7 fill-green" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12.7 11.6"><path d="M9.3 6.1H.8V4.7h8.5L5.3.8h2l4.6 4.6L7.3 10h-2l4-3.9z"/></svg>
							</span>
						</div>
					</div>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</section>