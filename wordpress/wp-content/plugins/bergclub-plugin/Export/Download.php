<?php

namespace BergclubPlugin\Export;

use BergclubPlugin\Export\Data\Generator;
use BergclubPlugin\Export\Data\GeneratorFactory;
use BergclubPlugin\Export\Format\Format;
use BergclubPlugin\Export\Format\FormatFactory;
use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\Helpers;
use BergclubPlugin\MVC\Models\Option;
use BergclubPlugin\MVC\Models\User;

class Download
{
    /**
     * @var Generator
     */
    private $dataGenerator;
    /**
     * @var Format
     */
    private $format;

    private $name;

    private $downloads = [
        'members.xls' => [
            'name' => 'Mitglieder',
            'needed_cap' => 'export_adressen',
        ],
        'shipping.xls' => [
            'name' => 'Programmversand',
            'needed_cap' => 'export_adressen',
        ],
        'addresses.xls' => [
            'name' => 'Adressen',
            'needed_cap' => 'export_adressen',
        ],
        'contributions.xls' => [
            'name' => 'Mitgliederbeiträge',
            'needed_cap' => 'export_adressen',
        ],
        'touren.xls' => [
            'name' => 'Touren',
            'needed_cap' => 'export_touren',
        ],
        'calendar.pdf' => [
            'name' => 'Kalender',
            'needed_cap' => '*',
        ],
        'pfarrblatt.docx' => [
            'name' => 'Pfarrblatt',
            'needed_cap' => 'export_druck',
        ],
        'program.docx' => [
            'name' => 'Programm',
            'needed_cap' => 'export_druck',
        ],
    ];

    public function prepare()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'bergclubplugin-export-controllers-maincontroller' && isset($_GET['download'])) {
            if (!isset($this->downloads[$_GET['download']])) {
                FlashMessage::add(FlashMessage::TYPE_WARNING, "Der gewünschte Download ist nicht vorhanden.");
            } else {
                $download = $this->downloads[$_GET['download']];

                $this->name = $download['name'];

                if ($this->checkCapability($download['needed_cap'])) {

                    $arrDownload = explode(".", $_GET['download']);
                    if (count($arrDownload) == 2) {
                        $this->dataGenerator = GeneratorFactory::getConcrete($arrDownload[0], $_GET);
                        if ($this->dataGenerator) {
                            $this->format = FormatFactory::getConcrete($arrDownload[1], $_GET);
                        }
                    }
                } else {
                    FlashMessage::add(FlashMessage::TYPE_WARNING, "Sie haben nicht die benötigten Rechte um diesen Download zu starten.");
                }
            }
        }
    }

    public function run()
    {
        $this->prepare();

        if ($this->dataGenerator && $this->format) {
            set_time_limit(0);
            $this->format->output($this->dataGenerator, $this->name);
            exit;
        }
    }

    private function checkCapability($capability)
    {
        if ($capability == '*') {
            return true;
        }

        $currentUser = User::findCurrent();
        if (!$currentUser) {
            return false;
        }

        return $currentUser->hasCapability($capability);
    }
}