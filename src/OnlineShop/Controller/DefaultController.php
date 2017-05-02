<?php

namespace OnlineShop\Controller;

use OnlineShop\Entity\Category;
use OnlineShop\Entity\Product;
use OnlineShop\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Route("/",name="default_index")
     * @Method("GET")
     *
     */
    public function indexPagination(Request $request)
    {
        $blogProducts = $this->getDoctrine()->getRepository('OnlineShop:Product')->findAll();

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         *
         */

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $blogProducts,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        return $this->render('default/index.html.twig', ['blog_products' => $result,
        ]);

    }


    /**
     * @Route("/category/{id}",name="category_products")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listProducts($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $products = $category->getProducts()->toArray();

        return $this->render('product/list.html.twig', ['products' => $products]
        );
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/profile", name="user_profile")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction()
    {
        $user = $this->getUser();
        return $this->render("admin/user/profile.html.twig", ['user'=>$user]);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/contactus",name="contactus")
     */
    public function contactPage()
    {
        return $this->render('default/contactus.html.twig');
    }

    /**
     * @Route("/faq",name="faq")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function FAQ()
    {
        return $this->render('default/faq.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/locationmap",name="location")
     */
    public function location()
    {
        return $this->render('default/location.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/terms",name="terms")
     */
    public function terms()
    {
        return $this->render('default/terms.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/refund",name="refund")
     */
    public function refund()
    {
        return $this->render('default/refund.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/privacy",name="privacy")
     */
    public function privacy()
    {
        return $this->render('default/privacy.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/carriers",name="carriers")
     */
    public function carriers()
    {
        return $this->render('default/carriers.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/info",name="info")
     */
    public function info()
    {
        return $this->render('default/info.html.twig');
    }

    /**
     * @return Response
     * @Route("/myorder",name="myorder")
     */
    public function singleAction()
    {
        if ($user = $this->getUser()) {
            /**
             * @var $user User
             */
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $order = $em->getRepository('OnlineShop:Orders')->findOneBy(['user' => $user]);
            if(!$order)
            {
                throw new Exception("You dont have any orders yet!");
            }

            $order->getStatus();


            return $this->render('default/orderindex.html.twig', [
                'order' => $order,
            ]);
        }

        else {

            return $this->redirectToRoute('security_login');
        }
    }

}
