<?php
$data = $args['data'];
//print_r($data);
?>
<section class="lg:container max-w-screen-xl mx-auto relative w-full my-20 <?= $data['acf_fc_layout'] ?>">
	<picture class="hidden lg:block">
		<source srcset="<?= $data['map_image']['sizes']['full-width'] ?>">
		<img class="w-full" alt="<?= $data['map_image']['alt'] ?>">
	</picture>
	<?php $counter = 0; ?>
	<div class="map-dropdown block lg:hidden">
		<?php foreach($data['hot_spot'] as $spot): ?>
			<?php if($counter == 0): ?>
				<div class="dropdown-toggle cursor-pointer py-3 pl-5"><?= $spot['title'] ?></div>
					<div class="locations-wrapper opacity-0 h-0 transition-all duration-300 ease-in-out">
			<?php endif; ?>
			<div class="location py-3 pl-5 cursor-pointer" data-spot="<?= $counter ?>"><?= $spot['title'] ?></div>
			<?php $counter++; ?>
		<?php endforeach; ?>
		</div>
	</div>
	<?php $counter = 0; ?>
	<?php foreach($data['hot_spot'] as $spot): ?>

		<div class="hot-spot absolute cursor-pointer hidden lg:block" style="top: <?= $spot['top'] ?>; left: <?= $spot['left'] ?>;" data-spot="<?= $counter ?>">
			<img class="max-w-[65px]" src="<?= $spot['icon']['sizes']['full-width']  ?>" alt="<?= $spot['icon']['alt'] ?>">
		</div>
		<?php 
		if($counter == 0):
			$classes = 'flex';
		else: 
			$classes = 'hidden';
		endif; 
		?>
		<div class="hot-spot--modal lg:px-5 lg:fixed top-0 left-0 right-0 z-50 <?= $classes; ?> lg:hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 lg:h-screen flex-col lg:justify-center bg-white bg-opacity-75" data-spot-container="<?= $counter ?>">
			<div class="lg:container max-w-screen-xl mx-auto relative w-full md:h-auto bg-green p-5 lg:p-10">
				<span class="hotspot-close cursor-pointer absolute top-3 right-3 hidden lg:block">X</span>
				<?php if(!empty($spot['title'])): ?>
					<h3 class="pb-5"><?= $spot['title'] ?></h3>
				<?php endif; ?>

				<?php if(!empty($spot['copy'])): ?>
					<p class="pb-5"><?= $spot['copy'] ?></p>
				<?php endif; ?>
				
				<?php if(!empty($spot['items_title'])): ?>
					<h4 class="pb-5"><?= $spot['items_title'] ?></h4>
				<?php endif; ?>
				
				<div class="grid grid-cols-2 lg:grid-cols-5 gap-6 mt-5">
					<?php foreach($spot['items'] as $item): ?>
						<div class="">
							<?php if(get_field('location_name', $item)): ?>
								<p><?= the_field('location_name', $item); ?></p>
							<?php endif; ?>

							<?php if(get_field('establishment_name', $item)): ?>
								<p><?= the_field('establishment_name', $item); ?></p>
							<?php endif; ?>

							<?php if(get_field('address', $item)): ?>
								<p><?= the_field('address', $item); ?></p>
							<?php endif; ?>

							<?php if(get_field('date', $item)): ?>
								<p><?= the_field('date', $item); ?></p>
							<?php endif; ?>

							<?php if(get_field('time', $item)): ?>
								<p><?= the_field('time', $item); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php $counter++; ?>
	<?php endforeach; ?>
</section>