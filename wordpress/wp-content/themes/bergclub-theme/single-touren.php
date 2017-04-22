<?php

get_header(); ?>
    <?php
    while (have_posts()) : the_post();
        $date_from = get_post_meta(get_the_ID(), "_dateFrom", true);
        $date_to =  get_post_meta(get_the_ID(), "_dateTo", true);
        if(!empty($date_to) && $date_to != $date_from){
            $dateDisplay = date("d.m.", strtotime($date_from)) . " - " . date("d.m.y", strtotime($date_to));
        } else {
            $dateDisplay = date("d.m.y", strtotime($date_from));
        }
        $type = get_post_meta(get_the_ID(), "_type", true);
        $reqTechnical = get_post_meta(get_the_ID(), "_requirementsTechnical", true);
        $typeDisplay = bcb_touren_meta(get_the_ID(), 'type') . ", " . $reqTechnical;
        $leaderId = get_post_meta(get_the_ID(), "_leader", true);
        $leaderDisplay = "";
        if(!empty($leaderId)){
            $leaderDisplay = bcb_get_user_full_name($leaderId);
        }
        $coLeaderId = get_post_meta(get_the_ID(), "_coLeader", true);
        if(!empty($coLeaderId)){
            $coLeaderFullName = bcb_get_user_full_name($coLeaderId);
            $leaderDisplay .= ", " . $coLeaderFullName . " (Co-Leiter)";
        }
        $programDisplay = get_post_meta(get_the_ID(), "_program", true);


        $reqConditionalId = get_post_meta(get_the_ID(), "_requirementsConditional", true);
        $reqConditionalDisplay = "";
        if($reqConditionalId == 1){
            $reqConditionalDisplay = "Leicht";
        }elseif($reqConditionalId == 2){
            $reqConditionalDisplay = "Mittel";
        }elseif($reqConditionalId == 3){
            $reqConditionalDisplay = "Schwer";
        };
        $riseUp = get_post_meta(get_the_ID(), "_riseUpMeters", true);
        $riseDown = get_post_meta(get_the_ID(), "_riseDownMeters", true);
        if(empty($riseUp) && empty($riseDown)){
            $riseDisplay = "";
        } else {
            $riseDisplay = "<div class=\"icon icon-up\" title=\"Hinauf\"></div>" . $riseUp . " <div class=\"icon icon-down\" title=\"Hinab\"></div>" . $riseDown;
        }
        $durationDisplay = get_post_meta(get_the_ID(), "_duration", true);
        if(!empty($durationDisplay)){
            $durationDisplay .= " Stunden";
        }
        $equipmentDisplay = get_post_meta(get_the_ID(), "_equipment", true);
        $sleepOverDisplay = get_post_meta(get_the_ID(), "_sleepOver", true);
        $mapMaterialDisplay = get_post_meta(get_the_ID(), "_mapMaterial", true);
        $onlineMapDisplay = get_post_meta(get_the_ID(), "_onlineMap", true);
        $additionalInfoDisplay = get_post_meta(get_the_ID(), "_additionalInfo", true);


        $costsDisplay = get_post_meta(get_the_ID(), "_costs", true) . " Franken";
        $costsForDisplay = get_post_meta(get_the_ID(), "_costsFor", true);

        $signupUntil = get_post_meta(get_the_ID(), "_signupUntil", true);
        $signupUntilDisplay = empty($signupUntil) ? "" : date("d.m.y", strtotime($signupUntil));
        $signupToId = get_post_meta(get_the_ID(), "_signupTo", true);
        $signupToDisplay = "";
        if(!empty($signupToId)) {
            $signupToDisplay = bcb_get_user_full_name($signupToId);
        }

        $generalInfo = array(
            "Datum" => $dateDisplay,
            "Typ" => $typeDisplay,
            "Tourenleiter" => $leaderDisplay,
            "Program" => $programDisplay,
        );

        $tourInfo = array(
            "Konditionelle Anforderungen" => $reqConditionalDisplay,
            "Steigung" => $riseDisplay,
            "Dauer" => $durationDisplay,
            "Ausrüstung" => $equipmentDisplay,
            "Übernachtung" => $sleepOverDisplay,
            "Kartenmaterial" => $mapMaterialDisplay,
            "Online Route" => "<a href=\"" . $onlineMapDisplay . "\">" . $onlineMapDisplay . "</a>",
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
