<?php

get_header();
$tourenarten = get_option('bcb_tourenarten');
asort($tourenarten);
$currentTourenart = '';
if(isset($_GET['type'])){
    $currentTourenart = $_GET['type'];
}
?>


    <div class="container">
        <h1>
            <?php the_archive_title(); ?>
        </h1>
        <?php if ( have_posts() ){ ?>
            <div class="container-fluid grid-table hide-links row-hover">
                <div class="row row-header hidden-xs">
                    <div class="col-sm-2">
                        Publiziert
                    </div>
                    <div class="col-sm-7">
                        Tour
                    </div>
                    <div class="col-sm-2">
                        Datum Tour
                    </div>
                </div>
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $tourPostID = bcb_touren_meta(get_the_ID(), "touren");
                    $typeDisplay = bcb_touren_meta($tourPostID, "type");
                    $typeWithTechnicalRequirementsDisplay = bcb_touren_meta($tourPostID, 'typeWithTechnicalRequirements');
                    ?>
                    <div class="row add-link">
                        <div class="col-sm-2 hidden-xs">
                            <?php echo get_the_date(); ?>
                        </div>
                        <div class="col-sm-7">
                            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a><br/><small><?= $typeWithTechnicalRequirementsDisplay ?></small>
                        </div>
                        <div class="col-sm-2 italic-sm">
                            <?= bcb_touren_meta($tourPostID, 'dateDisplayFull'); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="container">
                <?php bcb_pagination(); ?>
            </div>
        <?php }else{ ?>
            <div class="container">
                <p>Keine Beiträge vorhanden.</p>
            </div>
        <?php } ?>
    </div>
<?php get_footer(); ?>