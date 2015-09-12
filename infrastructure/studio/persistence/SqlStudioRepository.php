<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 16:30
 */
namespace infrastructure\studio\persistence;

use domain\person\contracts\StudioRepository;
use infrastructure\common\components\SqlRepository;

class SqlStudioRepository  extends SqlRepository implements StudioRepository
{

}
