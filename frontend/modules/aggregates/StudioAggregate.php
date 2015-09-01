<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 13:56
 */
namespace app\modules\aggregates;

use app\modules\repositories\StudioRepository;
use frontend\models\Studio;
use frontend\models\StudioOwner;

/**
 * Class StudioAggregate
 *
 * @package app\modules\aggregates
 * @property Studio      $root
 * @property StudioOwner $studioOwner
 */
class StudioAggregate extends Aggregate
{

    protected $studioOwner;

    /**
     * @param Studio      $studio
     * @param StudioOwner $studioOwner
     *
     * @internal param Studio $root
     */
    public function __construct(Studio $studio, StudioOwner $studioOwner)
    {
        $this->root        = $studio;
        $this->studioOwner = $studioOwner;
    }

    /**
     * @return Studio
     */
    public function studio()
    {
        return $this->root();
    }

    /**
     * @return StudioOwner
     */
    public function studioOwner()
    {
        return $this->studioOwner;
    }

    /**
     * @param Studio $studio
     */
    public function setStudio(Studio $studio)
    {
        $this->root = $studio;
    }

    /**
     * @param StudioOwner $studioOwner
     */
    public function setStudioOwner(StudioOwner $studioOwner)
    {
        $this->studioOwner = $studioOwner;
    }

    /**
     * @param StudioRepository $studioRepository
     *
     * @return bool
     */
    public function save(StudioRepository $studioRepository)
    {
        if (!$studioRepository->save($this->root)) {
            return false;
        }
        if (!$this->studioOwner->studio_id) {
            $this->studioOwner->studio_id = $this->root->id;
        }
        if ($studioRepository->saveAggregate($this)) {
            return true;
        }
        $this->studioOwner->studio_id = $this->root->id;
        return false;
    }
}
