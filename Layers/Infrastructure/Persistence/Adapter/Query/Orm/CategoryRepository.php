<?php
namespace PiApp\GedmoBundle\Layers\Infrastructure\Persistence\Adapter\Query\Orm;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm\AbstractQueryRepository;

/**
 * Class CategoryRepository
 * @category PiApp\GedmoBundle\Layers
 * @package Infrastructure
 * @subpackage Persistence\Adapter\Query\Orm
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since  2012-03-15
 */
class CategoryRepository extends AbstractQueryRepository
{
    /**
     * Gets all categories by type
     *
     * @return QueryBuilder
     * @access public
     */
    public function getAllByType($id_type, $id_category = null): QueryBuilder
    {
        return $this
            ->createQueryBuilder('a')
            ->select('a')
            ->where('a.type = :type')
            ->andWhere("a.id IN (:id)")
            ->orderBy('a.name', 'ASC')
            ->setParameter('id', $id_category)
            ->setParameter('type', $id_type);
    }
}