<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 16:46
 */
namespace infrastructure\wedding\commands\handlers;

use domain\common\exceptions\SqlRepositoryException;
use domain\wedding\components\WeddingFactory;
use infrastructure\wedding\commands\CreateWeddingCommand;
use infrastructure\wedding\components\WeddingRepository;

class CreateWeddingHandler
{

    private $weddingRepository;
    private $weddingFactory;

    public function __construct(WeddingRepository $aWeddingRepository, WeddingFactory $aWeddingFactory)
    {
        $this->weddingFactory    = $aWeddingFactory;
        $this->weddingRepository = $aWeddingRepository;
    }

    /**
     * @param CreateWeddingCommand $aCommand
     *
     * @return \domain\studio\entities\Studio
     * @throws SqlRepositoryException
     */
    public function handle(CreateWeddingCommand $aCommand)
    {
        $wedding = $this->weddingFactory->create($aCommand->groom()->id(), $aCommand->bride()->id(), $aCommand->date());
        $this->weddingRepository->save($wedding);
        return $wedding;
    }
}
