<?php

get_header();


require_once __DIR__ . '/forms/contact-action.php';
?>
<div class="container">
    <div class="row">
        <?php the_title('<h1 class="page-header">', '</h1>'); ?>
        <?php bcb_show_notice(); ?>
    </div>
    <div class="row">
        <?php
        while (have_posts()) : the_post();
            the_content('<p>', '</p>');
        endwhile;
        ?>
    </div>
    <br />
    <?php require_once __DIR__ . '/forms/contact.php'; ?>
    <br />
</div>

<?php get_footer(); ?>
