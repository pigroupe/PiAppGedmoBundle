<?php
/**
 * This file is part of the <PI_CRUD> project.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 20XX-XX-XX
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints;

/**
 * Description of the ContactType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ContactType extends AbstractType
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $_container;

    /**
     * @var string
     */
    protected $_locale;

    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param string    $locale
     * @return void
     */
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->_em             = $em;
        $this->_locale        = $container->get('request_stack')->getCurrentRequest()->getLocale();
        $this->_container     = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id_category = null;
        if ($builder->getData()->getCategory()
                instanceof Category
        ) {
            $id_category = $builder->getData()->getCategory()->getId();
        }
        if (isset($_POST['piapp_gedmobundle_contacttype']['category'])) {
            $id_category = $_POST['piapp_gedmobundle_contacttype']['category'];
        }
        //
        $id_media = null;
        $id_media1 = null;
        // get the id of media
        if ($builder->getData()->getMedia()
                instanceof \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque
        ) {
            $id_media = $builder->getData()->getMedia()->getId();
        }
        if (isset($_POST['piapp_gedmobundle_contacttype']['media'])) {
            $id_media = $_POST['piapp_gedmobundle_contacttype']['media'];
        }
        // get the id of media1
        if ($builder->getData()->getMedia1()
                instanceof \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque
        ) {
            $id_media1 = $builder->getData()->getMedia1()->getId();
        }
        if (isset($_POST['piapp_gedmobundle_contacttype']['media1'])) {
            $id_media1 = $_POST['piapp_gedmobundle_contacttype']['media1'];
        }

        $builder
             ->add('enabled', 'checkbox', array(
                     'data'  => true,
                     'label'    => 'pi.form.label.field.enabled',
             ))
             ->add('category', 'entity', array(
                    'class' => 'PiAppGedmoBundle:Category',
                    'query_builder' => function(EntityRepository $er) use ($id_category) {
                        $translatableListener = $this->_container->get('gedmo.listener.translatable');
                        $translatableListener->setTranslationFallback(true);
                        return $er->createQueryBuilder('k')
                        ->select('k')
                        ->where('k.type = :type')
                        ->andWhere("k.id IN (:id)")
                        ->orderBy('k.name', 'ASC')
                        ->setParameter('id', $id_category)
                        ->setParameter('type', 0);
                    },
                    'empty_value' => 'pi.form.label.select.choose.category',
                    'label'    => "pi.form.label.field.category",
                    'multiple'    => false,
                    'required'  => false,
                    "attr" => array(
                        "class"=>"pi_simpleselect ajaxselect", // ajaxselect
                        "data-url"=>$this->_container->get('sfynx.tool.route.factory')->generate("admin_gedmo_category_selectentity_ajax", array('type'=> 0)),
                        "data-selectid" => $id_category,
                        "data-max" => 50,
                    ),
                    'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
                                    title="Ajouter une catégorie"
                                    data-title="Catégorie"
                                    data-href="'.$this->_container->get('sfynx.tool.route.factory')->generate("admin_gedmo_category_new", array("NoLayout"=>"false", 'type'=> 0)).'"
                                    data-selectid="#piapp_gedmobundle_categorytype_id"
                                    data-selecttitle="#piapp_gedmobundle_categorytype_name"
                                    data-insertid="#piapp_gedmobundle_contacttype_category"
                                    data-inserttype="multiselect"
                                    ></a>',
             ))
             ->add('title', 'text', array(
                     'label'        => "pi.form.label.field.title",
                     'required'  => false,
             ))
             ->add('descriptif', 'textarea', array(
                     'label'    => "pi.form.label.field.description",
                     "label_attr" => array(
                             "class"=>"block_collection",
                     ),
                     "attr" => array(
                             "class"    =>"pi_editor_simple_easy",
                     ),
                     'required'  => false,
             ))
             ->add('coordinates', 'text', array(
                    "label" => 'pi.form.label.field.adress.coordinates',
                    'required'  => false,
             ))
             ->add('name','text', array(
                    'required'      => false,
                    'constraints' => array(
                       new Constraints\Regex(array(
                           'pattern' => "/^[[:alpha:]\s'\x22\-_&@!?()\[\]-]*$/u",
                           'message' => 'erreur.regex.name',
                       )),
                    ),
             ))
             ->add('nickname','text', array(
                    'required'      => false,
                    'constraints' => array(
                       new Constraints\Regex(array(
                           'pattern' => "/^[[:alpha:]\s'\x22\-_&@!?()\[\]-]*$/u",
                           'message' => 'erreur.regex.nickname',
                       )),
                    ),
             ))


            ->add('address', 'textarea', array(
                     "label" => 'pi.form.label.field.adress.main',
                     "label_attr" => array(
                             "class"=>"address_collection",
                     ),
                     "attr" => array(
                             "class"    =>"pi_editor_simple_easy",
                     ),
                     'required'  => false,
            ))
            ->add('cp', 'text', array(
                'label' => 'pi.form.label.field.adress.cp',
                "label_attr" => array(
                    "class"=>"address_collection",
                ),
                'required' => false,
                'constraints' => array(
                    new Constraints\Regex(array(
                        'pattern' => "/^[0-9]{4,6}$/",
                        //'htmlPattern' => "^[0-9]{4,6}$",
                        //'match'   => false,
                        'message' => 'erreur.regex.cp',
                    )),
                ),
            ))
            ->add('city', 'text', array(
                     "label" => 'pi.form.label.field.adress.city',
                     "label_attr" => array(
                             "class"=>"address_collection",
                     ),
                     "attr" => array(
                             "class"    =>"pi_editor_simple_easy",
                     ),
                     'required'  => false,
            ))
            ->add('country', 'text', array(
                    "label" => 'pi.form.label.field.adress.city',
                    "label_attr" => array(
                        "class"=>"address_collection",
                    ),
                    "attr" => array(
                        "class"    =>"pi_editor_simple_easy",
                    ),
                    'required'  => false,
            ))
            ->add('phone', 'text', array(
                    "label" => 'pi.form.label.field.adress.phone',
                    "label_attr" => array(
                             "class"=>"address_collection",
                    ),
                    'required'  => false,
                    'constraints' => array(
                        new Constraints\Regex(array(
                            'pattern' => "/^[0-9._-\s]{10,}$/",
                            //'htmlPattern' => "^[0-9._-\s]+{10,}$",
                            //'match'   => false,
                            'message' => 'erreur.regex.phone',
                        )),
                    ),
            ))
            ->add('mobile', 'text', array(
                    'label' => 'pi.form.label.field.adress.phone.mobile',
                    'required' => false,
                    "label_attr" => array(
                         "class"=>"address_collection",
                    ),
                    'constraints' => array(
                        new Constraints\Regex(array(
                            'pattern' => "/^[0-9._-\s]{10,}$/",
                            //'htmlPattern' => "^[0-9._-\s]+{10,}$",
                            //'match'   => false,
                            'message' => 'erreur.regex.phone',
                        )),
                    ),
            ))
             ->add('fax', 'text', array(
                     "label" => 'pi.form.label.field.adress.fax',
                     "label_attr" => array(
                             "class"=>"address_collection",
                     ),
                     'required'  => false,
             ))


             ->add('email', 'text', array(
                     "label" => 'pi.form.label.field.email',
                     "label_attr" => array(
                             "class"=>"email_collection",
                     ),
                     'required'  => false,
                     'constraints' => array(
                        new Constraints\Email(),
                     ),
             ))
             ->add('email_sender', 'text', array(
                    "label" => 'Nom expéditeur',
                    "label_attr" => array(
                                    "class"=>"email_collection",
                    ),
                    'required'  => false,
                    'constraints' => array(
                        new Constraints\Email(),
                     ),
             ))
             ->add('email_subject', 'text', array(
                     "label" => 'pi.form.label.field.email.subject',
                     "label_attr" => array(
                             "class"=>"email_collection",
                     ),
                     'required'  => false,
             ))
             ->add('email_body', 'textarea', array(
                     "label" => 'pi.form.label.field.email.body',
                     "label_attr" => array(
                             "class"=>"email_collection",
                     ),
                     'required'  => false,
             ))
             ->add('email_cc', 'textarea', array(
                     "label" => 'pi.form.label.field.email.cc',
                     "label_attr" => array(
                             "class"=>"email_collection",
                     ),
                     'required'  => false,
             ))
             ->add('email_bcc', 'textarea', array(
                     "label" => 'pi.form.label.field.email.bcc',
                     "label_attr" => array(
                             "class"=>"email_collection",
                     ),
                     'required'  => false,
             ))


             ->add('url', 'text', array(
                     "label" => 'pi.form.label.field.url',
                     "label_attr" => array(
                             "class"=>"url_collection",
                     ),
                     'required'  => false,
             ))
             ->add('url_title', 'text', array(
                     "label" => 'pi.form.label.field.title',
                     "label_attr" => array(
                             "class"=>"url_collection",
                     ),
                     'required'  => false,
             ))

             //->add('media', new \Sfynx\MediaBundle\Application\Validation\Type\MediathequeType($this->_container, $this->_em, 'image', 'pictures', "simpleLink", 'pi.contact.form.picture.left'))
             ->add('media', 'entity', array(
             		'class' => 'SfynxMediaBundle:Mediatheque',
            		'query_builder' => function(EntityRepository $er) use ($id_media) {
                            $translatableListener = $this->_container->get('gedmo.listener.translatable');
                            $translatableListener->setTranslationFallback(true);
                            return $er->createQueryBuilder('a')
                            ->select('a')
                            ->where("a.id IN (:id)")
                            ->setParameter('id', $id_media)
                            //->where("a.status = 'image'")
                            //->andWhere("a.image IS NOT NULL")
                            //->andWhere("a.enabled = 1")
                            //->orderBy('a.id', 'ASC')
                            ;
            		},
            		//'property' => 'id',
            		'empty_value' => 'pi.form.label.select.choose.media',
            		'label' => "Media",
            		'multiple' => false,
                            'required'  => false,
             		'constraints' => array(
                            //new Constraints\NotBlank(),
             		),
            		"label_attr" => array(
                            "class"=> 'bg_image_collection',
            		),
            		"attr" => array(
                            "class"=>"pi_simpleselect ajaxselect", // ajaxselect
                            "data-url"=>$this->_container->get('sfynx.tool.route.factory')->generate("sfynx_media_mediatheque_selectentity_ajax", array('type'=>'image')),
                            "data-selectid" => $id_media,
                            "data-max" => 50,
            		),
            		'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
             				title="Ajouter une image à la médiatheque"
             				data-title="Mediatheque"
             				data-href="'.$this->_container->get('sfynx.tool.route.factory')->generate("sfynx_media_mediatheque_new", array("NoLayout"=>"false", "category"=>'', 'status'=>'image')).'"
             				data-selectid="#sfynx_mediabundle_mediatype_id"
             				data-selecttitle="#sfynx_mediabundle_mediatype_title"
             				data-insertid="#piapp_gedmobundle_blocktype_media"
             				data-inserttype="multiselect"
             				></a>',
             ))
             //->add('media1', new \Sfynx\MediaBundle\Application\Validation\Type\MediathequeType($this->_container, $this->_em, 'image', 'pictures', "simpleLink",'pi.contact.form.picture.right'))
             ->add('media1', 'entity', array(
             		'class' => 'SfynxMediaBundle:Mediatheque',
            		'query_builder' => function(EntityRepository $er) use ($id_media1) {
                            $translatableListener = $this->_container->get('gedmo.listener.translatable');
                            $translatableListener->setTranslationFallback(true);
                            return $er->createQueryBuilder('a')
                            ->select('a')
                            ->where("a.id IN (:id)")
                            ->setParameter('id', $id_media1)
                            //->where("a.status = 'image'")
                            //->andWhere("a.image IS NOT NULL")
                            //->andWhere("a.enabled = 1")
                            //->orderBy('a.id', 'ASC')
                            ;
            		},
            		//'property' => 'id',
            		'empty_value' => 'pi.form.label.select.choose.media',
            		'label' => "Media",
            		'multiple' => false,
                            'required'  => false,
             		'constraints' => array(
                            //new Constraints\NotBlank(),
             		),
            		"label_attr" => array(
                            "class"=> 'bg_image_collection',
            		),
            		"attr" => array(
                            "class"=>"pi_simpleselect ajaxselect", // ajaxselect
                            "data-url"=>$this->_container->get('sfynx.tool.route.factory')->generate("sfynx_media_mediatheque_selectentity_ajax", array('type'=>'image')),
                            "data-selectid" => $id_media1,
                            "data-max" => 50,
            		),
            		'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
             				title="Ajouter une image à la médiatheque"
             				data-title="Mediatheque"
             				data-href="'.$this->_container->get('sfynx.tool.route.factory')->generate("sfynx_media_mediatheque_new", array("NoLayout"=>"false", "category"=>'', 'status'=>'image')).'"
             				data-selectid="#sfynx_mediabundle_mediatype_id"
             				data-selecttitle="#sfynx_mediabundle_mediatype_title"
             				data-insertid="#piapp_gedmobundle_blocktype_media1"
             				data-inserttype="multiselect"
             				></a>',
            ))
        ;
    }

    public function getBlockPrefix()
    {
        return 'piapp_gedmobundle_contacttype';
    }

}
