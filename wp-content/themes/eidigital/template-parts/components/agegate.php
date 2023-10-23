<?php if(get_field('age_gate_status', 'option') == 1): ?>
    <?php if(get_field('agegate__bg-mobile', 'option')): ?>
        <style>
        @media screen and (max-width: 480px) {
            #agegate {
                background-image: url('<?php echo get_field('agegate__bg-mobile', 'option')['url']; ?>');
            }
        }
        </style>
    <?php endif; ?>

    <section id="agegate" class="grid bg-<?= the_field('age_gate_background_color_theme_color', 'option') ?> fixed invisible opacity-0 items-end justify-center h-screen w-full z-50 transition-all duration-750 ease-in-out left-0 top-0" style="background-image: url('<?php echo get_field('agegate__bg-desktop', 'option')['url']; ?>');">
        <?php if(get_field('age_gate_status', 'option') == 1): ?>
            <div class="section section--contained h-full flex items-center justify-center">
                <div class="agegate px-8 py-12 max-w-md w-full">
                    <?php if(get_field('agegate_logo', 'option')): ?>
                        <?php $image = get_field('agegate_logo', 'option'); ?>
                        <img alt="<?php echo $image['alt']; ?>" src="<?php echo $image['sizes']['960w']; ?>" class="mb-5"/>
                    <?php endif; ?>
                    <?php if(get_field('agegate__preheader', 'option')): ?>
                        <div class="text-base text-gray-500 mb-8 text-center"><?php the_field('agegate__preheader', 'option'); ?></div>
                    <?php endif; ?>
                    <?php if(get_field('agegate_header', 'option')): ?>
                        <h1 class="text-2xl font-bold mb-8 text-center"><?php the_field('agegate_header', 'option'); ?></h1>
                    <?php endif; ?>
                    <?php if(get_field('age-gate__yes-button', 'option')): ?>
                        <?php $yes_link = get_field('age-gate__yes-button', 'option'); ?>
                    <?php endif; ?>
                    <?php 
                    if(get_field('age-gate__no-button', 'option')):
                        $no_link = get_field('age-gate__no-button', 'option');
                    else:
                        $no_link['url'] = 'https://responsibility.org';
                        $no_link['title'] = "NO";
                    endif;
                    ?>
                    <button id="age-gate__button--yes" class="utton border-2 border-primary text-primary text-sm font-medium py-2 px-4 rounded-full w-full"><span><?php echo esc_html( $yes_link ); ?></span></button>
                    <a class="age__gate--no block mt-2" href="<?php echo esc_url( $no_link['url'] ); ?>" target="_blank"><button id="age-gate__button--no" class="button border-2 border-primary text-primary text-sm font-medium py-2 px-4 rounded-full w-full"><span><?php echo esc_html( $no_link['title'] ); ?></span></button></a>
                </div>
            </div>
            <?php if(get_field('agegate_copy', 'option')): ?>
                <div class="section section--contained self-end text-xs text-gray-500 pt-12 pb-5 text-center">
                    <?php the_field('agegate_copy', 'option'); ?>
                </div>
            <?php endif; ?>
            <?php if(get_field('age-gate__cookie-consent', 'option')): ?>
                <div class="section section--contained self-end text-xs text-gray-500 pt-5 text-center">
                        <?php the_field('age-gate__cookie-consent', 'option'); ?>
                    </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
<?php endif; ?>