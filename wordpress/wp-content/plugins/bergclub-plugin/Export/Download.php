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
            && ($_GET['download'] == 'addresses' || $_GET['download'] == 'shipping' || $_GET['download'] == 'members' || $_GET['download'] == 'contributions' || $_GET['download'] == 'touren' || $_GET['download'] == 'calendar');
    }

    public function detectDownload(){
        if($this->hasDownload()){
            if($_GET['download'] == "calendar" || $this->checkRights()) {
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
                'Art' => bcb_touren_meta($post->ID, "type"),
                'Schwierigkeit' => bcb_touren_meta($post->ID, "requirementsTechnical"),
                'Konditionell' => bcb_touren_meta($post->ID, "requirementsConditional"),
                'Training' => bcb_touren_meta($post->ID, 'training'),
                'J+S Event' => bcb_touren_meta($post->ID, 'jsEvent'),
                'Aufstieg' => bcb_touren_meta($post->ID, 'riseUpMeters'),
                'Abstieg' => bcb_touren_meta($post->ID, 'riseDownMeters'),
                'Dauer' => bcb_touren_meta($post->ID, 'duration'),
                'Leiter' => bcb_touren_meta($post->ID, 'leader'),
                'Co-Leiter' => bcb_touren_meta($post->ID, 'coLeader'),
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
                $currentCol = chr($index + 65);
                $excel->getActiveSheet()->setCellValue($currentCol.$currentRow, html_entity_decode($key));
            }

            $maxCol = $currentCol;
            $excel->getActiveSheet()->getStyle("A".$currentRow.":".$maxCol.$currentRow)->applyFromArray(array('font'=> array('bold'=>true,'size'=>11)));

            foreach($data as $row){
                $currentRow++;
                foreach($keys as $index => $key){
                    $currentCol = chr($index + 65);
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
        if(!$currentUser || !$currentUser->hasCapability('export')){
            return false;
        }
        return true;
    }
}