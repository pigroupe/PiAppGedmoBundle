<?php
namespace PiApp\GedmoBundle\Layers\Domain\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


use Sfynx\CoreBundle\Layers\Domain\Model\Traits;
use Sfynx\CoreBundle\Layers\Domain\Model\AbstractDefault;
use Sfynx\PositionBundle\Annotation as PI;

/**
 * PiApp\GedmoBundle\Layers\Domain\Entity\Slider
 *
 * @ORM\Table(name="gedmo_slider")
 * @ORM\Entity(repositoryClass="PiApp\GedmoBundle\Repository\SliderRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\SliderTranslation")
 *
 * @category   Sfynx\GedmoBundle\Layers
 * @package    Domain
 * @subpackage Entity
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Slider extends AbstractDefault  
{
    use Traits\TraitBuild;

    /**
     * List of al translatable fields
     *
     * @var array
     * @access  protected
     */
    protected $_fields    = array('title', 'subtitle','descriptifleft', 'descriptifright', 'pagetitle', 'slug', 'meta_keywords', 'meta_description');

    /**
     * Name of the Translation Entity
     *
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'PiApp\GedmoBundle\Layers\Domain\Entity\Translation\SliderTranslation';

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\SliderTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @var bigint
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \PiApp\GedmoBundle\Layers\Domain\Entity\Category $category
     *
     * @ORM\ManyToOne(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Category", inversedBy="items_slider")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true)
     */
    protected $category;

    /**
     * @var string $CssClass
     *
     * @ORM\Column(name="css_class", type="string", nullable=true)
     */
    protected $CssClass;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=128, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="subtitle", type="string", length=128, nullable=true)
     */
    protected $subtitle;

    /**
     * @var text $descriptifleft
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="descriptif_left", type="text", nullable=true)
     * @Assert\Length(min = 25, minMessage = "Le descriptif name doit avoir au moins {{ limit }} caractères")
     */
    protected $descriptifleft;

    /**
     * @var text $descriptifright
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="descriptif_right", type="text", nullable=true)
     * @Assert\Length(min = 25, minMessage = "Le descriptif name doit avoir au moins {{ limit }} caractères")
     */
    protected $descriptifright;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\Page $page
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=true)
     */
    protected $page;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="page_title", type="string", length=128, nullable=true)
     */
    protected $pagetitle;

    /**
     * @var string
     *
     * @ORM\Column(name="page_cssclass", type="string", length=128, nullable=true)
     */
    protected $pagecssclass;

    /**
     * @var \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque $media
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque");
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

    /**
     * @var string $slug
     *
     * @Gedmo\Translatable
     * @Gedmo\Slug(separator="-", fields={"title", "id"})
     * @ORM\Column(name="slug", length=128, unique=false, nullable=true)
     */
    protected $slug;

    /**
     * @var text $meta_keywords
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     */
    protected $meta_keywords;

    /**
     * @var text $meta_description
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    protected $meta_description;

    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"category","columnName":"category"})
     */
    protected $position;

    //
    // * @Assert\File(
    //        *     maxSize = "10M",
    //        *     mimeTypes = {
    //    "image/gif","image/jpeg","image/png"},
    //    *     mimeTypesMessage = "Please upload a valid image"
    //    * )


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * This method is used when you want to convert to string an object of
     * this Entity
     * ex: $value = (string)$entity;
     *
     */
    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
    }

    /**
     * Get id
     *
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set page
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\Page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set Pagetitle
     *
     * @param string $title
     */
    public function setPagetitle($title)
    {
        $this->pagetitle = $title;
    }

    /**
     * Get Pagetitle
     *
     * @return string
     */
    public function getPagetitle()
    {
        return $this->pagetitle;
    }

    /**
     * Set pagecssclass
     *
     * @param string $className
     */
    public function setPagecssclass($className)
    {
        $this->pagecssclass = $className;
    }

    /**
     * Get pagecssclass
     *
     * @return string
     */
    public function getPagecssclass()
    {
        return $this->pagecssclass;
    }

    /**
     * Set category
     *
     * @param \PiApp\GedmoBundle\Layers\Domain\Entity\Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return \PiApp\GedmoBundle\Layers\Domain\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set CssClass
     *
     * @param string $CssClass
     */
    public function setCssClass($CssClass)
    {
        $this->CssClass = $CssClass;
    }

    /**
     * Get CssClass
     *
     * @return string
     */
    public function getCssClass()
    {
        return $this->CssClass;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set subtitle
     *
     * @param string $title
     */
    public function setSubtitle($title)
    {
        $this->subtitle = $title;
    }


    /**
     * Set descriptifleft
     *
     * @param text $descriptif
     */
    public function setDescriptifleft($descriptif)
    {
        $this->descriptifleft = $descriptif;
    }

    /**
     * Get descriptifleft
     *
     * @return text
     */
    public function getDescriptifleft()
    {
        return $this->descriptifleft;
    }

    /**
     * Set descriptifright
     *
     * @param text $descriptif
     */
    public function setDescriptifright($descriptif)
    {
        $this->descriptifright = $descriptif;
    }

    /**
     * Get descriptifright
     *
     * @return text
     */
    public function getDescriptifright()
    {
        return $this->descriptifright;
    }

    /**
     * Set media
     *
     * @param \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * Get media
     *
     * @return \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set meta_keywords
     *
     * @param text $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->meta_keywords = $metaKeywords;
    }

    /**
     * Get meta_keywords
     *
     * @return text
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * Set meta_description
     *
     * @param text $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;
    }

    /**
     * Get meta_description
     *
     * @return text
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }
}
