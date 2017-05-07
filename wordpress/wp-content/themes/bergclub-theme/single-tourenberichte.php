<?php

get_header(); ?>
<?php
while (have_posts()) : the_post();
    $tourPostID = bcb_touren_meta(get_the_ID(), "touren");
    $typeWithTechnicalRequirementsDisplay = bcb_touren_meta($tourPostID, 'typeWithTechnicalRequirements');
    $dateDisplay = bcb_touren_meta($tourPostID, "dateDisplayFull");
    $leaderAndCoLeaderDisplay = bcb_touren_meta($tourPostID, "leaderAndCoLeader");

    $distanceDisplay = bcb_touren_meta($tourPostID, "distance");
    $riseDisplay = bcb_touren_meta($tourPostID, "riseUpAndDown");
    $durationDisplay = bcb_touren_meta($tourPostID, "duration");
    $reqConditionalDisplay = bcb_touren_meta($tourPostID, "requirementsConditional");
    $onlineMap = get_post_meta($tourPostID, "_onlineMap", true);
    $onlineMapDisplay = empty($onlineMap) ? $onlineMap : "<a target=\"_blank\" href=\"" . $onlineMap . "\">" . $onlineMap . "</a>";

    $generalInfo = array(
        "Typ" => $typeWithTechnicalRequirementsDisplay,
        "Datum" => $dateDisplay,
        "Tourenleiter" => $leaderAndCoLeaderDisplay,
        "Distanz" => $distanceDisplay,
        "Steigung" => $riseDisplay,
        "Dauer" => $durationDisplay,
        "Konditionelle Anforderungen" => $reqConditionalDisplay,
        "Online Route" => $onlineMapDisplay,
    );
    ?>
    <div class="container">
        <div class="row">
            <h1>
                <?php
                $isYouth = bcb_touren_meta(get_the_ID(), 'isYouth');
                if($isYouth == 1 || $isYouth == 2){
                    ?>
                    <img class="pull-right" src="<?= get_template_directory_uri() ?>/img/bergclub-jugend-sm.png" style="margin-left:20px">
                <?php }if($isYouth == 0 || $isYouth == 2){ ?>
                    <img class="pull-right" src="<?= get_template_directory_uri() ?>/img/bergclub-sm.png" style="margin-left:20px">
                <?php } ?>
                <?php the_title(); ?>
            </h1>
        </div>
        <div class="row article-block">
            <h3>Toureninfo</h3>
            <?php foreach($generalInfo as $key => $value): ?>
                <?php if(!empty($value)): ?>
                    <div class="row article-line">
                        <div class="col-md-3 col-xs-5"><?=$key?></div>
                        <div class="col-md-9 col-xs-7"><?=$value?></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="row bericht-block">
            <h2>Tourenbericht</h2>
            <div class="report-images row">
                <?php if(get_post_gallery()):
                    $gallery = get_post_gallery(get_the_ID(), false);
                    $gallery_attachments_ids = explode(",", $gallery["ids"]);
                    foreach($gallery_attachments_ids as $id): ?>
                        <?php
                            $imgDescription = htmlentities(get_post($id)->post_excerpt);
                        ?>
                        <a href="<?=wp_get_attachment_url($id)?>" data-lightbox="report-gallery" data-title="<?= nl2br($imgDescription) ?>">
                            <img alt="<?= $imgDescription ?>" title="<?= $imgDescription ?>" src="<?=wp_get_attachment_thumb_url($id)?>" class="report-image">
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php
            $content = get_the_content();
            $content = preg_replace("/\[gallery[^\]]+\]/i", " ", $content); //replaces the gallery
            $content = preg_replace("/\[caption[^\\\]+\/caption]/i", " ", $content); //replaces single images with caption
            $content = preg_replace("/<img[^>]+\>/i", " ", $content);  //replaces single images without caption
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]>', $content);
            echo '<p>'.$content.'</p>';
            ?>
        </div>
    </div>
    <?php
endwhile;
?>

<?php get_footer(); ?>
