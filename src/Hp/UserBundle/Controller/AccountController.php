<?php

namespace Hp\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Hp\UserBundle\Form\Type\RegistrationType;
use Hp\UserBundle\Form\Model\Registration;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;

use Hp\UserBundle\Entity\User;

/**
 * Description of AccountController
 *
 * @author Hardik Patel <hardikpatel1644@gmail.com>
 */

/**
 * @Route("/account")
 */
class AccountController extends Controller {
    /**
     * @Route("/register", name="_register")
     */
    public function registerAction() {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration, array(
            'action' => $this->generateUrl('account_create'),
        ));

        return $this->render(
                        'HpUserBundle:Account:register.html.twig', array('form' => $form->createView())
        );
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/account/create", name="account_create")
     * @return type
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $registration = $form->getData();

            $user = new User();
            $user->setSalt(md5(uniqid()));
            
            
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($registration->getUser()->getPassword(),$user->getSalt());
            $registration->getUser()->setPassword($password);
            $registration->getUser()->setSalt($user->getSalt());
            $registration->getUser()->setIsActive(1);
            $em->persist($registration->getUser());
            $em->flush();

            return $this->redirect($this->generateUrl('_register'));
        }

        return $this->render(
                        'HpUserBundle:Account:register.html.twig', array('form' => $form->createView())
        );
    }

}

