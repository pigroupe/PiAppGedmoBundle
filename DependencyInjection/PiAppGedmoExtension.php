<?php
namespace PiApp\GedmoBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @category   CmfPluginsGedmo
 * @package    DependencyInjection
 * @subpackage Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class PiAppGedmoExtension extends Extension{

    public function load(array $config, ContainerBuilder $container)
    {
        $loaderYaml = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loaderYaml->load('service/services_cmfconfig.yml');
        $loaderYaml->load('service/services.yml');
        $loaderYaml->load("service/services_form_builder.yml");
        $loaderYaml->load('service/services_twig_extension.yml');
        $loaderYaml->load('service/services_util.yml');
        $loaderYaml->load('service/services_controllers.yml');

        $loaderYaml->load('repository/category.yml');

        // we load config
        $configuration = new Configuration();
        $config  = $this->processConfiguration($configuration, $config);

        /*
         * Mapping config parameter
         */
        if (isset($config['mapping']['provider'])) {
            $container->setParameter('sfynx.gedmo.mapping.provider', $config['mapping']['provider']);
        }
        if (isset($config['mapping']['category_class'])) {
            $container->setParameter('sfynx.gedmo.category_class', $config['mapping']['category_class']);
        }
        if (isset($config['mapping']['category_entitymanager_command'])) {
            $container->setParameter('sfynx.gedmo.category.entitymanager.command', $config['mapping']['category_entitymanager_command']);
        }
        if (isset($config['mapping']['category_entitymanager_query'])) {
            $container->setParameter('sfynx.gedmo.category.entitymanager.query', $config['mapping']['category_entitymanager_query']);
        }
        if (isset($config['mapping']['category_entitymanager'])) {
            $container->setParameter('sfynx.gedmo.category.entitymanager', $config['mapping']['category_entitymanager']);
        }
    }
}
