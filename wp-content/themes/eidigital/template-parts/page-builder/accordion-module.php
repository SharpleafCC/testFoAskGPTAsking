<?php
$data = $args['data'];

if ( !empty($data['accordion__items']) ) { ?>
<section id="<?= $data['accordion__id'] ?>" class="relative w-full pt-5 pb-24">
    <div class="accordion__header-content max-w-screen-lg mx-auto p-5">
    <?php
    if ( !empty($data['accordion__pretitle']) ) { ?>
        <h4 class="accordion__pretitle text-black text-center">
            <?= $data['accordion__pretitle'] ?>
        </h4>
    <?php
    }

    if ( !empty($data['accordion__title']) ) { ?>
        <h2 class="accordion__title text-black text-center">
            <?= $data['accordion__title'] ?>
        </h2>
    <?php
    }

    if ( !empty($data['accordion__subtitle']) ) { ?>
        <h4 class="accordion__subtitle text-black text-center">
            <?= $data['accordion__subtitle'] ?>
        </h4> <?php
    }

    if ( !empty($data['accordion__description']) ) { ?>
        <div class="accordion__description-wrap text-black text-center">
            <?= $data['accordion__description'] ?>
        </div> <?php
    } ?>
    </div>

    <div class="accordion__items max-w-screen-lg mx-auto"> <?php
        foreach ( $data['accordion__items'] as $item ) { ?>
        <div class="accordion__item p-5 border-solid border-b-2 border-[#171719]">

            <div class="accordion__item-title-wrap flex flex-row flex-nowrap cursor-pointer justify-between align-middle">
                <h3 class="accordion__item-title uppercase text-black">
                    <?= $item['accordion__item-title'] ?>
                </h3>

                <div class="accordion__item-arrow-wrap pr-5 self-center">
                    <svg class="accordion__item-arrow w-5 h-5 transition-all" viewBox="0 0 33 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-464.000000, -1819.000000)" fill="currentColor" stroke="currentColor" class="stroke-green fill-green">
                                <polygon points="494.195439 1820 480.78459 1831.61381 467.374101 1820 464.618838 1820 480.78459 1834 496.950702 1820"></polygon>
                            </g>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="accordion__item-content-cover max-h-0 transition-all text-left overflow-hidden block">
                <div class="accordion__item-content-wrap py-5 text-left text-white">
                    <?= $item['accordion__item-content'] ?>
                </div>
            </div>
        </div>
        <?php
        } ?>
    </div>
</section>
<?php
}
?>