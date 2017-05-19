<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 14.05.2017
 * Time: 16:41
 */

namespace BergclubPlugin\Export\Format;


use BergclubPlugin\Export\Data\Generator;
use PhpOffice\PhpWord\PhpWord;

class DocxFormat extends AbstractFormat
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var PhpWord
     */
    private $word;

    /**
     * @var Generator
     */
    private $dataGenerator;

    public function output(Generator $dataGenerator, $name)
    {
        $this->word = new PhpWord();
        $this->name = $name;
        $this->dataGenerator = $dataGenerator;
        if ($this->args['download'] == "pfarrblatt.docx") {
            $this->createPfarrblatt();
        } else {
            $this->createProgram();
        }
        $this->createOutput();
    }

    private function createPfarrblatt()
    {
        $data = join('. ', $this->dataGenerator->getData());
        $section = $this->word->addSection();
        $section->addText(
            $data,
            ['name' => 'Courier New', 'size' => 11]
        );
    }

    private function createProgram()
    {
        $tmp = $this->dataGenerator->getData();
        $data = $tmp['data'];
        $anmeldeTermine = $tmp['anmeldeTermine'];

        $header = array('name' => 'Arial', 'size' => 16, 'bold' => true);
        $content = array('name' => 'Arial', 'size' => 14, 'bold' => false);
        $contentBold = array('name' => 'Arial', 'size' => 14, 'bold' => true);
        $contentSmall = array('name' => 'Arial', 'size' => 12, 'bold' => false);
        $contentSmallItalic = array('name' => 'Arial', 'size' => 12, 'bold' => false, 'italic' => true);

        $section = $this->word->addSection();
        $section->addText('Bergclub Bern', $header);
        $section->addText('Anmeldetermine', $header);

        $table = $section->addTable();

        foreach ($anmeldeTermine as $arr) {
            foreach ($arr as $entry) {
                $table->addRow();
                $table->addCell(1000)->addText($entry['signupUntil'], $content);
                $table->addCell(600)->addText('für', $content);
                $table->addCell(7500)->addText($entry['info'], $content);
            }
        }

        $section->addText('', $header);
        $section->addText('Tourenübersicht', $header);

        $table = $section->addTable();
        foreach ($data as $entry) {
            $table->addRow();
            $table->addCell(2100)->addText($entry['dateDisplayShort'], $content);
            $textRun = $table->addCell(7000)->addTextRun();
            $textRun->addText($entry['title'], $contentBold);
            if (!empty($entry['type'])) {
                $textRun->addText(' (' . $entry['type'] . ')', $contentSmall);
            }
        }

        $section->addText('', $header);
        $section->addText('Tourendetails', $header);

        $table = $section->addTable();
        foreach ($data as $entry) {
            $table->addRow();
            $table->addCell(2100)->addText($entry['dateDisplayShort'], $contentBold);
            $table->addCell(7000)->addText($entry['title'], $contentBold);
            $table->addRow();
            $table->addCell(2100)->addText($entry['dateDisplayWeekday'], $content);
            $table->addCell(7000)->addText($entry['type'], $content);
            if (!empty($entry['meetpoint'])) {
                $table->addRow();
                $table->addCell(2100)->addText('Treffpunkt', $contentSmallItalic);
                $meetpoint = $entry['meetpoint'];
                if (!empty($entry['meetingPointTime'])) {
                    $meetpoint .= ', ' . $entry['meetingPointTime'] . ' Uhr';
                }
                $table->addCell(7000)->addText($meetpoint, $contentSmall);
            }

            if (!empty($entry['requirementsTechnical']) || !empty($entry['requirementsConditional']) || !empty($entry['riseUpMeters']) || !empty($entry['riseDownMeters']) || !empty($entry['duration'])) {
                $table->addRow();
                $table->addCell(2100)->addText('Anforderungen', $contentSmallItalic);
                $cell = $table->addCell(7000);
                $subTable = $cell->addTable();
                if (!empty($entry['requirementsTechnical'])) {
                    $subTable->addRow();
                    $subTable->addCell(1500)->addText('technisch:', $contentSmallItalic);
                    $subTable->addCell(5500)->addText($entry['requirementsTechnical'], $contentSmall);
                }
                if (!empty($entry['requirementsConditional'])) {
                    $subTable->addRow();
                    $subTable->addCell(1500)->addText('konditionell:', $contentSmallItalic);
                    $subTable->addCell(5500)->addText($entry['requirementsConditional'], $contentSmall);
                }
                if (!empty($entry['riseUpMeters'])) {
                    $subTable->addRow();
                    $subTable->addCell(1500)->addText('Aufstieg:', $contentSmallItalic);
                    $subTable->addCell(5500)->addText($entry['riseUpMeters'], $contentSmall);
                }
                if (!empty($entry['riseDownMeters'])) {
                    $subTable->addRow();
                    $subTable->addCell(1500)->addText('Abstieg:', $contentSmallItalic);
                    $subTable->addCell(5500)->addText($entry['riseDownMeters'], $contentSmall);
                }


            }

            if (!empty($entry['additionalInfo'])) {
                if ($entry["title"] == "Sommerlager Bergclub Jugend") {
                    print_r($entry['additionalInfo']);
                    exit;
                }
                $lines = $entry['additionalInfo'];
                $table->addRow();
                $table->addCell(2100)->addText('Besonderes', $contentSmallItalic);
                $textrun = $table->addCell(7000)->addTextRun();
                $textrun->addText(array_shift($lines), $contentSmall);
                foreach ($lines as $line) {
                    $textrun->addTextBreak();
                    $textrun->addText($line, $contentSmall);
                }
            }

            if (!empty($entry['equipment'])) {
                $lines = $entry['equipment'];
                $table->addRow();
                $table->addCell(2100)->addText('Ausrüstung', $contentSmallItalic);
                $textrun = $table->addCell(7000)->addTextRun();
                $textrun->addText(array_shift($lines), $contentSmall);
                foreach ($lines as $line) {
                    $textrun->addTextBreak();
                    $textrun->addText($line, $contentSmall);
                }
            }

            if (!empty($entry['mapMaterial'])) {
                $table->addRow();
                $table->addCell(2100)->addText('Kartenmaterial', $contentSmallItalic);
                $table->addCell(7000)->addText($entry['mapMaterial'], $contentSmall);
            }

            if (!empty($entry['food'])) {
                $table->addRow();
                $table->addCell(2100)->addText('Verpflegung', $contentSmallItalic);
                $table->addCell(7000)->addText($entry['food'], $contentSmall);
            }

            if (!empty($entry['costs'])) {
                $costs = "CHF " . $entry['costs'];
                if (!empty($entry['costsFor'])) {
                    $costs .= " für " . $entry['costsFor'];
                }
                $table->addRow();
                $table->addCell(2100)->addText('Kosten', $contentSmallItalic);
                $table->addCell(7000)->addText($costs, $contentSmall);
            }

            if (!empty($entry['returnBack'])) {
                $table->addRow();
                $table->addCell(2100)->addText('Rückkehr', $contentSmallItalic);
                $table->addCell(7000)->addText($entry['returnBack'], $contentSmall);
            }

            if (!empty($entry['signupUntil'])) {
                $signup = "bis " . $entry['signupUntil'];
                if (!empty($entry['signupTo'])) {
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
    }

    private function createOutput()
    {
        header('Cache-Control: max-age=0');

        $this->word->save($this->name . ' ' . date("Y-m-d_H-i-s") . '.docx', 'Word2007', true);
    }
}