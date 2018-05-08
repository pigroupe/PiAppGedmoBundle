<?php
/**
 * This file is part of the <PI_CRUD> project.
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-04-11
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

use PiApp\GedmoBundle\Layers\Domain\Entity\Slider;
use PiApp\GedmoBundle\Form\SliderType;
use PiApp\GedmoBundle\Layers\Domain\Entity\Translation\SliderTranslation;

/**
 * Slider controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SliderController extends abstractController
{
    protected $_entityName = "PiAppGedmoBundle:Slider";

    /**
     * Enabled Slider entities.
     *
     * @Route("/admin/gedmo/slider/enabled", name="admin_gedmo_slider_enabledentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function enabledajaxAction()
    {
        return parent::enabledajaxAction();
    }

    /**
     * Disable Slider  entities.
     *
     * @Route("/admin/gedmo/slider/disable", name="admin_gedmo_slider_disablentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function disableajaxAction()
    {
        return parent::disableajaxAction();
    }

    /**
     * Position Slider entities.
     *
     * @Route("/admin/gedmo/slider/position", name="admin_gedmo_slider_position_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function positionajaxAction()
    {
        return parent::positionajaxAction();
    }

    /**
     * Delete Slider entities.
     *
     * @Route("/admin/gedmo/slider/delete", name="admin_gedmo_slider_deletentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteajaxAction()
    {
        return parent::deletajaxAction();
    }

    /**
     * Archive a Slider entity.
     *
     * @Route("/admin/gedmo/slider/archive", name="admin_gedmo_slider_archiveentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function archiveajaxAction()
    {
        return parent::archiveajaxAction();
    }

    /**
     * Lists all Slider entities.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $locale     = $this->container->get('request_stack')->getCurrentRequest()->getLocale();

        $category   = $this->container->get('request_stack')->getCurrentRequest()->query->get('category');
        $NoLayout   = $this->container->get('request_stack')->getCurrentRequest()->query->get('NoLayout');

        if ($NoLayout) {
        	$query    = $em->getRepository("PiAppGedmoBundle:Slider")->setContainer($this->container)->getAllByCategory($category, null, '', 'DESC', false);
        } else {
        	$query    = $em->getRepository("PiAppGedmoBundle:Slider")->getAllByCategory($category, null, '', 'ASC', false);
        }
        $entities   = $em->getRepository("PiAppGedmoBundle:Slider")->findTranslationsByQuery($locale, $query->getQuery(), 'object', false, true);

        return $this->render("PiAppGedmoBundle:Slider:index.html.twig", array(
                'entities' => $entities,
                'NoLayout' => $NoLayout,
                'category' => $category,
        ));
    }

    /**
     * Finds and displays a Slider entity.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
        $entity = $em->getRepository("PiAppGedmoBundle:Slider")->findOneByEntity($locale, $id, 'object');

        $category   = $this->container->get('request_stack')->getCurrentRequest()->query->get('category');
        $NoLayout   = $this->container->get('request_stack')->getCurrentRequest()->query->get('NoLayout');

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Slider');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PiAppGedmoBundle:Slider:show.html.twig", array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'NoLayout'      => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Displays a form to create a new Slider entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function newAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
        $entity = new Slider();
        //
        $category   = $this->container->get('request_stack')->getCurrentRequest()->query->get('category', '');
        $NoLayout   = $this->container->get('request_stack')->getCurrentRequest()->query->get('NoLayout');
        //
        $entity_cat = $em->getRepository("PiAppGedmoBundle:Category")->find($category);
        if ( !empty($category) && ($entity_cat instanceof \PiApp\GedmoBundle\Layers\Domain\Entity\Category))
            $entity->setCategory($entity_cat);
        elseif (!empty($category))
            $entity->setCategory($category);
        //
        $form   = $this->createForm(new SliderType($em, $locale, $this->container), $entity, array('show_legend' => false));

        return $this->render("PiAppGedmoBundle:Slider:new.html.twig", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'     => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Creates a new Slider entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request_stack')->getCurrentRequest()->getLocale();

        $category   = $this->container->get('request_stack')->getCurrentRequest()->query->get('category');
        $NoLayout   = $this->container->get('request_stack')->getCurrentRequest()->query->get('NoLayout');

        $entity  = new Slider();
        $request = $this->getRequest();
        $form    = $this->createForm(new SliderType($em, $locale, $this->container), $entity, array('show_legend' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_gedmo_slider_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout, 'category' => $category)));
        }

        return $this->render("PiAppGedmoBundle:Slider:new.html.twig", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Displays a form to edit an existing Slider entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function editAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
        $entity = $em->getRepository("PiAppGedmoBundle:Slider")->findOneByEntity($locale, $id, 'object');

        $category   = $this->container->get('request_stack')->getCurrentRequest()->query->get('category');
        $NoLayout   = $this->container->get('request_stack')->getCurrentRequest()->query->get('NoLayout');

        if (!$entity) {
            $entity = $em->getRepository("PiAppGedmoBundle:Slider")->find($id);
            $entity->addTranslation(new SliderTranslation($locale));
        }

        $editForm   = $this->createForm(new SliderType($em, $locale, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PiAppGedmoBundle:Slider:edit.html.twig", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'      => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Edits an existing Slider entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function updateAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
        $entity = $em->getRepository("PiAppGedmoBundle:Slider")->findOneByEntity($locale, $id, "object");

        $category   = $this->container->get('request_stack')->getCurrentRequest()->query->get('category');
        $NoLayout   = $this->container->get('request_stack')->getCurrentRequest()->query->get('NoLayout');

        if (!$entity) {
            $entity = $em->getRepository("PiAppGedmoBundle:Slider")->find($id);
        }

        $editForm   = $this->createForm(new SliderType($em, $locale, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($this->getRequest(), $entity);
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_gedmo_slider_edit', array('id' => $id, 'NoLayout' => $NoLayout, 'category' => $category)));
        }

        return $this->render("PiAppGedmoBundle:Slider:edit.html.twig", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'      => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Deletes a Slider entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteAction($id)
    {
        $em      = $this->getDoctrine()->getManager();
        $locale     = $this->container->get('request_stack')->getCurrentRequest()->getLocale();

        $NoLayout   = $this->container->get('request_stack')->getCurrentRequest()->query->get('NoLayout');
        $category   = $this->container->get('request_stack')->getCurrentRequest()->query->get('category');

        $form      = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $entity = $em->getRepository("PiAppGedmoBundle:Slider")->findOneByEntity($locale, $id, 'object');

            if (!$entity) {
                throw ControllerException::NotFoundEntity('Slider');
            }

            try {
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $e) {
                $this->container->get('request_stack')->getCurrentRequest()->getSession()->getFlashBag()->clear();
                $this->container->get('request_stack')->getCurrentRequest()->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_gedmo_slider', array('NoLayout' => $NoLayout, 'category' => $category)));
    }

    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
