<?php

namespace BergclubPlugin\Commands;

use BergclubPlugin\Commands\Processor\AddressProcessor;
use BergclubPlugin\Commands\Processor\MitteilungProcessor;
use BergclubPlugin\Commands\Processor\Processor;
use BergclubPlugin\Commands\Processor\TourProcessor;

/**
 * Class Import
 *
 * The main entry point for the import command
 *
 * @package BergclubPlugin\Commands
 */
class Import extends Init
{

    private $noop = false;

    /**
     * @var MitteilungProcessor
     */
    private $mitteilungsProcessor;

    /**
     * @var AddressProcessor
     */
    private $addressenProcessor;

    /**
     * @var TourProcessor
     */
    private $tourProcessor;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(MitteilungProcessor $mitteilungProcessor = null,
                                AddressProcessor $adressenProcessor = null,
                                TourProcessor $tourProcessor = null,
                                Logger $logger = null)
    {
        if ($logger == null) {
            $this->logger = new WPCliLogger();
        } else {
            $this->logger = $logger;
        }

        if ($mitteilungProcessor == null) {
            $this->mitteilungsProcessor = new MitteilungProcessor($this->logger);
        } else {
            $this->mitteilungsProcessor = $mitteilungProcessor;
        }

        if ($adressenProcessor == null) {
            $this->addressenProcessor = new AddressProcessor($this->logger);
        } else {
            $this->addressenProcessor = $adressenProcessor;
        }

        if ($tourProcessor == null) {
            $this->tourProcessor = new TourProcessor($this->logger);
        } else {
            $this->tourProcessor = $tourProcessor;
        }
    }


    /**
     * Import old database files into wordpress.
     *
     * ## OPTIONS
     *
     * <filename>
     * : Filename with the content of the old database. The file should be utf-8 encoded and be a php export of a db.
     *
     * [--noop]
     * : Does not save the generated stuff
     *
     * ---
     * default: success
     * options:
     *   - success
     *   - error
     * ---
     *
     * ## EXAMPLES
     *
     *     wp bergclub import /tmp/import.php
     *
     * @when after_wp_load
     */
    function __invoke($args, $assoc_args)
    {
        if (!is_array($args)) {
            return;
        }
        if (count($args) < 1) {
            return;
        }

        if (isset($assoc_args['noop'])) {
            $this->noop = true;
        }

        list($filename) = $args;

        if (!file_exists($filename)) {
            $this->logger->error('Input file not found, aborting!');

            return;
        }

        // read input file
        require $filename;

        // Check for adressen
        if (!isset($adressen)) {
            $this->logger->warning('Input file has no adressen, skipping');
        } else {
            $this->import(array($adressen), $this->addressenProcessor);
        }

        // Check for mitteilungen
        if (!isset($mitteilungen)) {
            $this->logger->warning('Input file has no mitteilungen, skipping');
        } else {
            $this->import(array($mitteilungen), $this->mitteilungsProcessor);
        }

        // Check for touren
        if (!isset($touren) || !isset($berichte) || !isset($art) || !isset($schwierigkeit) || !isset($adressen)) {
            $this->logger->warning('Input file has no touren, berichte, art, schwierigkeit, adressen... skipping');
        } else {

            $this->import(array($touren, $berichte, $art, $schwierigkeit, $adressen), $this->tourProcessor);
        }
    }

    /**
     * Helper method for the processor
     *
     * @param           $values
     * @param Processor $processor
     */
    private function import($values, Processor $processor)
    {
        $this->logger->log('Begin processing of ' . $processor->getEntityName());
        $this->logger->log('It has ' . count(current($values)) . ' ' . $processor->getEntityName());

        $entities = $processor->process($values);

        foreach ($entities as $entity) {
            $processor->save($entity, $this->noop);
        }

        $this->logger->success('All ' . $processor->getEntityName() . ' are imported');
    }

}