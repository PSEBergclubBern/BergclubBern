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
use BergclubPlugin\MVC\Models\Role;
use BergclubPlugin\MVC\Models\User;

class AdressenProcessor extends Processor
{
    protected $processedAddressen = array();

    public function process(...$values): array {
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
            foreach($addressEntities as $addressEntity) {
                /** @var $addressEntity Adressen */
                if($addressEntity->lastName == $lastName &&
                    $addressEntity->firstName == $firstName1
                ) {
                    $spouseId1 = $addressEntity;
                }

                if($addressEntity->lastName == $lastName &&
                    $addressEntity->firstName == $firstName2
                ) {
                    $spouseId2 = $addressEntity;
                }
            }

            if ($spouseId1 && $spouseId2) {
                $this->logger->log("link " . $spouseId1->firstName . " and " . $spouseId2->firstName);
                $spouseId1->setSpouse($spouseId2);
                $spouseId2->setSpouse($spouseId1);
            } else {
                $this->logger->warning('couldnt find couple ' . $lastName);
            }
        }

        return $addressEntities;
    }

    public function save(Entity $entity, $noOp = true)
    {
        /** @var $entity Adressen */
        $this->logger->log('Processing ' . $entity);
        $model = new User($entity->toArray());
        $model->addRole(Role::find($entity->determinateRole()));
        if (!$noOp) {
            $model->save();
            if ($entity->getSpouse() && isset($this->processedAddressen[$entity->getSpouse()->id])) {
                $model->spouse = $this->processedAddressen[$entity->getSpouse()->id];
                $model->main_address = true;
                $this->processedAddressen[$entity->getSpouse()->id]->spouse = $model;
            }
        }

        $this->processedAddressen[$entity->id] = $model;
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

    public function getEntityName() {
        return 'Adressen';
    }
}