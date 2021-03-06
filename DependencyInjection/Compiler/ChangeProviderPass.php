<?php
namespace PiApp\GedmoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

use Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\FactoryPass;

/**
 * Class ChangeRepositoryFactoryPass
 *
 * @category   Bundle
 * @package    PiApp\GedmoBundle
 * @subpackage DependencyInjection\Compiler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class ChangeProviderPass implements CompilerPassInterface
{
    /**
     * Processes the edition of the repository factory path depending of the DBMS to load.
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     * @throws InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        FactoryPass::create($container->getParameter('sfynx.gedmo.mapping.provider'), 'category', 'sfynx.gedmo')->process($container);
    }
}
