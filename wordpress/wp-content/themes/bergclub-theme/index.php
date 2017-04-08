<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
get_header() ?>

    <div class="container-fluid">

        <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active"></div>
                <div class="item"></div>
                <div class="item"></div>
                <div class="item"></div>
            </div>
        </div>

        <img class="img-responsive header-logo"
             src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo<?php if(bcb_is_jugend()){ echo "-jugend"; } ?>.png" alt="Logo">

        <div class="row">

            <div class="col-md-6 col-md-offset-6 col-lg-4 col-lg-offset-8 newest-posts">
                <!--<div class="hidden-lg hidden-md mobile-placeholder">&nbsp;</div>-->
                <div class="well well-home">
                    <h3>Nächste Touren <?php if(bcb_is_jugend()){ echo "Jugend"; } ?></h3>
                    <ul class="list-group hide-links">
                        <?php
                        $query =  new WP_Query( array(
                            'post_type' => 'touren',
                            'posts_per_page' => 5,
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
                            $dateDisplay = date("d.m.", strtotime($date_from));
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
                            <li class="list-group-item add-link">
                                <div class="row">
                                    <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                                </div>
                                <div class="row additional-info">
                                    <div class="pull-left additional-info-item"><div class="icon icon-date"></div> <?= $dateDisplay ?></div>
                                    <div class="pull-left additional-info-item"><div class="icon icon-type"></div> <?= $typeDisplay ?></div>
                                    <div class="pull-left additional-info-item"><div class="icon icon-up"></div> <?= $riseUpDisplay ?></div>
                                    <div class="pull-left additional-info-item"><div class="icon icon-down"></div> <?= $riseDownDisplay ?></div>
                                    <div class="pull-left additional-info-item"><div class="icon icon-duration"></div> <?= $durationDisplay ?></div>
                                </div>
                            </li>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </ul>
                </div>

                <div class="well well-home">
                    <h3>Neueste Mitteilungen</h3>

                    <ul class="list-group">
                        <?php
                        $query = new WP_Query(array('category_name' => 'mitteilungen', 'posts_per_page' => 3));
                        while ($query->have_posts()) : $query->the_post(); ?>
                            <li class="list-group-item"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></li>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>

                    </ul>
                </div>
            </div>

        </div>
    </div><!-- /.container-fluid -->

<?php get_footer() ?>