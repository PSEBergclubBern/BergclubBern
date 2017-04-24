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
            <h1><?php the_title(); ?></h1>
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
            <?php the_content('<p>', '</p>'); ?>
        </div>
    </div>
<?php
endwhile;
?>

<?php get_footer(); ?>
