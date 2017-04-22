<?php

get_header(); ?>


    <div class="container">
        <h1><?php the_archive_title(); ?></h1>
        <?php if ( have_posts() ){ ?>
            <div class="container-fluid grid-table hide-links row-hover">
                <div class="row row-header hidden-xs">
                    <div class="col-sm-2">
                        Datum
                    </div>
                    <div class="col-sm-3">
                        Art
                    </div>
                    <div class="col-sm-1">
                        Dauer
                    </div>
                    <div class="col-sm-6">
                        Titel
                    </div>
                </div>
                <?php
                $query =  new WP_Query( array(
                    'post_type' => 'touren',
                    'order' => 'ASC',
                    'orderby' => 'dateFrom',
                    'meta_query' => array (
                        'key' => 'dateFrom',
                        'value' => date('d.m.Y',strtotime("today")),
                        'type' => 'DATE',
                        'compare' => '>='
                    )
                ));
                while ($query->have_posts()) : $query->the_post();
                    $date_from = get_post_meta(get_the_ID(), "_dateFrom", true);
                    $date_to =  get_post_meta(get_the_ID(), "_dateTo", true);
                    if(!empty($date_to) && $date_to != $date_from){
                        $dateDisplay = date("d.m.", strtotime($date_from)) . "-" . date("d.m.y", strtotime($date_to));
                    } else {
                        $dateDisplay = date("d.m.y", strtotime($date_from));
                    }
                    $typeDisplay = bcb_touren_meta(get_the_ID(), "type");
                    $reqTechnical = get_post_meta(get_the_ID(), "_requirementsTechnical", true);
                    if(!empty($reqTechnical)) {
                        $typeDisplay .= ", " . $reqTechnical;
                    }
                    $durationDisplay = get_post_meta(get_the_ID(), "_duration", true);
                    ?>
                    <div class="row add-link">
                        <div class="col-sm-2 italic-sm">
                            <?= $dateDisplay; ?>
                        </div>
                        <div class="col-sm-3 italic-sm">
                            <?= $typeDisplay; ?>
                        </div>
                        <div class="col-sm-1 italic-sm">
                            <?= $durationDisplay; ?>
                        </div>
                        <div class="col-sm-6">
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