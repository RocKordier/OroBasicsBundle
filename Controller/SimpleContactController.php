<?php

namespace EHDev\Bundle\BasicsBundle\Controller;

use EHDev\Bundle\BasicsBundle\Entity\SimpleContact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SecurityBundle\Annotation\Acl;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class SimpleContactController
 *
 * @package EHDev\Bundle\BasicsBundle\Controller
 * @Route("/simple_contact")
 */
class SimpleContactController extends Controller
{
    /**
     * Index
     * @Route("/", name="ehdev_basics_simplecontact_index")
     * @AclAncestor("ehdev_basics_simplecontact_view")
     *
     * @Template
     */
    public function indexAction()
    {
        return [];
    }

//    /**
//     * @Route("/view/{id}", name="ehdev_basics_simplecontact_view", requirements={"id"="\d+"})
//     * @Template
//     * @Acl(
//     *      id="ehdev_basics_simplecontact_view",
//     *      type="entity",
//     *      permission="VIEW",
//     *      class="EHDevBasicsBundle:SimpleContact"
//     * )
//     *
//     * @param \EHDev\Bundle\BasicsBundle\Entity\SimpleContact $simpleContact
//     */
//    public function viewAction(SimpleContact $simpleContact)
//    {
//        return [
//            'entity' => $simpleContact,
//        ];
//    }

    /**
     * @Route("/create", name="ehdev_basics_simplecontact_create")
     * @Template("@EHDevBasics/SimpleContact/update.html.twig")
     * @Acl(
     *      id="ehdev_basics_simplecontact_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="EHDevBasicsBundle:SimpleContact"
     * )
     */
    public function createAction()
    {
        return $this->update($this->getManager()->createEntity());
    }

    /**
     * Update form
     * @Route("/update/{id}", name="ehdev_basics_simplecontact_update", requirements={"id"="\d+"})
     *
     * @Template
     * @Acl(
     *      id="ehdev_basics_simplecontact_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="EHDevBasicsBundle:SimpleContact"
     * )
     */
    public function updateAction(SimpleContact $entity)
    {
        return $this->update($entity);
    }

    /**
     * @param \EHDev\Bundle\BasicsBundle\Entity\SimpleContact $entity
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function update(SimpleContact $entity)
    {
        return $this->get('oro_form.model.update_handler')->update(
            $entity,
            $this->get('ehdev.basics.simplecontact.form'),
            $this->get('translator')->trans('ehdev.basics.simplecontact.saved.message')
        );
    }

    protected function getManager()
    {
        return $this->get('ehdev.basics.simplecontact.manager');
    }
}
