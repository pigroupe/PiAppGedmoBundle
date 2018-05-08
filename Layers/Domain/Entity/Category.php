<?php
namespace PiApp\GedmoBundle\Layers\Domain\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


use Sfynx\CoreBundle\Layers\Domain\Model\Traits;
use Sfynx\CoreBundle\Layers\Domain\Model\AbstractDefault;
use Sfynx\MediaBundle\Layers\Domain\Entity\Media;
use Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque;

/**
 * PiApp\GedmoBundle\Layers\Domain\Entity\Category
 *
 * @ORM\Table(name="gedmo_category")
 * @ORM\Entity(repositoryClass="PiApp\GedmoBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\CategoryTranslation")
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
class Category extends AbstractDefault  
{
    const TYPE_CONTACT = 0;
    const TYPE_BLOC = 1;
    const TYPE_MEDIA = 2;
    const TYPE_CONTENU = 3;
    const TYPE_SLIDER = 4;
    const TYPE_MENU = 5;

    use Traits\TraitBuild;

    /**
     * List of al translatable fields
     *
     * @var array
     * @access  protected
     */
    protected $_fields    = array('name', 'subtitle', 'descriptif');

    /**
     * Name of the Translation Entity
     *
     * @var array
     * @access  protected
    */
    protected $_translationClass = 'PiApp\GedmoBundle\Layers\Domain\Entity\Translation\CategoryTranslation';

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\CategoryTranslation", mappedBy="object", cascade={"persist", "remove"})
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
    * @ORM\Column(name="type", type="string", length=255, nullable = true)
    */
    protected $type;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255, nullable = true)
     */
    protected $name;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="subtitle", type="string", length=128, nullable=true)
     */
    protected $subtitle;

    /**
     * @var text $descriptif
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     */
    protected $descriptif;

    /**
     * @var \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque $media
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque");
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Contact", mappedBy="category")
     */
    protected $items_contact;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Block", mappedBy="category")
     */
    protected $items_block;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque", mappedBy="category")
     */
    protected $items_media;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Content", mappedBy="category")
     */
    protected $items_content;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Slider", mappedBy="category")
     */
    protected $items_slider;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Menu", mappedBy="category")
     */
    protected $items_menu;

    public function __construct()
    {
        parent::__construct();

        $this->items_contact = new ArrayCollection();
        $this->items_block   = new ArrayCollection();
        $this->items_media   = new ArrayCollection();
        $this->items_content = new ArrayCollection();
        $this->items_slider  = new ArrayCollection();
        $this->items_menu    = new ArrayCollection();
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
        $content = $this->getName();
        if (($this->getMedia() instanceof Mediatheque)
                    && ($this->getMedia()->getImage() instanceof Media)
            ) {
            $content .= "<img width='100px' src=\"{{ media_url('".$this->getMedia()->getImage()->getId()."', 'small', true, '".$this->getUpdatedAt()->format('Y-m-d H:i:s')."', 'gedmo_media_') }}\" alt='Photo'/>";
    	}
        return (string) $content;
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
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Set media
     *
     * @param \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque $media
     */
    public function setMedia($media)
    {
        //         if (($media instanceof \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque) && ($media->getImage()->getName() == ""))
            //             $this->media = null;
            //         else{
            //             $this->media = $media;
            //         }
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
     * Add items_contact
     *
     * @param \PiApp\GedmoBundle\Layers\Domain\Entity\Contact $itemsContacts
     */
    public function addContact(\PiApp\GedmoBundle\Layers\Domain\Entity\Contact $itemsContacts)
    {
        if (!$this->items_contact->contains($itemsContacts)){
            $this->items_contact->add($itemsContacts);
        }
    }

    /**
     * Get all items_contact
     *
     * @return ArrayCollection
     */
    public function getItemsContact()
    {
        return $this->items_contact;
    }

    /**
     * Add items_block
     *
     * @param \PiApp\GedmoBundle\Layers\Domain\Entity\Block $itemsBlocks
     */
    public function addBlock(\PiApp\GedmoBundle\Layers\Domain\Entity\Block $itemsBlocks)
    {
        $this->items_block[] = $itemsBlocks;
    }

    /**
     * Get all items_block
     *
     * @return ArrayCollection
     */
    public function getItemsBlock()
    {
        return $this->items_block;
    }

    /**
     * Add items_media
     *
     * @param \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque $itemsMedias
     */
    public function addMedia(\Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque $itemsMedias)
    {
        $this->items_media[] = $itemsMedias;
    }

    /**
     * Get all items_media
     *
     * @return ArrayCollection
     */
    public function getItemsMedia()
    {
        return $this->items_media;
    }

    /**
     * Add items_content
     *
     * @param  \PiApp\GedmoBundle\Layers\Domain\Entity\Content $items_content
     */
    public function addContent(\PiApp\GedmoBundle\Layers\Domain\Entity\Content $items_content)
    {
        $this->items_content[] = $items_content;
    }

    /**
     * Get all items_content
     *
     * @return ArrayCollection
     */
    public function getItemsContent()
    {
        return $this->items_content;
    }

    /**
     * Add items_slider
     *
     * @param \PiApp\GedmoBundle\Layers\Domain\Entity\Slider $items_slider
     */
    public function addSlider(\PiApp\GedmoBundle\Layers\Domain\Entity\Slider $items_slider)
    {
        $this->items_slider[] = $items_slider;
    }

    /**
     * Get all items_slider
     *
     * @return ArrayCollection
     */
    public function getItemsSlider()
    {
        return $this->items_slider;
    }

    /**
     * Add items_menu
     *
     * @param \PiApp\GedmoBundle\Layers\Domain\Entity\Menu $items_menu
     */
    public function addMenu(\PiApp\GedmoBundle\Layers\Domain\Entity\Menu $items_menu)
    {
        $this->items_menu[] = $items_menu;
    }

    /**
     * Get all items_menu
     *
     * @return ArrayCollection
     */
    public function getItemsMenu()
    {
        return $this->items_menu;
    }
}
