<?php
$data = $args['data'];

if ( !empty($data) ) {
    ?>
    <section class="social-grid relative container w-full max-w-screen-xl mx-auto text-center" id="social-grid">
        <?php
        if ( !empty($data['social-grid__pretitle']) && is_array($data['social-grid__pretitle']) ) { ?>
            <a href="<?= !empty($data['social-grid__pretitle']['link']) ? $data['social-grid__pretitle']['link'] : '#' ?>" target="<?= $data['social-grid__pretitle']['target'] ?>" class="social-grid__pretitle social-grid__link font-subtitle text-green inline-block uppercase pb-3 lg:pb-4 text-xl">
                <?= !empty($data['social-grid__pretitle']['title']) ? $data['social-grid__pretitle']['title'] : '' ?>
            </a>
        <?php
        } 
        
        if ( !empty($data['social-grid__title']) ) {
        ?>
            <h2 class="social-grid__title font-title text-white">
                <?= $data['social-grid__title'] ?>
            </h2> <?php
        }

        if ( is_array($data['social-grid__content']) && !empty($data['social-grid__content']) ) {
            $containerClass = 'grid-cols-2 md:grid-cols-3';
            $contentCount = count($data['social-grid__content']);

            $containerClass .= ' desktop:grid-cols-' . $contentCount;
            ?>
            <div class="social-grid__content grid w-full pt-3 pb-5 lg:pt-5 lg:pb-10 mb-20 justify-center items-center gap-x-2 gap-y-2 <?= $containerClass ?>"> <?php
            foreach ($data['social-grid__content'] as $content) { ?>
                <article class="social-grid__item relative inline-block overflow-hidden justify-self-center self-center bg-black-100/10 w-full h-[160px] lg:h-[220px]">
                    <?php
                    if ( isset($content['content_type']) && $content['content_type'] === 'video' ) { ?>
                    <svg aria-label="Clip" class="_ab6- absolute top-2 right-2 z-[2]" color="#ffffff" fill="#ffffff" height="18" role="img" viewBox="0 0 24 24" width="18">
                        <path d="m12.823 1 2.974 5.002h-5.58l-2.65-4.971c.206-.013.419-.022.642-.027L8.55 1Zm2.327 0h.298c3.06 0 4.468.754 5.64 1.887a6.007 6.007 0 0 1 1.596 2.82l.07.295h-4.629L15.15 1Zm-9.667.377L7.95 6.002H1.244a6.01 6.01 0 0 1 3.942-4.53Zm9.735 12.834-4.545-2.624a.909.909 0 0 0-1.356.668l-.008.12v5.248a.91.91 0 0 0 1.255.84l.109-.053 4.545-2.624a.909.909 0 0 0 .1-1.507l-.1-.068-4.545-2.624Zm-14.2-6.209h21.964l.015.36.003.189v6.899c0 3.061-.755 4.469-1.888 5.64-1.151 1.114-2.5 1.856-5.33 1.909l-.334.003H8.551c-3.06 0-4.467-.755-5.64-1.889-1.114-1.15-1.854-2.498-1.908-5.33L1 15.45V8.551l.003-.189Z" fill-rule="evenodd"></path>
                    </svg> <?php
                    }

                    if ( !empty($content['link']) ) { ?>
                    <a href="<?= isset($content['link']['url']) ? $content['link']['url'] : '' ?>" target="<?= $content['link']['target'] ?>" class="social-grid__item-link block relative w-full h-full"> <?php
                    } 
                    
                    if ( !empty($content['image']) ) { ?>
                        <img src="<?= $content['image']['url'] ?>" alt="<?= $content['image']['alt'] ?>" class="social-grid__item-image block object-cover h-full w-full"> <?php
                    }

                    if ( !empty($content['link']) ) { ?>
                    </a>
                    <?php
                    } ?>
                </article>
            <?php
            } ?>
            </div><?php
        } ?>
    </section>
    <?php
}
?>