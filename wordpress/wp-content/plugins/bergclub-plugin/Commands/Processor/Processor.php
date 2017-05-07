<?php

namespace BergclubPlugin\Commands\Processor;
use BergclubPlugin\Commands\Entities\Entity;
use BergclubPlugin\Commands\Logger;

/**
 * Abstract class for processors
 * A processor takes data from the old website and imports them into the new one
 *
 * @author Kevin Studer <kreemer@me.com>
 */
abstract class Processor
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Processor constructor
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * get all entities from the import variables
     *
     * @param $values
     * @return array
     */
    abstract public function process($values) : array;

    /**
     * save an entity
     *
     * @param Entity $entity
     * @param boolean $noOp true if no operation should be executed
     * @return void
     */
    abstract public function save(Entity $entity, $noOp = true);

    /**
     * get name of entity
     *
     * @return string
     */
    abstract public function getEntityName();

    /**
     * Convert a title field for the new database
     *
     * @param $text
     * @return mixed|string
     */
    protected function convertTitleField($text){
        return $this->cleanUp($text);
    }

    /**
     * Convert a text field for the new database
     *
     * @param $text
     * @return mixed|string
     */
    protected function convertTextField($text)
    {
        $text = $this->convertEncoding($text);
        return $this->cleanUp($text);
    }

    /**
     * cleanup old field
     *
     * @param $text
     * @return mixed|string
     */
    private function cleanUp($text){
        // replace br
        $text = str_replace('<br>', '', $text);

        // remove leading and trailing whitespaces
        $text = trim($text);

        return $text;
    }

    /**
     * convert encoding to new format
     *
     * @param $text
     * @return string
     */
    private function convertEncoding($text){
        // urldecode
        $text = urldecode($text);

        // to utf8
        $text = iconv('iso-8859-1', 'UTF-8', $text);

        return $text;
    }
}