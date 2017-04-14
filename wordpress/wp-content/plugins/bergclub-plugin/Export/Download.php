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
            && ($_GET['download'] == 'addresses' || $_GET['download'] == 'shipping' || $_GET['download'] == 'members' || $_GET['download'] == 'contributions' || $_GET['download'] == 'touren');
    }

    public function detectDownload(){
        if($this->hasDownload()){
            $method = "download" . Helpers::snakeToCamelCase($_GET['download'], true);
            if(method_exists($this, $method)){
                $this->$method();
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

    private function downloadTouren(){
        echo "downloadTouren";
        exit;
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
                $excel->getActiveSheet()->setCellValue($currentCol.$currentRow, $key);
            }

            $maxCol = $currentCol;
            $excel->getActiveSheet()->getStyle("A".$currentRow.":".$maxCol.$currentRow)->applyFromArray(array('font'=> array('bold'=>true,'size'=>11)));

            foreach($data as $row){
                $currentRow++;
                foreach($keys as $index => $key){
                    $currentCol = chr($index + 65);
                    $excel->getActiveSheet()->setCellValue($currentCol.$currentRow, $row[$key]);
                }
            }

            foreach(range('A', $maxCol) as $columnID) {
                $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
        }

        $excel->getActiveSheet()->setTitle($name);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $name . ' '.date("Y-m-d_H-i-s").'.xlsx"');
        header('Cache-Control: max-age=0');


        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }
}