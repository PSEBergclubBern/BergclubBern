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
            <div class="pull-right title-right type-select">
                Art: <select id="tourenart"><option value="">Alle</option>
                    <?php foreach($tourenarten as $key => $tourenart){ ?>
                    <option value="<?= $key ?>"<?php if($key == $currentTourenart){ ?> selected<?php } ?>><?= $tourenart ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="pull-right title-right download">
                <a target="_blank" href="<?= BCB_CALENDAR_URL ?>"><span class="glyphicon glyphicon-download-alt"></span> Kalender <?= date('Y') ?> herunterladen</a>
            </div>
            <?php the_archive_title(); ?>
        </h1>
        <?php if ( have_posts() ){ ?>
            <div class="container-fluid grid-table hide-links row-hover">
                <div class="row row-header hidden-xs">
                    <div class="col-sm-2">
                        Datum
                    </div>
                    <div class="col-sm-3">
                        Art
                    </div>
                    <div class="col-sm-7">
                        Titel
                    </div>
                </div>
                <?php
                while (have_posts()) : the_post();
                    $typeDisplay = bcb_touren_meta(get_the_ID(), "type");
                    $typeWithTechnicalRequirementsDisplay = bcb_touren_meta(get_the_ID(), 'typeWithTechnicalRequirements');
                    ?>
                    <div class="row add-link">
                        <div class="col-sm-2 italic-sm">
                            <?= bcb_touren_meta(get_the_ID(), 'dateDisplayFull'); ?>
                        </div>
                        <div class="col-sm-3 italic-sm">
                            <?= $typeWithTechnicalRequirementsDisplay; ?>
                        </div>
                        <div class="col-sm-7">
                            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
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