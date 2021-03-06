<?php
/**
 * This file is part of the <Gedmo> project.
 *
 * @category   Gedmo_Repositories
 * @package    Repository
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-07-31
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm\Traits\TraitTranslation;

/**
 * Block Repository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 * 
 * @category   Gedmo_Repositories
 * @package    Repository
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class BlockRepository extends EntityRepository
{
    use TraitTranslation;
}