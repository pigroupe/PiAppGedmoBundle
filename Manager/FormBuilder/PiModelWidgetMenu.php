<?php
/**
 * This file is part of the <Gedmo> project.
 *
 * @category   Gedmo_Managers
 * @package    FormBuilder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-08-31
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Manager\FormBuilder;  

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Sfynx\CmfBundle\Layers\Domain\Service\Manager\PiFormBuilderManager;
use Sfynx\CmfBundle\Layers\Domain\Service\Util\PiWidget\Gedmo\NavigationHandler;
use PiApp\GedmoBundle\Manager\FormBuilder\PiModelWidgetSlideCollectionType;
use PiApp\GedmoBundle\Manager\FormBuilder\PiModelWidgetSearchFieldsType;
        
/**
* Description of the Form builder manager
*
* @category   Gedmo_Managers
* @package    FormBuilder
*
* @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
*/
class PiModelWidgetMenu extends PiFormBuilderManager
{
    /**
     * Type form name.
     */
    const FORM_TYPE_NAME = 'symfony';
    
    /**
     * Default decorator file name
     */
    const FORM_DECORATOR = 'model_form_builder.html.twig';    
    
    /**
     * Form name.
     */
    const FORM_NAME = 'formbuilder';    
        
    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function __construct(ContainerInterface $containerService)
    {
        parent::__construct($containerService, 'WIDGET', 'menu', $this::FORM_TYPE_NAME, $this::FORM_DECORATOR, $this::FORM_NAME);
    }
    
    /**
     * Return list of available content types for all type pages.
     *
     * @param  array    $options
     * @return array
     * @access public
     * @static
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-06-27
     */
    public static function getContents()
    {
        return [
            PiFormBuilderManager::CONTENT_RENDER_TITLE  => "Widget Menu",
            PiFormBuilderManager::CONTENT_RENDER_DESC   => "call for inserting a menu",
        ];
    }    

    /**
     * Chargement du template de formulaire.
     *
     * @access protected
     * @return string
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // we get all entities
        //$listTableClasses = $this->container->get('sfynx.database.db')->listTables('table_class');
        //$listTableClasses = array_combine($listTableClasses, $listTableClasses);
        $ListsAvailableEntities = NavigationHandler::getAvailable();
        $ListsAvailableEntities = array_combine(array_keys($ListsAvailableEntities), array_keys($ListsAvailableEntities));
        // we get all slide templates
        $listFiles = $this->container->get('sfynx.tool.file_manager')->ListFilesBundle('/Resources/views/Template/Tree', 'twig', 'templating');
        $listFiles_ = array_map(function($value) {
            $arr = explode(':', $value);
        	return end($arr);
        }, array_values($listFiles));
        $listFiles = array_combine($listFiles, $listFiles_);
        // we get all css files
        $listCss = $this->container->get('sfynx.tool.file_manager')->ListFilesBundle('/Resources/public/css/widget/tree', 'css', 'assetic');
        $listCss_ = array_map(function($value) {
            return basename($value);
        }, array_values($listCss));
        $listCss = array_combine($listCss, $listCss_);
        // actions
        $actions = [
            '_navigation_default' => 'Navigation par défault',
        ];

        // we create the forme
        $builder
        ->add('action', 'choice', array(
        		'choices'   => $actions,
        		'required'  => true,
        		'multiple'    => false,
        		'expanded' => false,
        		'label'    => "Action",
        		"label_attr" => array(
        				"class"=>"select_choice",
        		),
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))        
        ->add('template', 'choice', array(
        		'choices'   => $listFiles,
        		'multiple'    => false,
        		'required'  => false,
                'expanded' => false,
        		'label' => 'pi.form.label.select.choose.template',
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))   
        ->add('css', 'choice', array(
        		'choices'   => $listCss,
        		'multiple'    => true,
        		'required'  => false,
        		'expanded' => false,
        		'label' => 'pi.form.label.select.choose.css',
        		"attr" => array(
        				"class"=>"pi_multiselect",
        		),
        ))   
        ->add('table', 'choice', array(
        		'choices'   => $ListsAvailableEntities,
        		'multiple'    => false,
        		'required'  => true,
        		'expanded' => false,
        		'empty_value' => 'Choice a table',
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        		"label_attr" => array(
        				"class"=>"select_choice",
        		),
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))        
        ->add('category', 'text', array(
        		'required'  => false,
        		'label'    => "Catégorie",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        ))     
        ->add('node', 'text', array(
        		'required'  => false,
        		'label'    => "Noeud",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        ))           
        ->add('enabled', 'choice', array(
        		'choices'   => array(1=>'pi.form.label.field.yes', 0=>'pi.form.label.field.no'),
        		'required'  => false,
        		'multiple'    => false,
        		'expanded' => false,
        		'label'    => "Activer",
        		"label_attr" => array(
        				"class"=>"select_choice",
        		),
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))
        ->add('query_function', 'text', array(
                'required'  => false,
        		'label'    => "Nom de la fonction SQL",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        ))   
        ->add('navigationsearchfields', 'collection', array(
        		'allow_add' => true,
        		'allow_delete' => true,
        		'prototype'    => true,
        		// Post update
        		'by_reference' => true,
        		'type'   => new PiModelWidgetSearchFieldsType($this->_locale, $this->container),
        		'options'  => array(
        				'attr'      => array('class' => 'collection_widget')
        		),
        		'label'    => ' '
        ))
        ;
    }
    
    /**
     * Sets JS script.
     *
     * @param    array $options
     * @access public
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function renderScript(array $option) 
    {
        // We open the buffer.
        ob_start ();
        ?>
                jQuery(document).ready(function() {
                    var indexSQLParams    = 0;
                    jQuery("div#piappgedmobundlemanagerformbuilderpimodelwidgetmenu").find("fieldset").append('<br ><ul id="sqlparams-fields-list-navigation" ></ul>');
                    jQuery('#add-another-sqlparameters-navigation').click(function() {
                        var prototypeList = jQuery('#prototype_script_navigationsearchfields');   
                        // parcourt le template prototype
                        var newWidget2 = prototypeList.html().replace('<label class="required">__name__label__</label>', '');
                        // remplace les "__name__" utilisés dans l'id et le nom du prototype
                        // par un nombre unique pour chaque email
                        // le nom de l'attribut final ressemblera à name="contact[emails][2]"
                        newWidget2 = newWidget2.replace(/__name__/g, indexSQLParams);
                        indexSQLParams++;            
                        // créer une nouvelle liste d'éléments et l'ajoute à notre liste
                        var newLi2 = jQuery('<li class="addcollection"></li>').html(newWidget2);
                        newLi2.appendTo(jQuery('#sqlparams-fields-list-navigation'));
                        // we align the fields
                        return false;
                    });
                })            
        <?php
        // We retrieve the contents of the buffer.
        $_content_js = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();
        
        // We open the buffer.
        ob_start ();
        ?>
            <br/>
            <a href="#" id="add-another-sqlparameters-navigation">Add another field SQL</a>
        <?php
        // We retrieve the contents of the buffer.
        $_content_html = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();
        
        return  $this->container->get('sfynx.tool.script_manager')->renderScript($_content_js, $_content_html, 'formbuilder/default/menu/');        
    }        
    
    /**
     *
     *
     * @access public
     * @return void
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function preEventBindRequest(){}    
    
    /**
     *
     *
     * @access public
     * @return void
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function preEventActionForm(array $data){
    }

    /**
     *
     *
     * @access public
     * @return void
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function postEventActionForm(array $data){}    
    
    /**
     *
     *
     * @access public
     * @return array        Xml config in array format to create/update a widget.
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function XmlConfigWidget(array $data)
    {
        $AllCss = [];
        foreach ($data['css'] as $css) {
            $AllCss[] = $css;
        }

        return [
            'plugin' => 'gedmo',
            'action' => 'navigation',
            'xml'    => Array (
                "widgets" => Array (
                    'css' => $AllCss,
                    "gedmo" => Array (
                        "controller" => $data['table'].':'.$data['action'],
                        "params" => array(
                            'template' => $data['template'],
                            'enabledonly' => $data['enabled'],
                            'category' => $data['category'],
                            'node' => $data['node'],
                            'navigation' => array(
                                'query_function' => $data['query_function'],
                                'searchFields' => $data['navigationsearchfields'],
                            )
                        )
                    )
                )
            ),
        ];
    }        

}
