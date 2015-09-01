<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace app\modules\repositories;

use app\modules\aggregates\PhotogalleryAggregate;
use frontend\models\Photogallery;
use frontend\models\User;

class PhotogalleryRepository extends Repository
{

    /**
     * @param User $user
     *
     * @return PhotogalleryAggregate|null
     */
    public static function getByUser(User $user)
    {
        /**
         * @var Photogallery $photogallery
         */
        $photogallery = Photogallery::find()->where(['user_id' => $user->id]);
        if(!$photogallery){
            return null;
        }
        return $photogallery;
    }
}
