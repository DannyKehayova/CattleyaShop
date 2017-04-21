<?php

namespace OnlineShop\Controller;

use OnlineShop\Entity\Product;
use OnlineShop\OnlineShop;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Asset\Tests\UrlPackageTest;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;


/**
 * Product controller.
 *
 * @Route("products")
 */
class ProductController extends Controller
{
//    /**
//     * Lists all product entities.
//     *
//     * @Route("/", name="products_index")
//     * @Method("GET")
//     */
//    public function indexAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $products = $em->getRepository('OnlineShop:Product')->findAll();
//
//        return $this->render('product/index.html.twig', array(
//            'products' => $products,
//        ));
//    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="products_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('OnlineShop\Form\ProductType', $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $product->getPhotoForm();

            if (!$file) {
                $form->get('photo_form')->addError(new FormError('Image is required'));
            } else {
                $filename = md5($product->getName() . '' . $product->getId());

                $file->move(
                    $this->get('kernel')->getRootDir() . '/../web/images/products/',
                    $filename
                );

                $product->setPhoto($filename);
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();



                return $this->redirectToRoute('products_show', array('id' => $product->getId()));
            }
        }
            return $this->render('product/new.html.twig', array(
                'product' => $product,
                'form' => $form->createView(),
            ));
        }


    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="products_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="products_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $currentUser=$this->getUser();
        if(!$currentUser->isAdmin()&&!$currentUser->isEditor())
        {
            return $this->redirectToRoute('products_index');
        }
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('OnlineShop\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {


            if ($product->getPhotoForm() instanceof UploadedFile) {
                /** @var UploadedFile $file */
                $file = $product->getPhotoForm();

                $filename = md5($product->getName() . '' . $product->getId());

                $file->move(
                    $this->get('kernel')->getRootDir() . '/../web/images/products/',
                    $filename
                );

                $product->setPhoto($filename);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success','Product updated');
            return $this->redirectToRoute('products_index');
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="products_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $currentUser=$this->getUser();
        if(!$currentUser->isAdmin()&&!$currentUser->isEditor())
        {
            return $this->redirectToRoute('products_index');
        }
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('products_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('products_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/",name="list")
     * @Method("GET")
     *
     */
    public function KnpPagination(Request $request)
    {
        $blogProducts=$this->getDoctrine()->getRepository('OnlineShop:Product')->findAll();

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         *
         */

        $paginator=$this->get('knp_paginator');
        $result=$paginator->paginate(
            $blogProducts,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)
        );

        return $this->render('product/pagination.html.twig',['blog_products'=>$result,
            ]);

    }
}
