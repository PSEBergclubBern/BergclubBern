<?php

get_header(); ?>
    <?php
    while (have_posts()) : the_post();
        $date_from = get_post_meta(get_the_ID(), "_dateFrom", true);
        $date_to =  get_post_meta(get_the_ID(), "_dateTo", true);
        $dateDisplay = date("d.m.y", strtotime($date_from));
        if(!empty($date_to) && $date_to != $date_from){
            $dateDisplay .=" - " . date("d.m.", strtotime($date_to));
        }
        $type = get_post_meta(get_the_ID(), "_type", true);
        $reqTechnical = get_post_meta(get_the_ID(), "_requirementsTechnical", true);
        $typeDisplay = bcb_get_touren_type_by_slug($type) . ", " . $reqTechnical;
        $riseUpDisplay = get_post_meta(get_the_ID(), "_riseUpMeters", true);
        $riseDownDisplay = get_post_meta(get_the_ID(), "_riseDownMeters", true);
        $durationDisplay = get_post_meta(get_the_ID(), "_duration", true);
        ?>

        <div class="container">
            <div class="row">
                <h1><?php the_title(); ?></h1>
            </div>
            <div class="row">
                <ul class="list-group article-content-list">
                <li class="list-group-item">
                    <div class="icon icon-date"></div> <?= $dateDisplay ?>
                </li>
                <li class="list-group-item">
                    <div class="icon icon-type"></div> <?= $typeDisplay ?>
                </li>
                <li class="list-group-item">
                    <div class="icon icon-up"></div> <?= $riseUpDisplay ?>
                </li>
                </ul>
            </div>
            <div class="container-fluid grid-table hide-links row-hover">
                <div class="table">

                </div>
            </div>
        </div>

    <?php endwhile; ?>

<?php get_footer(); ?>
