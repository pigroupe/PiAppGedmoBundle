<?php
/**
 * This file is part of the <PI_CRUD> project.
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since XXXX-XX-XX
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sfynx\CoreBundle\Controller\abstractController;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

use PiApp\GedmoBundle\Layers\Domain\Entity\Block;
use PiApp\GedmoBundle\Form\BlockType;
use PiApp\GedmoBundle\Layers\Domain\Entity\Translation\BlockTranslation;

/**
 * Block controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class BlockController extends abstractController
{
    protected $_entityName = "PiAppGedmoBundle:Block";

    /**
     * Enabled Block entities.
     *
     * @Route("/admin/gedmo/block/enabled", name="admin_gedmo_block_enabledentity_ajax")
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function enabledajaxAction()
    {
        return parent::enabledajaxAction();
    }

    /**
     * Disable Block entities.
     *
     * @Route("/admin/gedmo/block/disable", name="admin_gedmo_block_disablentity_ajax")
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function disableajaxAction()
    {
        return parent::disableajaxAction();
    }

    /**
     * Position Block entities.
     *
     * @Route("/admin/gedmo/block/position", name="admin_gedmo_block_position_ajax")
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function positionajaxAction()
    {
        return parent::positionajaxAction();
    }

    /**
     * Delete Block entities.
     *
     * @Route("/admin/gedmo/block/delete", name="admin_gedmo_block_deletentity_ajax")
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteajaxAction()
    {
        return parent::deletajaxAction();
    }

    /**
     * Archive a Block entity.
     *
     * @Route("/admin/gedmo/block/archive", name="admin_gedmo_block_archiveentity_ajax")
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function archiveajaxAction()
    {
        return parent::archiveajaxAction();
    }

    /**
     * Lists all Block entities.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em      = $this->getDoctrine()->getManager();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $locale  = $request->getLocale();

        $category   = $request->query->get('category');
        $NoLayout   = $request->query->get('NoLayout');
        if (!$NoLayout)     $template = "index.html.twig"; else $template = "index.html.twig";

        if ($NoLayout){
            //$entities     = $em->getRepository("PiAppGedmoBundle:Block")->getAllEnableByCatAndByPosition($locale, $category, 'object');
            $query     = $em->getRepository("PiAppGedmoBundle:Block")->getAllByCategory($category, null, '', 'ASC', false)->getQuery();
            $entities  = $em->getRepository("PiAppGedmoBundle:Block")->findTranslationsByQuery($locale, $query, 'object', false);
        }else
            $entities  = $em->getRepository("PiAppGedmoBundle:Block")->findAllByEntity($locale, 'object');

        return $this->render("PiAppGedmoBundle:Block:$template", array(
                'entities' => $entities,
                'NoLayout'    => $NoLayout,
                'category'    => $category,
        ));
    }

    /**
     * Finds and displays a Block entity.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $locale    = $request->getLocale();
        $entity = $em->getRepository("PiAppGedmoBundle:Block")->findOneByEntity($locale, $id, 'object');

        $NoLayout   = $request->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Block');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PiAppGedmoBundle:Block:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Block entity.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function newAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $locale    = $request->getLocale();

        $entity = new Block();
        $form   = $this->createForm(new BlockType($em, $locale, $this->container), $entity, array('show_legend' => false));

        $NoLayout   = $request->query->get('NoLayout');
        $category   = $request->query->get('category', '');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";

        $entity_cat = $em->getRepository("PiAppGedmoBundle:Category")->find($category);
        if ( !empty($category) && ($entity_cat instanceof \PiApp\GedmoBundle\Layers\Domain\Entity\Category))
            $entity->setCategory($entity_cat);
        elseif (!empty($category))
            $entity->setCategory($category);

        return $this->render("PiAppGedmoBundle:Block:$template", array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Creates a new Block entity.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $locale    = $request->getLocale();

        $NoLayout   = $request->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";

        $entity  = new Block();
        $form    = $this->createForm(new BlockType($em, $locale, $this->container), $entity, array('show_legend' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_gedmo_block_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout)));

        }

        return $this->render("PiAppGedmoBundle:Block:$template", array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Displays a form to edit an existing Block entity.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function editAction($id)
    {
        $em      = $this->getDoctrine()->getManager();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $locale  = $request->getLocale();
        $entity  = $em->getRepository("PiAppGedmoBundle:Block")->findOneByEntity($locale, $id, 'object');

        $NoLayout   = $request->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";

        if (!$entity) {
            $entity = $em->getRepository("PiAppGedmoBundle:Block")->find($id);
            $entity->addTranslation(new BlockTranslation($locale));
        }

        $editForm   = $this->createForm(new BlockType($em, $locale, $this->container), $entity, ['show_legend' => false]);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PiAppGedmoBundle:Block:$template", [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'    => $NoLayout,
        ]);
    }

    /**
     * Edits an existing Block entity.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function updateAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $locale    = $request->getLocale();
        $entity = $em->getRepository("PiAppGedmoBundle:Block")->findOneByEntity($locale, $id, "object");

        $NoLayout   = $request->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";

        if (!$entity) {
            $entity = $em->getRepository("PiAppGedmoBundle:Block")->find($id);
        }

        $editForm   = $this->createForm(new BlockType($em, $locale, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($request, $entity);
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_gedmo_block_edit', array('id' => $id, 'NoLayout' => $NoLayout)));
        }

        return $this->render("PiAppGedmoBundle:Block:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'    => $NoLayout,
        ));
    }

    /**
     * Deletes a Block entity.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteAction($id)
    {
        $em      = $this->getDoctrine()->getManager();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $locale     = $request->getLocale();

        $NoLayout   = $request->query->get('NoLayout');
        $form      = $this->createDeleteForm($id);

        $form->bind($request);

        if ($form->isValid()) {
            $entity = $em->getRepository("PiAppGedmoBundle:Block")->findOneByEntity($locale, $id, 'object');

            if (!$entity) {
                throw ControllerException::NotFoundEntity('Block');
            }

            try {
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $e) {
                $request->getSession()->getFlashBag()->clear();
                $request->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_gedmo_block', array('NoLayout' => $NoLayout)));
    }

    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Template : Finds and displays a Block entity.
     *
     * @Cache(maxage="86400")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function _template_showAction($id, $template = '_tmp_show.html.twig', $lang = "")
    {
        $em = $this->getDoctrine()->getManager();

        if (empty($lang)) {
            $lang = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
        }

        $entity = $em->getRepository("PiAppGedmoBundle:Block")->findOneByEntity($lang, $id, 'object', false);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Block');
        }

        return $this->render("PiAppGedmoBundle:Block:$template", [
            'entity'   => $entity,
            'locale'   => $lang,
            'lang'     => $lang,
        ]);
    }

    /**
     * Template : Finds and displays a list of Block entity.
     *
     * @Cache(maxage="86400")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function _template_listAction($category = '', $MaxResults = null, $template = '_tmp_list.html.twig', $order = 'DESC', $lang = "")
    {
        $em         = $this->getDoctrine()->getManager();

        if (empty($lang))
            $lang    = $this->container->get('request_stack')->getCurrentRequest()->getLocale();

        $query        = $em->getRepository("PiAppGedmoBundle:Block")->getAllByCategory($category, $MaxResults, $order)->getQuery();
        $entities   = $em->getRepository("PiAppGedmoBundle:Block")->findTranslationsByQuery($lang, $query, 'object', false);

        return $this->render("PiAppGedmoBundle:Block:$template", array(
            'entities' => $entities,
            'cat'       => ucfirst($category),
            'locale'   => $lang,
            'lang'       => $lang,
        ));
    }
}
