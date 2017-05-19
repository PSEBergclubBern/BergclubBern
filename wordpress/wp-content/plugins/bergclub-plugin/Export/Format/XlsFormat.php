<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 14.05.2017
 * Time: 16:41
 */

namespace BergclubPlugin\Export\Format;


use BergclubPlugin\Export\Data\Generator;

/**
 * Creates Excel document downloads (xlsx, open office format)
 * @package BergclubPlugin\Export\Format
 */
class XlsFormat extends AbstractFormat
{
    /**
     * @var string the name used for the download filename.
     */
    private $name;

    /**
     * @var \PHPExcel the excel processor
     */
    private $excel;

    /**
     * @var Generator the data generator
     */
    private $dataGenerator;

    /**
     * Creates an Excel file download.
     *
     * @param Generator $dataGenerator the data generator to use
     * @param string $name the file name (date and time will be added).
     */
    public function output(Generator $dataGenerator, $name)
    {
        $this->excel = new \PHPExcel();
        $this->name = $name;
        $this->dataGenerator = $dataGenerator;
        $this->createExcel();
        $this->createOutput();
    }

    private function createExcel()
    {
        $data = $this->dataGenerator->getData();

        $this->excel->setActiveSheetIndex(0);

        if (!empty($data)) {
            $currentRow = 1;
            $currentCol = "A";
            $keys = array_keys($data[0]);
            foreach ($keys as $index => $key) {
                $currentCol = $this->getExcelColFromIndex($index);
                $this->excel->getActiveSheet()->setCellValue($currentCol . $currentRow, html_entity_decode($key));
            }

            $maxCol = $currentCol;
            $this->excel->getActiveSheet()->getStyle("A" . $currentRow . ":" . $maxCol . $currentRow)->applyFromArray(array('font' => array('bold' => true, 'size' => 11)));

            foreach ($data as $row) {
                $currentRow++;
                foreach ($keys as $index => $key) {
                    $currentCol = $this->getExcelColFromIndex($index);
                    $this->excel->getActiveSheet()->setCellValue($currentCol . $currentRow, html_entity_decode($row[$key]));
                }
            }

            foreach (range('A', $maxCol) as $columnID) {
                $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
        }

        $this->excel->getActiveSheet()->setTitle($this->name);
    }

    private function getExcelColFromIndex($index)
    {
        $col = "";
        if ($index > 25) {
            $col = "A";
            $index -= 26;
        }
        $col .= chr($index + 65);
        return $col;
    }

    private function createOutput()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->name . ' ' . date("Y-m-d_H-i-s") . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $writer->save('php://output');
    }
}