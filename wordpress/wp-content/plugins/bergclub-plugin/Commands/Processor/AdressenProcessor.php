<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 29.04.17
 * Time: 14:34
 */

namespace BergclubPlugin\Commands\Processor;

use BergclubPlugin\Commands\Entities\Adressen;
use BergclubPlugin\Commands\Entities\Entity;

class AdressenProcessor extends Processor
{
    public function process($values): array
    {
        $addressEntities = array();
        $spouseEntries = array();
        foreach ($values as $a) {
            if ($a['kategorie'] == Adressen::CATEGORY_EHEPAAR) {
                $spouseEntries[] = $a;
            } else {
                $addressEntities[] = $this->generateEntitiesFromArray($a);
            }
        }

        $i = 0;
        foreach ($spouseEntries as $spouseEntry) {
            $i++;
            $this->logger->log('Processing spouses ' . $i);
            $firstNames = mb_split('\+', $spouseEntry['vorname']);
            $lastName = $spouseEntry['name'];

            $firstName1 = trim(current($firstNames));
            $firstName2 = trim(end($firstNames));

            $spouseId1 = null;
            $spouseId2 = null;
            foreach($addressEntities as $id => $values) {
                if($values['entity']->lastName == $lastName &&
                    $values['entity']->firstName == $firstName1
                ) {
                    $spouseId1 = $id;
                }

                if($values['entity']->lastName == $lastName &&
                    $values['entity']->firstName == $firstName2
                ) {
                    $spouseId2 = $id;
                }
            }

            if ($spouseId1 && $spouseId2) {
                \WP_CLI::log("Marry " . $processedAddresses[$spouseId1]['entity'] . " and " . $processedAddresses[$spouseId2]['entity']);
                $processedAddresses[$spouseId1]['model']->spouse = $processedAddresses[$spouseId2]['model'];
                $processedAddresses[$spouseId1]['model']->main_address = true;
                $processedAddresses[$spouseId2]['model']->spouse = $processedAddresses[$spouseId1]['model'];
                if (!$this->noop) {
                    $processedAddresses[$spouseId1]['model']->save();
                    $processedAddresses[$spouseId2]['model']->save();
                }
            } else {
                \WP_CLI::warning('couldnt find couple ' . $lastName);
            }
        }

    }

    public function save(Entity $entity, $noOp = true)
    {
        $processedAddresses = array();
        $i = 0;
        foreach ($entity as $addressEntity) {
            $i++;
            /** @var Adressen $addressEntity */
            \WP_CLI::log('Processing ' . $addressEntity);
            $model = new ModelUser($addressEntity->toArray());
            $model->addRole(Role::find($addressEntity->determinateRole()));
            if (!$this->noop) {
                $model->save();
            }

            $processedAddresses[$addressEntity->id]['entity'] = $addressEntity;
            $processedAddresses[$addressEntity->id]['model'] = $model;
        }


    }

    /**
     * generate address entity from an array key
     *
     * @param $array
     * @return Adressen
     */
    private function generateEntitiesFromArray($a) {
        $addressEntity = new Adressen();
        $addressEntity->id = $a['id'];
        $addressEntity->firstName = trim($a['vorname']);
        $addressEntity->lastName = trim($a['name']);
        $addressEntity->salutation = $a['anrede'];

        $addressEntity->email = $a['email'];
        $addressEntity->ahv = $a['ahv'];
        $addressEntity->birthday = $a['geburtsdatum'];
        $addressEntity->category = $a['kategorie'];
        $addressEntity->number = $a['nr'];
        $addressEntity->phoneBusiness = $a['tel_g'];
        $addressEntity->phoneMobile = $a['natel'];
        $addressEntity->phonePrivate = $a['tel_p'];
        $addressEntity->place = $a['ort'];
        $addressEntity->plz = $a['plz'];
        $addressEntity->street = $a['strasse'];
        $addressEntity->comment = $a['bemerkungen'];
        $addressEntity->addition = $a['zusatz'];

        $addressEntity->freeMemberDate = $a['edat'];
        $addressEntity->freeMemberReason = $a['egrund'];
        $addressEntity->honorMemberDate = $a['fdat'];
        $addressEntity->honorMemberReason = $a['fgrund'];
        $addressEntity->exitDate = $a['adat'];
        $addressEntity->exitReason = $a['agrund'];
        $addressEntity->diedDate = $a['ddat'];
        $addressEntity->diedReason = $a['dgrund'];

        $addressEntity->leader = $a['leiter'];
        $addressEntity->leaderDescription = $a['beschreibung'];
        $addressEntity->leaderFrom = $a['ldat'] == '0000-00-00' ? null : $a['ldat'];
        $addressEntity->leaderTo = $a['lbis'] == '0000-00-00' ? null : $a['lbis'];

        $addressEntity->leaderYouth = $a['js_leiter'];
        $addressEntity->leaderYouthDescription = $a['js_beschreibung'];
        $addressEntity->leaderYouthFrom = $a['jsdat'] == '0000-00-00' ? null : $a['jsdat'];
        $addressEntity->leaderYouthTo = $a['jsbis'] == '0000-00-00' ? null : $a['jsbis'];
        $addressEntity->leaderYouthYear = $a['js_jahr'];
        $addressEntity->leaderYouthPersonNr = $a['pers_nr'];

        $addressEntity->vorstand = $a['vorstand'];
        $addressEntity->vorstandDescription = $a['funktion'];
        $addressEntity->vorstandFrom = $a['vdat'] == '0000-00-00' ? null : $a['vdat'];
        $addressEntity->vorstandTo = $a['vbis'] == '0000-00-00' ? null : $a['vbis'];

        $addressEntity->support = $a['support'];
        $addressEntity->supportFrom = $a['sdat'] == '0000-00-00' ? null : $a['sdat'];;
        $addressEntity->supportTo = $a['sbis'] == '0000-00-00' ? null : $a['sbis'];;
        $addressEntity->supportDescription = $a['aufgabe'];

        $addressEntity->sendProgram = empty($a['versenden']) || $a['versenden'] == 0 ? false : true;

        return $addressEntity;
    }
}