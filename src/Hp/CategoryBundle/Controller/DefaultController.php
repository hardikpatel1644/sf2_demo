<?php

namespace Hp\CategoryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Hp\CategoryBundle\Form\CategoryType;
use Hp\CategoryBundle\Entity\Category;

/**
 * @Route("/category")
 */
class DefaultController extends Controller {

    /**
     * @Route("/list", name="_hp_category_list")
     */
    public function indexAction() {
        $request = $this->getRequest();
        $session = $request->getSession();
        $repository = $this->getDoctrine()->getRepository('HpCategoryBundle:Category');
        $asCat = $repository->findAllOrderedByName();
        return $this->render('HpCategoryBundle:Default:index.html.twig', array('asCategory' => $asCat));
    }

    /**
     * @Route("/add", name="_hp_category_add")
     */
    public function addAction() {
        $category = new Category();
        $repository = $this->getDoctrine()->getRepository('HpCategoryBundle:Category');
        $query = $repository->createQueryBuilder('c')
                ->getQuery();
        $asCategory = $query->getArrayResult();
        $form = $this->createForm(new CategoryType($asCategory), $category);
        $request = $this->getRequest();



        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);


            if ($form->isValid()) {
                ///var_dump($form->getData()->getIdParent());exit;

                $form->getData()->setLvl(0);

                $em = $this->getDoctrine()
                        ->getEntityManager();
                $em->persist($category);
                $em->flush();
                $category->upload();
                return $this->redirect($this->generateUrl('_hp_category_list'));
            }
        }
        return $this->render('HpCategoryBundle:Default:add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/edit/{id}", name="_hp_category_edit")
     */
    public function editAction($id) {

        $repository = $this->getDoctrine()->getRepository('HpCategoryBundle:Category');
        $category = $repository->find($id);

        $query = $repository->createQueryBuilder('c')
                ->getQuery();
        $asCategory = $query->getArrayResult();
        $form = $this->createForm(new CategoryType($asCategory), $category);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);


            if ($form->isValid()) {
                ///var_dump($form->getData()->getIdParent());exit;

                $form->getData()->setLvl(0);

                $em = $this->getDoctrine()
                        ->getEntityManager();
                $em->persist($category);
                $em->flush();
                $category->upload();
                return $this->redirect($this->generateUrl('_hp_category_list'));
            }
        }
        return $this->render('HpCategoryBundle:Default:edit.html.twig', array('form' => $form->createView(), 'id' => $id));
    }

    /**
     * @Route("/delete/{id}", name="_hp_category_delete")
     */
    public function delete($id) {
        $repository = $this->getDoctrine()->getRepository('HpCategoryBundle:Category');
        $category = $repository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('No category found');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($category);
        $em->flush();

        return $this->redirect($this->generateUrl('_hp_category_list'));
    }

}
