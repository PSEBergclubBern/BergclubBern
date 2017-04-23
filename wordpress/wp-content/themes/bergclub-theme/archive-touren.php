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
            <div class="pull-right" style="font-size:0.5em;margin-top:10px">
                Art: <select id="tourenart"><option value="">Alle</option>
                    <?php foreach($tourenarten as $key => $tourenart){ ?>
                    <option value="<?= $key ?>"<?php if($key == $currentTourenart){ ?> selected<?php } ?>><?= $tourenart ?></option>
                    <?php } ?>
                </select>
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
                $metaQuery = [
                    [
                        'key' => '_dateFromDB',
                        'value' => date('Y-m-d'),
                        'type' => 'DATE',
                        'compare' => '>='
                    ]
                ];

                if(!empty($currentTourenart)){
                    $metaQuery[] = [
                            'key' => '_type',
                            'value' => $currentTourenart,
                            'compare' => '='
                        ];
                }

                $query =  new WP_Query( array(
                    'posts_per_page' => -1,
                    'post_type' => 'touren',
                    'order' => 'ASC',
                    'orderby' => '_dateFromDB',
                    'meta_query' => $metaQuery,
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
                    ?>
                    <div class="row add-link">
                        <div class="col-sm-2 italic-sm">
                            <?= bcb_touren_meta(get_the_ID(), 'dateDisplayFull'); ?>
                        </div>
                        <div class="col-sm-3 italic-sm">
                            <?= $typeDisplay; ?>
                        </div>
                        <div class="col-sm-7">
                            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="container">
                <?php //bcb_pagination(); ?>
            </div>
        <?php }else{ ?>
            <div class="container">
                <p>Keine Beiträge vorhanden.</p>
            </div>
        <?php } ?>
    </div>
<?php get_footer(); ?>