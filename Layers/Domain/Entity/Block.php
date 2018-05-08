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
 * PiApp\GedmoBundle\Layers\Domain\Entity\Block
 *
 * @ORM\Table(name="gedmo_block")
 * @ORM\Entity(repositoryClass="PiApp\GedmoBundle\Repository\BlockRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\BlockTranslation")
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
class Block extends AbstractDefault  
{
    use Traits\TraitBuild;

    /**
     * List of al translatable fields
     *
     * @var array
     * @access  protected
     */
    protected $_fields    = array('title', 'descriptif', 'content', 'url_title');

    /**
     * Name of the Translation Entity
     *
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'PiApp\GedmoBundle\Layers\Domain\Entity\Translation\BlockTranslation';

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\BlockTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @ORM\ManyToOne(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Category", inversedBy="items_block")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true)
     */
    protected $category;

    /**
     * @var string $title
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="text", nullable = true)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var text $descriptif
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     */
    protected $descriptif;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    /**
     * @var string $author
     *
     * @ORM\Column(name="author", type="string", length=255, nullable = true)
     */
    protected $author;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\Page $pageurl
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Page")
     * @ORM\JoinColumn(name="page_intro_id", referencedColumnName="id", nullable=true)
     */
    protected $pageurl;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=314, nullable=true)
     */
    protected $url;

    /**
     * @var string $url_title
     * @Gedmo\Translatable
     * @ORM\Column(name="url_title", type="string", length=255, nullable = true)
     */
    protected $url_title;

    /**
     * @var integer $media
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque" , inversedBy="block");
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

    /**
     * @var integer $media1
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque" , inversedBy="block2");
     * @ORM\JoinColumn(name="media1_id", referencedColumnName="id", nullable=true)
     */
    protected $media1;

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
        if (isset($_GET['_locale']) && !empty($_GET['_locale'])) {
        	$locale = $_GET['_locale'];
        } else {
        	$locale = "fr_FR";
        }
        $content = $this->getId();
        $title = $this->translate($locale)->getTitle();
        $cat = $this->getCategory();
        if ($title) {
        	$content .=  " > " .$title;
        }
        if (!(null === $cat)) {
        	$content .=  '('. $cat->translate($locale)->getName() .')';
        }
        return (string) $content;
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
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
     * Set descriptif
     *
     * @param text $descriptif
     */
    public function setDescriptif ($descriptif)
    {
        $this->descriptif = $descriptif;
        return $this;
    }

    /**
     * Get descriptif
     *
     * @return text
     */
    public function getDescriptif ()
    {
        return $this->descriptif;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $text
     */
    public function setContent($text)
    {
        $this->content = $text;
        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Set page url
     *
     * @param \Sfynx\CmfBundle\Layers\Domain\Entity\Page
     */
    public function setPageurl($pageurl)
    {
        $this->pageurl = $pageurl;
        return $this;
    }

    /**
     * Get page url
     *
     * @return \Sfynx\CmfBundle\Layers\Domain\Entity\Page
     */
    public function getPageurl()
    {
        return $this->pageurl;
    }

    /**
     * Set $url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
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
     * Set url_title
     *
     * @param string $urlTitle
     * @return this
     */
    public function setUrlTitle($urlTitle)
    {
        $this->url_title = $urlTitle;
        return $this;
    }

    /**
     * Get url_title
     *
     * @return string
     */
    public function getUrlTitle()
    {
        return $this->url_title;
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
     * Set media1
     *
     * @param \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque $media
     */
    public function setMedia1($media)
    {
        $this->media1 = $media;
        return $this;
    }

    /**
     * Get media1
     *
     * @return \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque
     */
    public function getMedia1()
    {
        return $this->media1;
    }

}
