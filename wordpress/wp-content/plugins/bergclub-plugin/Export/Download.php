<?php

namespace BergclubPlugin\Export;

use BergclubPlugin\Export\Data\Generator;
use BergclubPlugin\Export\Data\GeneratorFactory;
use BergclubPlugin\Export\Format\Format;
use BergclubPlugin\Export\Format\FormatFactory;
use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\Models\User;

/**
 * Prepares and starts file downloads.
 * The `run` method should be used to check the current url for a download request and to automatically start the download
 * process.
 * The `prepare` method should be used to check the current url for a download and prepare the download without starting
 * it.
 *
 * @package BergclubPlugin\Export
 */
class Download
{
    /**
     * @var Generator the data generator to use
     */
    private $dataGenerator;
    /**
     * @var Format the download format to use
     */
    private $format;

    /**
     * @var string the filename to use for the download
     */
    private $name;

    /**
     * When `run` or `prepare` method is called, this array will be used to compare it's first level keys with the value
     * of $_GET['download'] to determine the filename to use and the capability the current logged in user needs to have
     * assigned to download the file.
     *
     * @var array
     */
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

    /**
     * Calls the prepare method.
     * If the data generator and the download file format is set afterwards, it asks the download file format to output
     * with the data generator and file name.
     *
     * @see Format::output()
     */
    public function run()
    {
        $this->prepare();

        if ($this->dataGenerator && $this->format) {
            set_time_limit(0);
            $this->format->output($this->dataGenerator, $this->name);
            exit;
        }
    }

    /**
     * Will prepare a download if the following conditions are met:
     * - `$_GET['page']` is set to the "Export" admin page
     * - `$_GET['download']` is a first level key of `$this->downloads`
     * - The `needed_cap` for the specific file in `$this->downloads` is '*' (everyone/public) or the user has the
     *   corresponding capability assigned.
     * - the corresponding data generators and file format classes are available.
     *   Example: If `$_GET['download']` is `calendar.pdf` a `CalendarGenerator` and a `PdfFormat` needs to be available.
     *
     * @see GeneratorFactory
     * @see FormatFactory
     */
    public function prepare()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'bergclubplugin-export-controllers-maincontroller' && isset($_GET['download'])) {
            if (isset($this->downloads[$_GET['download']])) {
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

            if (!$this->dataGenerator || !$this->format) {
                FlashMessage::add(FlashMessage::TYPE_WARNING, "Der gewünschte Download ist nicht vorhanden.");
            }
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