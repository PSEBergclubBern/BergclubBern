<?php

namespace BergclubPlugin\Commands\Processor;
use BergclubPlugin\Commands\Entities\Entity;
use BergclubPlugin\Commands\Entities\Mitteilung;

/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 29.04.17
 * Time: 13:52
 */
class MitteilungProcessor extends Processor
{
    public function process(...$values) : array {
        if (count($values) != 1) {
            throw new \RuntimeException();
        }
        $values = current($values);
        $list = array();
        foreach ($values as $key => $mitteilung) {
            $this->logger->debug('Processing element ' . (1+$key));

            $mit = new Mitteilung(
                $mitteilung['id'],
                $mitteilung['datum'],
                $this->convertTitleField($mitteilung['titel']),
                $this->convertTextField($mitteilung['text'])
            );

            $list[] = $mit;
        }

        return $list;
    }

    public function save(Entity $entity, $noOp = true) {
        if (!($entity instanceof Mitteilung)) {
            $this->logger->error('Entity not for this processor');
        }

        if (!$noOp) {
            wp_insert_post(
                array(
                    'post_title'        => $entity->titel,
                    'post_author'       => 1,
                    'post_status'       => 'publish',
                    'post_date'         => $entity->datum,
                    'post_content'      => $entity->text,
                    'post_type'         => 'mitteilungen',
                ),
                true
            );
        }
    }

    public function getEntityName() {
        return 'Mitteilungen';
    }
}