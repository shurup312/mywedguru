<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace infrastructure\wedding\components;

use DateTime;
use domain\person\entities\Person;
use domain\wedding\components\WeddingFactory;
use domain\wedding\entities\Wedding;
use infrastructure\common\components\SqlRepository;
use yii\db\Query;

/**
 * Class StudioRepository
 *
 * @package infrastructure\studio\components
 */
class WeddingRepository extends SqlRepository
{

    private static $weddingTableName = '{{%wedding}}';

    private static function getFactory()
    {
        return new WeddingFactory();
    }

    public static function getByBride(Person $aPerson)
    {
        $data   = (new Query())->select('wedding.*')->from(self::$weddingTableName.' as wedding')->where(['bride_id' => $aPerson->id()])->one();
        if(!$data){
            return null;
        }
        $studio = self::getFactory()->create($data['groom_id'], $data['bride_id'], new DateTime($data['date']));
        $studio->setId($data['id']);
        return $studio;
    }

    public function save(Wedding $aWedding)
    {
        if (!$aWedding->id()) {
            return $this->create($aWedding);
        } else {
            return $this->update($aWedding);
        }
    }

    private function create(Wedding $aWedding)
    {
        $db     = $this->db();
        $db->createCommand()->insert(self::$weddingTableName, [
            'groom_id' => $aWedding->groomId(),
            'bride_id' => $aWedding->brideId(),
            'date'     => $aWedding->date()->format('Y-m-d H:i:s'),
        ])->execute();
        $aWedding->setId($db->getLastInsertID());
    }

    private function update(Wedding $aWedding)
    {
        $db     = $this->db();
        $db->createCommand()->update(self::$weddingTableName, [
            'groom_id' => $aWedding->groomId(),
            'bride_id' => $aWedding->brideId(),
            'date'     => $aWedding->date()->format('Y-m-d H:i:s'),
        ], ['id' => $aWedding->id()])->execute();
    }
}
