<?php
/**
 * This file is part of the <CmfPluginsGedmo> project.
 *
 * @category   CmfPluginsGedmo
 * @package    Entity
 * @subpackage ModelTranslation
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Layers\Domain\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Sfynx\CoreBundle\Layers\Domain\Model\AbstractTranslationEntity;

/**
 * @ORM\Entity(repositoryClass="PiApp\GedmoBundle\Repository\BlockRepository")
 * @ORM\Table(
 *         name="gedmo_block_translations",
 *         uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx_sfynx_trans_cmfplugin_block", columns={
 *             "locale", "object_id", "field"
 *         })}
 * )
 *
 * @category   CmfPluginsGedmo
 * @package    Entity
 * @subpackage ModelTranslation
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class BlockTranslation extends AbstractTranslationEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="PiApp\GedmoBundle\Layers\Domain\Entity\Block", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * Convinient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale = null, $field = null, $value = null)
    {
        if (!(null === $locale))
            $this->setLocale($locale);
        if (!(null === $field))
            $this->setField($field);
        if (!(null === $value))
            $this->setContent($value);
    }

}
