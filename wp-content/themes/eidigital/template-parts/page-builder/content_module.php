<?php
$data = $args['data'];
$theme_color = $data['theme_color'];
$backgroundImage = '';

$fadeInUp = '';
$section_fade = '';
if($data['add_fade_in'] == 1):
    $fadeInUp = 'fade-in-up overflow-hidden';
    $section_fade = 'section-fade';
endif;

$section_pin = '';
if($data['pin_section'] == 1):
    $section_pin = 'section-pin h-full min-h-screen ';
endif;

$backgroundImageVariables = BackgroundImage::createBackgroundImageVariables( $data['background_image_mobile'], $data['background_image_desktop'], $data['background_image_settings_mobile'], $data['background_image_settings_desktop'] );

if ( !empty($data) ) { ?>
<section style="<?= $backgroundImageVariables ?>" id="<?= $data['id'] ?>" class="background-image-support relative overflow-hidden w-full mx-auto flex <?= $section_fade ?> <?= $section_pin ?> <?= PageBuilder::section_styles($data) ?>">
    <?php
    if ( is_array($data['background_video']) && array_key_exists('video_type', $data['background_video']) && $data['background_video']['video_type'] !== 'null' ) {
        get_template_part('template-parts/components/video', null, ['data' => $data['background_video']]);
    }
    ?>
    <div class="container relative flex <?= $data['flex_direction'] ?> <?= $data['align_items']['small']['align_alignment'] ?> md:<?= $data['align_items']['medium']['align_alignment'] ?> lg:<?= $data['align_items']['large']['align_alignment'] ?> <?= $data['justify_content'] ?> w-full max-w-screen-xl mx-auto">        
        <?php
        if ( !empty($data['pretitle']) ) { ?>
            <h3 class="text-postTitle <?= $fadeInUp ?> text-<?= $data['pretitle_color_theme_color'] ?> w-full mb-2 <?= PageBuilder::content_width($data) ?> <?= PageBuilder::content_width($data) ?> <?= PageBuilder::text_alignment($data) ?> "><?= $data['pretitle'] ?></h3> <?php
        }

        if ( !empty($data['title']) ) { ?>
            <?php if($data['title_h1']): ?>
                <h1 class="title <?= $fadeInUp ?> font-title text-title text-<?= $data['title_color_theme_color'] ?> w-full mb-2 <?= PageBuilder::content_width($data) ?> <?= PageBuilder::text_alignment($data) ?>"><?= $data['title'] ?></h1>
            <?php else: ?>
                <h2 class="title <?= $fadeInUp ?> font-title text-title text-<?= $data['title_color_theme_color'] ?> w-full mb-2 <?= PageBuilder::content_width($data) ?> <?= PageBuilder::text_alignment($data) ?>"><?= $data['title'] ?></h2>
            <?php endif; ?>
            <?php
        }

        if ( !empty($data['subtitle']) ) { ?>
            <h3 class="subtitle <?= $fadeInUp ?> text-<?= $data['sub_title_color_theme_color'] ?> w-full mb-2 <?= PageBuilder::content_width($data) ?> <?= PageBuilder::text_alignment($data) ?>"><?= $data['subtitle'] ?></h3> <?php
        }

        if ( !empty($data['description']) ) { ?>
            <div class="description <?= $fadeInUp ?> font-body text-base text-<?= $data['description_copy_color_theme_color'] ?> w-full mb-2 <?= PageBuilder::content_width($data) ?> <?= PageBuilder::text_alignment($data) ?>"><?= $data['description'] ?></div> <?php
        }

        if ( !empty($data['cta']['cta__link']) ) {
        ?>
            <div class="mt-10 <?= $fadeInUp ?>">
                <?php get_template_part( 'template-parts/page-builder/cta', '', ['data' => $data['cta']] ); ?>
            </div>
        <?php } ?>
    </div>
</section>
<?php 
} ?>