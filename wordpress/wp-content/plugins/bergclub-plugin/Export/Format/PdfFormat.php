<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 14.05.2017
 * Time: 16:41
 */

namespace BergclubPlugin\Export\Format;


use BergclubPlugin\Export\Data\Generator;

class PdfFormat extends AbstractFormat
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \TCPDF
     */
    private $pdf;

    /**
     * @var Generator
     */
    private $dataGenerator;

    public function output(Generator $dataGenerator, $name)
    {
        $this->pdf = new \TCPDF();
        $this->name = $name;
        $this->dataGenerator = $dataGenerator;
        $this->createPDF();
        $this->createOutput();
    }

    private function createPDF(){
        $year = date("Y");
        if(isset($this->args['year'])){
            $year = $this->args['year'];
        }

        $data = $this->dataGenerator->getData();

        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetMargins(0,0,-1,false);
        $this->pdf->SetAutoPageBreak(false);
        $this->pdf->SetLineWidth(0.1);
        for($month = 1; $month < 13 ; $month++) {
            $calcDate = $year. '-' .$month . '-1';
            $currentY = 25;
            $this->pdf->AddPage();
            $this->pdf->Line(15, $currentY, 195, $currentY);
            $this->pdf->Image(__DIR__ . '/../assets/img/pdf-header.png', 15, 10, 45, 0, 'PNG');
            $this->pdf->setFontSize(16);
            $txt = $this->getMonthYear($year. '-' .$month . '-1');
            $textWidth = $this->pdf->GetStringWidth($txt);
            $this->pdf->Text(190-$textWidth, 11, $this->getMonthYear($calcDate));
            for($day = 1; $day <= date("t", strtotime($calcDate)); $day++){
                $calcDate = $year. '-' .$month . '-' .$day;
                if(date("w", strtotime($calcDate)) == 0){
                    $this->pdf->Rect(15, $currentY, 180, 8, 'F', null, [0, 0, 0, 5]);
                    $this->pdf->Line(15, $currentY, 195, $currentY);
                }
                $this->pdf->Line(15, $currentY + 8, 195, $currentY + 8);

                $this->pdf->Text(17, $currentY + 0.5, $this->getDayOfWeek($calcDate) . ",");
                $txt = $day . ".";
                $textWidth = $this->pdf->GetStringWidth($txt);
                $this->pdf->Text(35 - $textWidth, $currentY + 0.5, $txt);


                if(isset($data[date('Y-m-d', strtotime($calcDate))])){
                    $fontSize = 12;
                    $this->pdf->SetFontSize($fontSize);
                    $txt = html_entity_decode(join(' | ', $data[date('Y-m-d', strtotime($calcDate))]));
                    $textWidth = $this->pdf->GetStringWidth($txt);
                    while($textWidth > 193 - 40){
                        $fontSize -= 0.1;
                        $this->pdf->SetFontSize($fontSize);
                        $textWidth = $this->pdf->GetStringWidth($txt);
                    }
                    $offset = ((16 - $fontSize) * 0.2) + 0.5;
                    $this->pdf->Text(40, $currentY + $offset, $txt);
                }

                $this->pdf->SetFontSize(16);

                $currentY += 8;
            }
        }
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

    private function createOutput(){
        header('Cache-Control: max-age=0');

        $this->pdf->Output($this->name . ' '.date("Y-m-d_H-i-s").'.pdf', 'I');
    }
}