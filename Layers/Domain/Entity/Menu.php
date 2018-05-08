<?php
namespace PiApp\GedmoBundle\Layers\Domain\Entity;


use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitPositionInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitTreeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;
use Sfynx\CoreBundle\Layers\Domain\Model\AbstractDefault;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Sfynx\PositionBundle\Annotation as PI;

/**
 * PiApp\GedmoBundle\Layers\Domain\Entity\Menu
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="gedmo_menu")
 * @ORM\Entity(repositoryClass="PiApp\GedmoBundle\Repository\MenuRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\MenuTranslation")
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
class Menu extends AbstractDefault implements TraitDatetimeInterface,TraitEnabledInterface,TraitTreeInterface,TraitPositionInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;
    use Traits\TraitTree;
    use Traits\TraitPosition;

    /**
     * @var integer $parent
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="childrens", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    /**
     * @var array $childrens
     *
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent", cascade={"all"})
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $childrens;

    /**
     * List of al translatable fields
     *
     * @var array
     * @access  protected
     */
    protected $_fields = array('slug', 'title');

    /**
     * Name of the Translation Entity
     *
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'PiApp\GedmoBundle\Layers\Domain\Entity\Translation\MenuTranslation';

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\MenuTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @Gedmo\Translatable
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", length=64, unique=true, nullable=false)
     */
    private $slug;

    /**
     * @var \PiApp\GedmoBundle\Layers\Domain\Entity\Category $category
     *
     * @ORM\ManyToOne(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Category", inversedBy="items_menu")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true)
     */
    protected $category;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=128, nullable=false)
     * @Assert\NotBlank(message = "erreur.title.notblank")
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
     * @var string $configCssClass
     *
     * @ORM\Column(name="config_css_class", type="string", nullable=true)
     */
    protected $configCssClass;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\Page $page
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Page", inversedBy="menus", cascade={"persist"})
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=true)
     */
    protected $page;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=314, nullable=true)
     */
    protected $url;

    /**
     * @var boolean $blank
     *
     * @ORM\Column(name="blank", type="boolean", nullable=true)
     */
    protected $blank;

    /**
     * @var \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque $media
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque");
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"category","columnName":"category"})
     */
    protected $position;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->childrens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setEnabled(true);
    }

    public function __toString()
    {
        return (string) $this->getTitle();
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

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set page
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\Page    $page
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
     * Set $url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get $url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set blank
     *
     * @param boolean $blank
     */
    public function setBlank($blank)
    {
        $this->blank = $blank;
        return $this;
    }

    /**
     * Get blank
     *
     * @return boolean
     */
    public function getBlank()
    {
        return $this->blank;
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
     * Set $title
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
     * Set $subtitle
     *
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
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
     * Set configCssClass
     *
     * @param string $configCssClass
     */
    public function setConfigCssClass($configCssClass)
    {
        $this->configCssClass = $configCssClass;
    }

    /**
     * Get configCssClass
     *
     * @return string
     */
    public function getConfigCssClass()
    {
        return $this->configCssClass;
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
}
