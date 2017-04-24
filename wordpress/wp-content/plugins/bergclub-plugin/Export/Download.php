<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 12.04.2017
 * Time: 17:07
 */

namespace BergclubPlugin\Export;


use BergclubPlugin\MVC\Helpers;
use BergclubPlugin\MVC\Models\Option;
use BergclubPlugin\MVC\Models\User;

class Download
{
    private function hasDownload(){
        return
            isset($_GET['page']) && $_GET['page'] == 'bergclubplugin-export-controllers-maincontroller' && isset($_GET['download'])
            && ($_GET['download'] == 'addresses'
                || $_GET['download'] == 'shipping'
                || $_GET['download'] == 'members'
                || $_GET['download'] == 'contributions'
                || $_GET['download'] == 'touren'
                || $_GET['download'] == 'calendar'
                || $_GET['download'] == 'pfarrblatt'
                || $_GET['download'] == 'program');
    }

    public function detectDownload(){
        if($this->hasDownload()){
            if($this->checkRights()) {
                $method = "download" . Helpers::snakeToCamelCase($_GET['download'], true);
                if (method_exists($this, $method)) {
                    set_time_limit (0);
                    $this->$method();
                }
            }
        }
    }

    private function downloadAddresses(){
        $this->downloadExcel('Versandliste Komplett', $this->dataAddresses('adresses'));
    }

    private function downloadShipping(){
        $this->downloadExcel('Versandliste Programm', $this->dataAddresses('program_shipment'));
    }

    private function downloadContributions(){
        $this->downloadExcel('Beitragsliste', $this->dataAddresses('contributions'));
    }

    private function downloadTouren(){
        $data = $this->getTourenData($_GET['status'], $_GET['from'], $_GET['to']);
        $this->downloadExcel('Touren', $data);
    }

    private function downloadCalendar(){

        $year = date("Y");
        if(isset($_GET['year'])){
            $year = $_GET['year'];
        }


        $data = $this->getCalendarData($year);

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(0,0,-1,false);
        $pdf->SetAutoPageBreak(false);
        $pdf->SetLineWidth(0.1);
        for($month = 1; $month < 13 ; $month++) {
            $calcDate = $year. '-' .$month . '-1';
            $currentY = 25;
            $pdf->AddPage();
            $pdf->Line(15, $currentY, 195, $currentY);
            $pdf->Image(__DIR__ . '/assets/img/pdf-header.png', 15, 10, 45, 0, 'PNG');
            $pdf->setFontSize(16);
            $txt = $this->getMonthYear($year. '-' .$month . '-1');
            $textWidth = $pdf->GetStringWidth($txt);
            $pdf->Text(190-$textWidth, 11, $this->getMonthYear($calcDate));
            for($day = 1; $day <= date("t", strtotime($calcDate)); $day++){
                $calcDate = $year. '-' .$month . '-' .$day;
                if(date("w", strtotime($calcDate)) == 0){
                    $pdf->Rect(15, $currentY, 180, 8, 'F', null, [0, 0, 0, 5]);
                    $pdf->Line(15, $currentY, 195, $currentY);
                }
                $pdf->Line(15, $currentY + 8, 195, $currentY + 8);

                $pdf->Text(17, $currentY + 0.5, $this->getDayOfWeek($calcDate) . ",");
                $txt = $day . ".";
                $textWidth = $pdf->GetStringWidth($txt);
                $pdf->Text(35 - $textWidth, $currentY + 0.5, $txt);


                if(isset($data[date('Y-m-d', strtotime($calcDate))])){
                    $fontSize = 12;
                    $pdf->SetFontSize($fontSize);
                    $txt = html_entity_decode(join(' | ', $data[date('Y-m-d', strtotime($calcDate))]));
                    $textWidth = $pdf->GetStringWidth($txt);
                    while($textWidth > 193 - 40){
                        $fontSize -= 0.1;
                        $pdf->SetFontSize($fontSize);
                        $textWidth = $pdf->GetStringWidth($txt);
                    }
                    $offset = ((16 - $fontSize) * 0.2) + 0.5;
                    $pdf->Text(40, $currentY + $offset, $txt);
                }

                $pdf->SetFontSize(16);

                $currentY += 8;
            }
        }

        $this->createPDFDownload($pdf, 'Kalender');
    }

    private function downloadPfarrblatt(){
        $data = $this->getPfarrblattData($_GET['from'], $_GET['to']);
        $word = new \PhpOffice\PhpWord\PhpWord();
        $section = $word->addSection();
        $section->addText(
            $data,
            ['name' => 'Courier New', 'size' => 11]
        );

        $word->save('Pfarrblatt '.date("Y-m-d_H-i-s").'.docx', 'Word2007', true);
        exit;
    }

    private function downloadProgram(){
        $data = [];
        $posts = get_posts([
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => 'touren',
            'order' => 'ASC',
            'orderby' => '_dateFromDB',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_dateFromDB',
                    'value' => date('Y-m-d', strtotime($_GET['touren-from'])),
                    'type' => 'DATE',
                    'compare' => '>='
                ],
                [
                    'key' => '_dateFromDB',
                    'value' => date('Y-m-d', strtotime($_GET['touren-to'])),
                    'type' => 'DATE',
                    'compare' => '<='
                ],
            ],
        ]);

        foreach($posts as $post){
            $dateFrom = bcb_touren_meta($post->ID, 'dateFrom');
            $dateTo = bcb_touren_meta($post->ID, 'dateTo');

            $dateDisplayWeekday = $this->getDayOfWeek($dateFrom);
            if(!empty($dateTo) && $dateTo != $dateFrom){
                $dateDisplayWeekday .= " - " . $this->getDayOfWeek($dateTo);
            }

            $data[] = [
                'dateDisplayShort' => $this->oneLine(bcb_touren_meta($post->ID, "dateDisplayShort")),
                'dateDisplayWeekday' => $this->oneLine($dateDisplayWeekday),
                'title' => $this->oneLine(get_the_title($post)),
                'type' => $this->oneLine(bcb_touren_meta($post->ID, "type")),
                'requirementsTechnical' => $this->oneLine(bcb_touren_meta($post->ID, "requirementsTechnical")),
                'requirementsConditional' => $this->oneLine(bcb_touren_meta($post->ID, "requirementsConditional")),
                'riseUpMeters' => $this->oneLine(bcb_touren_meta($post->ID, 'riseUpMeters')),
                'riseDownMeters' => $this->oneLine(bcb_touren_meta($post->ID, 'riseDownMeters')),
                'duration' => $this->oneLine(bcb_touren_meta($post->ID, 'duration')),
                'meetpoint' => $this->oneLine(bcb_touren_meta($post->ID, 'meetpoint')),
                'meetingPointTime' => $this->oneLine(bcb_touren_meta($post->ID, 'meetingPointTime')),
                'returnBack' => $this->oneLine(bcb_touren_meta($post->ID, 'returnBack')),
                'costs' => $this->oneLine(bcb_touren_meta($post->ID, 'oosts')),
                'costsFor' => $this->oneLine(bcb_touren_meta($post->ID, 'costsFor')),
                'signupUntil' => $this->oneLine(bcb_touren_meta($post->ID, 'signupUntil')),
                'signUpTo' => $this->oneLine(bcb_touren_meta($post->ID, 'signupTo')),
                'additionalInfo' => $this->multiLine(bcb_touren_meta($post->ID, 'additionalInfo')),
                'equipment' => $this->multiLine(bcb_touren_meta($post->ID, 'equipment')),
                'food' => $this->oneLine(bcb_touren_meta($post->ID, 'food')),
                'mapMaterial' => $this->oneLine(bcb_touren_meta($post->ID, 'mapMaterial')),
            ];
        }

        $anmeldeTermine = [];
        foreach($data as $entry) {
            if(!empty($entry['signupUntil'])){
                $anmeldeTermine[date('Y-m-d', strtotime($entry['signupUntil']))][] = [
                    'signupUntil' => date('d.m.', strtotime($entry['signupUntil'])),
                    'info' => $entry['title'] . ' (' . $entry['type'] . ', ' . $entry['dateDisplayShort'] . ')',
                ];
            }
        }
        ksort($anmeldeTermine);

        $word = new \PhpOffice\PhpWord\PhpWord();
        $header = array('name' => 'Arial', 'size' => 16, 'bold' => true);
        $content = array('name' => 'Arial', 'size' => 14, 'bold' => false);
        $contentBold = array('name' => 'Arial', 'size' => 14, 'bold' => true);
        $contentSmall = array('name' => 'Arial', 'size' => 12, 'bold' => false);
        $contentSmallItalic = array('name' => 'Arial', 'size' => 12, 'bold' => false, 'italic' => true);

        $section = $word->addSection();
        $section->addText('Bergclub Bern', $header);
        $section->addText('Anmeldetermine', $header);

        $table = $section->addTable();

        foreach($anmeldeTermine as $arr){
            foreach($arr as $entry) {
                $table->addRow();
                $table->addCell(1000)->addText($entry['signupUntil'], $content);
                $table->addCell(600)->addText('für', $content);
                $table->addCell(7500)->addText($entry['info'], $content);
            }
        }

        $section->addText('', $header);
        $section->addText('Tourenübersicht', $header);

        $table = $section->addTable();
        foreach($data as $entry){
            $table->addRow();
            $table->addCell(2100)->addText($entry['dateDisplayShort'], $content);
            $textRun = $table->addCell(7000)->addTextRun();
            $textRun->addText($entry['title'], $contentBold);
            if(!empty($entry['type'])) {
                $textRun->addText(' (' . $entry['type'] . ')', $contentSmall);
            }
        }

        $section->addText('', $header);
        $section->addText('Tourendetails', $header);

        $table = $section->addTable();
        foreach($data as $entry){
            $table->addRow();
            $table->addCell(2100)->addText($entry['dateDisplayShort'], $contentBold);
            $table->addCell(7000)->addText($entry['title'], $contentBold);
            $table->addRow();
            $table->addCell(2100)->addText($entry['dateDisplayWeekday'], $content);
            $table->addCell(7000)->addText($entry['type'], $content);
            if(!empty($entry['meetpoint'])){
                $table->addRow();
                $table->addCell(2100)->addText('Treffpunkt', $contentSmallItalic);
                $meetpoint = $entry['meetpoint'];
                if(!empty($entry['meetingPointTime'])){
                    $meetpoint .= ', ' . $entry['meetingPointTime'] . ' Uhr';
                }
                $table->addCell(7000)->addText($meetpoint, $contentSmall);
            }

            if(!empty($entry['requirementsTechnical']) || !empty($entry['requirementsConditional']) || !empty($entry['riseUpMeters']) || !empty($entry['riseDownMeters']) || !empty($entry['duration'])){
                $table->addRow();
                $table->addCell(2100)->addText('Anforderungen', $contentSmallItalic);
                $cell = $table->addCell(7000);
                $subTable = $cell->addTable();
                if(!empty($entry['requirementsTechnical'])) {
                    $subTable->addRow();
                    $subTable->addCell(1500)->addText('technisch:', $contentSmallItalic);
                    $subTable->addCell(5500)->addText($entry['requirementsTechnical'], $contentSmall);
                }
                if(!empty($entry['requirementsConditional'])) {
                    $subTable->addRow();
                    $subTable->addCell(1500)->addText('konditionell:', $contentSmallItalic);
                    $subTable->addCell(5500)->addText($entry['requirementsConditional'], $contentSmall);
                }
                if(!empty($entry['riseUpMeters'])) {
                    $subTable->addRow();
                    $subTable->addCell(1500)->addText('Aufstieg:', $contentSmallItalic);
                    $subTable->addCell(5500)->addText($entry['riseUpMeters'], $contentSmall);
                }
                if(!empty($entry['riseDownMeters'])) {
                    $subTable->addRow();
                    $subTable->addCell(1500)->addText('Abstieg:', $contentSmallItalic);
                    $subTable->addCell(5500)->addText($entry['riseDownMeters'], $contentSmall);
                }


            }

            if(!empty($entry['additionalInfo'])) {
                if($entry["title"] == "Sommerlager Bergclub Jugend"){
                    print_r($entry['additionalInfo']);
                    exit;
                }
                $lines = $entry['additionalInfo'];
                $table->addRow();
                $table->addCell(2100)->addText('Besonderes', $contentSmallItalic);
                $textrun = $table->addCell(7000)->addTextRun();
                $textrun->addText(array_shift($lines), $contentSmall);
                foreach($lines as $line){
                    $textrun->addTextBreak();
                    $textrun->addText($line, $contentSmall);
                }
            }

            if(!empty($entry['equipment'])) {
                $lines = $entry['equipment'];
                $table->addRow();
                $table->addCell(2100)->addText('Ausrüstung', $contentSmallItalic);
                $textrun = $table->addCell(7000)->addTextRun();
                $textrun->addText(array_shift($lines), $contentSmall);
                foreach($lines as $line){
                    $textrun->addTextBreak();
                    $textrun->addText($line, $contentSmall);
                }
            }

            if(!empty($entry['mapMaterial'])) {
                $table->addRow();
                $table->addCell(2100)->addText('Kartenmaterial', $contentSmallItalic);
                $table->addCell(7000)->addText($entry['mapMaterial'], $contentSmall);
            }

            if(!empty($entry['food'])) {
                $table->addRow();
                $table->addCell(2100)->addText('Verpflegung', $contentSmallItalic);
                $table->addCell(7000)->addText($entry['food'], $contentSmall);
            }

            if(!empty($entry['costs'])) {
                $costs = "CHF " . $entry['costs'];
                if(!empty($entry['costsFor'])){
                    $costs .= " für " . $entry['costsFor'];
                }
                $table->addRow();
                $table->addCell(2100)->addText('Kosten', $contentSmallItalic);
                $table->addCell(7000)->addText($costs, $contentSmall);
            }

            if(!empty($entry['returnBack'])) {
                $table->addRow();
                $table->addCell(2100)->addText('Rückkehr', $contentSmallItalic);
                $table->addCell(7000)->addText($entry['returnBack'], $contentSmall);
            }

            if(!empty($entry['signupUntil'])) {
                $signup = "bis " . $entry['signupUntil'];
                if(!empty($entry['signupTo'])){
                    $signup .= " an " . $entry['signupTo'];
                }
                $table->addRow();
                $table->addCell(2100)->addText('Anmeldung', $contentSmallItalic);
                $table->addCell(7000)->addText($signup, $contentSmall);
            }

            $table->addRow();
            $table->addCell(2100)->addText('', $contentSmall);
            $table->addCell(7000)->addText('', $contentSmall);
        }

        $word->save('Quartalsprogramm '.date("Y-m-d_H-i-s").'.docx', 'Word2007', true);
        exit;
    }

    private function oneLine($string){
        return trim(str_replace("\n", "", str_replace("<br />", " ", $this->removeCarriageReturn($string))));
    }

    private function multiLine($string){
        $lines = explode("<br />", str_replace("\n", "", $this->removeCarriageReturn($string)));
        if(count($lines) == 1 && empty($lines[0])){
            return [];
        }
        return $lines;
    }

    private function removeCarriageReturn($string){
        return str_replace("\r", "", $string);
    }

    private function ordutf8($string, &$offset) {
        $code = ord(substr($string, $offset,1));
        if ($code >= 128) {        //otherwise 0xxxxxxx
            if ($code < 224) $bytesnumber = 2;                //110xxxxx
            else if ($code < 240) $bytesnumber = 3;        //1110xxxx
            else if ($code < 248) $bytesnumber = 4;    //11110xxx
            $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
            for ($i = 2; $i <= $bytesnumber; $i++) {
                $offset ++;
                $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
                $codetemp = $codetemp*64 + $code2;
            }
            $code = $codetemp;
        }
        $offset += 1;
        if ($offset >= strlen($string)) $offset = -1;
        return $code;
    }

    private function getPfarrblattDate($from, $to){
        $weekday = [
            'Sonntag',
            'Montag',
            'Dienstag',
            'Mittwoch',
            'Donnerstag',
            'Freitag',
            'Samstag',
        ];

        $month = [
            'Januar',
            'Februar',
            'März',
            'April',
            'Mai',
            'Juni',
            'Juli',
            'August',
            'September',
            'Oktober',
            'November',
            'Dezember',
        ];

        $tmFrom = strtotime($from);

        if(empty($to) || $to == $from){
            return $weekday[date('w', $tmFrom)] . ', ' . date('j', $tmFrom) . '. ' . $month[date('n', $tmFrom) - 1];
        }else{
            $tmTo = strtotime($to);
            $result = $weekday[date('w', $tmFrom)] . '/' . $weekday[date('w', $tmTo)] . ", ";
            $monthFrom = $month[date('n', $tmFrom) - 1];
            $monthTo = $month[date('n', $tmTo) - 1];
            if($monthFrom == $monthTo){
                $result .= date('j', $tmFrom) . './' . date('j', $tmTo) . '. ' . $monthFrom;
            }else{
                $result .= date('j', $tmFrom) . '. ' . $monthFrom . '/' . date('j', $tmTo) . '. ' . $monthTo;
            }

            return $result;
        }
    }

    private function getPfarrblattData($from, $to){
        $data = [];

        $posts = get_posts([
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => 'touren',
            'order' => 'ASC',
            'orderby' => '_dateFromDB',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_dateFromDB',
                    'value' => date('Y-m-d', strtotime($from)),
                    'type' => 'DATE',
                    'compare' => '>='
                ],
                [
                    'key' => '_dateFromDB',
                    'value' => date('Y-m-d', strtotime($to)),
                    'type' => 'DATE',
                    'compare' => '<='
                ],
            ],
        ]);

        foreach($posts as $post){
            $item = $this->getPfarrblattDate(bcb_touren_meta($post->ID, "dateFrom"), bcb_touren_meta($post->ID, "dateTo")). ": ";
            $item .= bcb_touren_meta($post->ID, "type") . ", ";
            $item .= get_the_title($post). ", Anmeldung an: " . bcb_touren_meta($post->ID, "signUpTo");
            $data[] = $item;
        }

        return join('. ', $data);
    }

    private function getTourenData($status, $from, $to){
        $data = [];

        $posts = get_posts([
            'posts_per_page' => -1,
            'post_status' => $status,
            'post_type' => 'touren',
            'order' => 'ASC',
            'orderby' => '_dateFromDB',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_dateFromDB',
                    'value' => date('Y-m-d', strtotime($from)),
                    'type' => 'DATE',
                    'compare' => '>='
                ],
                [
                    'key' => '_dateFromDB',
                    'value' => date('Y-m-d', strtotime($to)),
                    'type' => 'DATE',
                    'compare' => '<='
                ],
            ],
        ]);

        foreach($posts as $post){
            $data[] = [
                'Datum von' => bcb_touren_meta($post->ID, "dateFrom"),
                'Datum bis' => bcb_touren_meta($post->ID, "dateTo"),
                'Titel' => get_the_title($post),
                'Leiter' => bcb_touren_meta($post->ID, 'leader'),
                'Co-Leiter' => bcb_touren_meta($post->ID, 'coLeader'),
                'Art' => bcb_touren_meta($post->ID, "type"),
                'Schwierigkeit' => bcb_touren_meta($post->ID, "requirementsTechnical"),
                'Konditionell' => bcb_touren_meta($post->ID, "requirementsConditional"),
                'Training' => bcb_touren_meta($post->ID, 'training'),
                'J+S Event' => bcb_touren_meta($post->ID, 'jsEvent'),
                'Aufstieg' => bcb_touren_meta($post->ID, 'riseUpMeters'),
                'Abstieg' => bcb_touren_meta($post->ID, 'riseDownMeters'),
                'Dauer' => bcb_touren_meta($post->ID, 'duration'),
                'Treffpunkt' => bcb_touren_meta($post->ID, 'meetpoint'),
                'Zeit' => bcb_touren_meta($post->ID, 'meetingpointTime'),
                'Rückkehr' => bcb_touren_meta($post->ID, 'returnBack'),
                'Kosten' => bcb_touren_meta($post->ID, 'oosts'),
                'Kosten für' => bcb_touren_meta($post->ID, 'costsFor'),
                'Anmeldung bis' => bcb_touren_meta($post->ID, 'signupUntil'),
                'Anmeldung an' => bcb_touren_meta($post->ID, 'signupTo'),
            ];
        }

        return $data;
    }

    private function getCalendarData($year){
        $data = [];

        $posts = get_posts([
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => 'touren',
            'order' => 'ASC',
            'orderby' => '_dateFromDB',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_dateFromDB',
                    'value' => $year . '-01-01',
                    'type' => 'DATE',
                    'compare' => '>='
                ],
                [
                    'key' => '_dateToDB',
                    'value' => $year . '-12-31',
                    'type' => 'DATE',
                    'compare' => '<='
                ],
            ],
        ]);

        foreach($posts as $post){
            $title = get_the_title($post);
            $date_from = bcb_touren_meta($post->ID, "dateFrom");
            $date_to =  bcb_touren_meta($post->ID, "dateTo");
            $type = bcb_touren_meta($post->ID, "type");
            $reqTechnical = bcb_touren_meta($post->ID, "requirementsTechnical");

            if(!empty($date_from)) {
                $item = [];
                if (!empty($type)) {
                    $item['type'] = $type;
                    if (!empty($reqTechnical)) {
                        $item['req_technical'] = $reqTechnical;
                    }
                }

                if (bcb_touren_meta($post->ID, "isSeveralDays")) {
                    $item['date_to'] = "bis " . date("d.m.", strtotime($date_to));
                }

                $data[date('Y-m-d', strtotime($date_from))][] = $title . " (" . join(', ', $item) .")";
            }
        }

        return $data;
    }

    private function getMonthYear($date){
        $months = [
            'Januar',
            'Februar',
            'März',
            'April',
            'Mai',
            'Juni',
            'Juli',
            'August',
            'September',
            'Oktober',
            'November',
            'Dezember',
        ];

        return $months[date("n", strtotime($date))-1]." ".date("Y", strtotime($date));
    }

    private function getDayOfWeek($date){
        $days = [
            'So',
            'Mo',
            'Di',
            'Mi',
            'Do',
            'Fr',
            'Sa',
        ];
        return $days[date("w", strtotime($date))];
    }

    private function dataAddresses($type = "addresses"){
        $data = [];
        $users = [];
        if($type == "contributions") {
            $users = User::findMitgliederWithoutSpouse();
        }else{
            $users = User::findAllWithoutSpouse();
        }
        foreach($users as $user) {
            /* @var User $user */
            if ($type != "program_shipment" || $user->program_shipment) {

                $currentIndex = 0;
                $row = [];
                for($i = 1; $i < 7; $i++){
                    $row["Adresszeile " . $i] = "";
                }
                $role = $user->address_role->getKey();
                if ($user->company) {
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = $user->company;
                }

                /* @var User $spouse */
                $spouse = $user->spouse;

                if ($user->first_name && $user->last_name) {
                    if (empty($spouse)) {
                        if ($user->company) {
                            $currentIndex++;
                            $row["Adresszeile " . $currentIndex] = trim($user->gender . ' ' . $user->first_name . ' ' . $user->last_name);
                        } else {
                            if ($user->gender) {
                                $currentIndex++;
                                $row["Adresszeile " . $currentIndex] = $user->gender;
                            }
                            $currentIndex++;
                            $row["Adresszeile " . $currentIndex] = trim($user->first_name . ' ' . $user->last_name);
                        }
                    } else {
                        if ($user->last_name == $spouse->last_name) {
                            $gender = $user->gender;
                            if ($user->gender == $spouse->gender) {
                                $gender .= "en";
                            } else {
                                $gender .= " & " . $spouse->gender;
                            }
                            $currentIndex++;
                            $row["Adresszeile " . $currentIndex] = $gender;
                            $currentIndex++;
                            $row["Adresszeile " . $currentIndex] = $user->first_name . ' & ' . $spouse->firstname . ' ' . $user->last_name;
                        } else {
                            $currentIndex++;
                            $row["Adresszeile " . $currentIndex] = trim($user->gender . ' ' . $user->first_name . ' ' . $user->last_name);
                            $currentIndex++;
                            $row["Adresszeile " . $currentIndex] = trim($spouse->gender . ' ' . $spouse->first_name . ' ' . $spouse->last_name);
                        }
                    }
                }

                if ($user->address_addition) {
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = $user->addressAddition;
                }

                $currentIndex++;
                $row["Adresszeile " . $currentIndex] = $user->street;

                $currentIndex++;
                $row["Adresszeile " . $currentIndex] = trim($user->zip . ' ' . $user->location);

                if($type == "contributions"){
                    $contributions = Option::get('mitgliederbeitraege');
                    $contributionType =  $contributions['bcb']['name'];
                    $contributionAmount = $contributions['bcb']['amount'];

                    if(!empty($spouse)){
                        $contributionType =  $contributions['ehepaar']['name'];
                        $contributionAmount = $contributions['ehepaar']['amount'];
                    }elseif($user->address_role->getKey() == 'bcb_aktivmitglied_jugend'){
                        $contributionType =  $contributions['jugend']['name'];
                        $contributionAmount = $contributions['jugend']['amount'];
                    }

                    $currentIndex++;
                    $row["Beitragstyp"] = $contributionType;
                    $currentIndex++;
                    $row["Betrag"] = number_format($contributionAmount, 2, '.', '');
                }
                $data[] = $row;
            }
        }
        return $data;
    }

    private function downloadMembers(){
        $data = [];
        $users = User::findMitglieder();
        foreach($users as $user){
            /* @var User $user */
            $row['Typ'] = $user->address_role_name;
            $row['Anrede'] = $user->gender;
            $row['Nachname'] = $user->last_name;
            $row['Vorname'] = $user->first_name;
            $row['Zusatz'] = $user->address_addition;
            $row['Strasse'] = $user->street;
            $row['PLZ'] = $user->zip;
            $row['Ort'] = $user->location;
            $row['Telefon (P)'] = $user->phone_private;
            $row['Telefon (G)'] = $user->phone_work;
            $row['Telefon (M)'] = $user->phone_mobile;
            $row['Email'] = $user->email;
            $row['Geburtsdatum'] = $user->birthdate;
            $row['Ehepartner'] = "";

            /* @var User $spouse */
            $spouse = $user->spouse;
            if($spouse){
                $row["Ehepartner"] = $spouse->lastname . ' ' . $spouse->firstname;
            }

            $roles = $user->functionary_roles;
            $arr = [];
            foreach($roles as $role){
                /* @var \BergclubPlugin\MVC\Models\Role $role */
                $arr[] = $role->getName();
            }

            $row['Funktionen'] = join(', ', $arr);
            $data[] = $row;
        }

        $this->downloadExcel('Mitglieder', $data);
    }

    private function downloadExcel($name, $data){
        $excel = new \PHPExcel();

        $excel->setActiveSheetIndex(0);

        if(!empty($data)){
            $currentRow = 1;
            $currentCol = "A";
            $keys = array_keys($data[0]);
            foreach($keys as $index => $key){
                $currentCol = $this->getExcelColFromIndex($index);
                $excel->getActiveSheet()->setCellValue($currentCol.$currentRow, html_entity_decode($key));
            }

            $maxCol = $currentCol;
            $excel->getActiveSheet()->getStyle("A".$currentRow.":".$maxCol.$currentRow)->applyFromArray(array('font'=> array('bold'=>true,'size'=>11)));

            foreach($data as $row){
                $currentRow++;
                foreach($keys as $index => $key){
                    $currentCol = $this->getExcelColFromIndex($index);
                    $excel->getActiveSheet()->setCellValue($currentCol.$currentRow, html_entity_decode($row[$key]));
                }
            }

            foreach(range('A', $maxCol) as $columnID) {
                $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
        }

        $excel->getActiveSheet()->setTitle($name);

        $this->createExcelDownload($excel, $name);
    }

    private function getExcelColFromIndex($index){
        $col = "";
        if($index > 25){
            $col = "A";
            $index -= 26;
        }
        $col .= chr($index + 65);
        return $col;
    }

    private function createExcelDownload(\PHPExcel $excel, $name){
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $name . ' '.date("Y-m-d_H-i-s").'.xlsx"');
        header('Cache-Control: max-age=0');


        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    private function createPDFDownload(\TCPDF $pdf, $name){
        header('Cache-Control: max-age=0');


        $pdf->Output($name . ' '.date("Y-m-d_H-i-s").'.pdf', 'I');
        exit;
    }

    private function checkRights(){
        $currentUser = User::findCurrent();
        if($_GET['download'] != "calendar") {
            if (!$currentUser || !$currentUser->hasCapability('export')) {
                return false;
            }

            if(!$currentUser->hasCapability('export_adressen') && ($_GET['download'] == 'shipping' || $_GET['download'] == 'members' || $_GET['download'] == 'contributions')){
                return false;
            }

            if(!$currentUser->hasCapability('export_touren') && $_GET['download'] == 'touren'){
                return false;
            }

            if(!$currentUser->hasCapability('export_druck') && ($_GET['download'] == 'pfarrblatt' || $_GET['download'] == 'program')){
                return false;
            }
        }
        return true;
    }
}