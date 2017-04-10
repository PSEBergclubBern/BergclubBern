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
        $leaderDisplay = get_post_meta(get_the_ID(), "_leader", true);
        $coLeader = get_post_meta(get_the_ID(), "_coLeader", true);
        if(!empty($coLeader)){
            $leaderDisplay .= ", " . $coLeader . " (Co-Leiter)";
        }
        $programDisplay = get_post_meta(get_the_ID(), "_program", true);


        $reqConditionalDisplay = get_post_meta(get_the_ID(), "_requirementsConditional", true);
        $riseUp = get_post_meta(get_the_ID(), "_riseUpMeters", true);
        $riseDown = get_post_meta(get_the_ID(), "_riseDownMeters", true);
        $riseDisplay = "<div class=\"icon icon-up\" title=\"Hinauf\"></div>" . $riseUp . " <div class=\"icon icon-down\" title=\"Hinab\"></div>" . $riseDown;
        $durationDisplay = get_post_meta(get_the_ID(), "_duration", true);
        $equipmentDisplay = get_post_meta(get_the_ID(), "_equipment", true);
        $sleepOverDisplay = get_post_meta(get_the_ID(), "_sleepOver", true);
        $mapMaterialDisplay = get_post_meta(get_the_ID(), "_mapMaterial", true);
        $onlineMapDisplay = get_post_meta(get_the_ID(), "_onlineMap", true);
        $additionalInfoDisplay = get_post_meta(get_the_ID(), "_additionalInfo", true);


        $costsDisplay = get_post_meta(get_the_ID(), "_costs", true) . " Franken";
        $costsFor = get_post_meta(get_the_ID(), "_costsFor", true);
        if(!empty($costsFor)){
            $costsDisplay .= ", " . $costsFor;
        }

        $signupUntil = get_post_meta(get_the_ID(), "_signupUntil", true);
        $signupUntilDisplay = date("d.m.y", strtotime($signupUntil));
        $signupToDisplay = get_post_meta(get_the_ID(), "_signupTo", true);


        $generalInfo = array(
            "Datum" => $dateDisplay,
            "Typ" => $typeDisplay,
            "Tourenleiter" => $leaderDisplay,
            "Program" => $programDisplay,
        );

        $tourInfo = array(
            "Konditionelle Schwierigkeit" => $reqConditionalDisplay,
            "Steigung" => $riseDisplay,
            "Dauer" => $durationDisplay . " Stunden",
            "Ausrüstung" => $equipmentDisplay,
            "Übernachtung" => $sleepOverDisplay,
            "Kartenmaterial" => $mapMaterialDisplay,
            "Online Route" => "<a href=\"" . $onlineMapDisplay . "\">" . $onlineMapDisplay . "</a>",
            "Zusätzliche Informationen" => $additionalInfoDisplay,
        );

        $signupInfo = array(
            "Kosten" => $costsDisplay,
            "Anmeldung bis" => $signupUntilDisplay,
            "Anmeldung bei" => $signupToDisplay
        );

        $rows = array(
            "Allgemeine Informationen" => $generalInfo,
            "Tourendetails" => $tourInfo,
            "Anmeldung" => $signupInfo
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
