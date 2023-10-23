<?php
    $data = $args['data'];
    $unique_class_name = "announcement_bar_" . wp_generate_uuid4();
    //add a class to any anchor tags in the text
    $data['text'] = str_replace('<a', '<a class="text-'.$data['link_color_theme_color'].'"', $data['text']);
?>

<div class="flex items-center w-full h-8 justify-center bg-<?= $data['background_color_theme_color'] ?>">
    <div class="announcement_bar_text">
        <span class="flex justify-center items-center text-<?=$data['text_color_theme_color'] ?> font-poppins text-14 leading-20"><?= $data['text'] ?></span>
    </div>
</div