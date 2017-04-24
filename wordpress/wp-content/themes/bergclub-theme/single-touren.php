<?php

get_header(); ?>
    <?php
    while (have_posts()) : the_post();
        $typeWithTechnicalRequirementsDisplay = bcb_touren_meta(get_the_ID(), 'typeWithTechnicalRequirements');
        $dateDisplay = bcb_touren_meta(get_the_ID(), "dateDisplayFull");
        $meetpointWithTimeDisplay = bcb_touren_meta(get_the_ID(), "meetpointWithTime");
        $returnBackDisplay = bcb_touren_meta(get_the_ID(), "returnBack");
        $leaderAndCoLeaderDisplay = bcb_touren_meta(get_the_ID(), "leaderAndCoLeader");
        $programDisplay = get_post_meta(get_the_ID(), "_program", true);


        $distanceDisplay = bcb_touren_meta(get_the_ID(), "distance");
        $riseDisplay = bcb_touren_meta(get_the_ID(), "riseUpAndDown");
        $durationDisplay = bcb_touren_meta(get_the_ID(), "duration");
        $reqConditionalDisplay = bcb_touren_meta(get_the_ID(), "requirementsConditional");
        $equipmentDisplay = get_post_meta(get_the_ID(), "_equipment", true);
        $sleepOverDisplay = get_post_meta(get_the_ID(), "_sleepOver", true);
        $foodDisplay = get_post_meta(get_the_ID(), "_food", true);
        $mapMaterialDisplay = get_post_meta(get_the_ID(), "_mapMaterial", true);
        $onlineMap = get_post_meta(get_the_ID(), "_onlineMap", true);
        $onlineMapDisplay = empty($onlineMap) ? $onlineMap : "<a target=\"_blank\" href=\"" . $onlineMap . "\">" . $onlineMap . "</a>";
        $additionalInfoDisplay = get_post_meta(get_the_ID(), "_additionalInfo", true);


        $costs = get_post_meta(get_the_ID(), "_costs", true);
        $costsDisplay = "Keine Angabe";
        if(is_numeric($costs) && $costs > 0){
            $costsDisplay = "CHF " . number_format($costs, 2, '.', '');
        }
        $costsForDisplay = get_post_meta(get_the_ID(), "_costsFor", true);

        $signupUntil = get_post_meta(get_the_ID(), "_signupUntil", true);
        $signupUntilDisplay = empty($signupUntil) ? "" : date("d.m.y", strtotime($signupUntil));
        $signupToDisplay = bcb_touren_meta(get_the_ID(), "signupToWithEmail");

        $generalInfo = array(
            "Typ" => $typeWithTechnicalRequirementsDisplay,
            "Datum" => $dateDisplay,
            "Treffpunkt" => $meetpointWithTimeDisplay,
            "Rückkehr" => $returnBackDisplay,
            "Tourenleiter" => $leaderAndCoLeaderDisplay,
            "Programm" => $programDisplay,
        );

        $tourInfo = array(
            "Distanz" => $distanceDisplay,
            "Steigung" => $riseDisplay,
            "Dauer" => $durationDisplay,
            "Konditionelle Anforderungen" => $reqConditionalDisplay,
            "Ausrüstung" => $equipmentDisplay,
            "Übernachtung" => $sleepOverDisplay,
            "Verplfegung" => $foodDisplay,
            "Kartenmaterial" => $mapMaterialDisplay,
            "Online Route" => $onlineMapDisplay,
            "Besonderes" => $additionalInfoDisplay,
        );

        $signupInfo = array(
            "Kosten" => $costsDisplay,
            "Kostengrund" => $costsForDisplay,
            "Anmeldung bis" => $signupUntilDisplay,
            "Anmeldung bei" => $signupToDisplay
        );

        $rows = array(
            "Allgemeine Informationen" => $generalInfo,
            "Tourendetails" => $tourInfo,
            "Kosten/Anmeldung" => $signupInfo
        );

        ?>

        <div class="container">
            <div class="row">
                <h1><?php the_title(); ?></h1>
            </div>

            <?php foreach($rows as $title => $array): ?>
                <div class="row article-block">
                    <h3><?=$title?></h3>
                    <?php foreach($array as $key => $value): ?>
                        <?php if(!empty($value)): ?>
                            <div class="row article-line">
                                <div class="col-md-3 col-xs-5"><?=$key?></div>
                                <div class="col-md-9 col-xs-7"><?=$value?></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endwhile; ?>

<?php get_footer(); ?>
