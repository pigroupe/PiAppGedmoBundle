<?php
namespace PiApp\GedmoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @category   CmfPluginsGedmo
 * @package    DependencyInjection
 * @subpackage Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pi_app_gedmo');
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->addMapping($rootNode);

        return $treeBuilder;
    }

    /**
     * Mapping config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addMapping(ArrayNodeDefinition $rootNode)
    {
        $rootNode
        ->children()
            ->arrayNode('mapping')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('provider')->isRequired()->defaultValue('orm')->end()
                    ->scalarNode('category_class')->defaultValue('PiApp\GedmoBundle\Layers\Domain\Entity\Category')->end()
                    ->scalarNode('category_entitymanager_command')->defaultValue('doctrine.orm.entity_manager')->end()
                    ->scalarNode('category_entitymanager_query')->defaultValue('doctrine.orm.entity_manager')->end()
                    ->scalarNode('category_entitymanager')->defaultValue('doctrine.orm.entity_manager')->end()
                ->end()
            ->end()
        ->end();
    }
}
