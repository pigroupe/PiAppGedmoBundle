<?php
namespace PiApp\GedmoBundle\Layers\Domain\Entity;


use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitDatetimeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitEnabledInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\TraitTreeInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Sfynx\CoreBundle\Layers\Domain\Model\AbstractDefault;

/**
 * PiApp\GedmoBundle\Layers\Domain\Entity\Organigram
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="gedmo_organigram")
 * @ORM\Entity(repositoryClass="PiApp\GedmoBundle\Repository\OrganigramRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\OrganigramTranslation")
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
class Organigram extends AbstractDefault implements TraitDatetimeInterface,TraitEnabledInterface,TraitTreeInterface
{
    use Traits\TraitBuild;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;
    use Traits\TraitTree;

    /**
     * @var integer $parent
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Organigram", inversedBy="childrens", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    /**
     * @var array $childrens
     *
     * @ORM\OneToMany(targetEntity="Organigram", mappedBy="parent", cascade={"all"})
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $childrens;

    /**
     * List of al translatable fields
     *
     * @var array
     * @access  protected
     */
    protected $_fields    = array('slug', 'title', 'content', 'descriptif', 'question');

    /**
     * Name of the Translation Entity
     *
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'PiApp\GedmoBundle\Layers\Domain\Entity\Translation\OrganigramTranslation';

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Translation\OrganigramTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @ORM\Column(name="slug", length=64, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=128, nullable=true)
     */
    protected $category;

    /**
     * @var string
     *
     * @ORM\Column(name="categoryother", type="string", length=128, nullable=true)
     */
    protected $categoryother;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=128, nullable=true)
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
     * @ORM\Column(name="question", type="string", length=128, nullable=true)
     */
    protected $question;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    /**
     * @var \Sfynx\CmfBundle\Layers\Domain\Entity\Page $page
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\CmfBundle\Layers\Domain\Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=true)
     */
    protected $page;

    /**
     * @var \Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque $media
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\MediaBundle\Layers\Domain\Entity\Mediatheque" , inversedBy="organigram");
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

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
        return (string) $this->getTitle() . ' - ' . $this->getQuestion();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $other  = $this->getCategoryother();
        if (!empty($other)){
            $this->setCategory($other);
            $this->setCategoryother('');
        }
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
     * Set category
     *
     * @param string $category
     */
    public function setCategory($category)
    {
           $this->category = $category;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     *
     * @param string $category
     */
    public function setCategoryother($category)
    {
           $this->categoryother = $category;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategoryother()
    {
        return $this->categoryother;
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
     * Set descriptif
     *
     * @param text $descriptif
     */
    public function setDescriptif ($descriptif)
    {
        $this->descriptif = $descriptif;
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
     * Set $content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * Set question
     *
     * @param string $title
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
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
