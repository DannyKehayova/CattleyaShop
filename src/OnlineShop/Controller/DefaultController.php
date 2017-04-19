<?php

namespace OnlineShop\Controller;

use OnlineShop\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default_index")
     */
    public function indexAction(Request $request)
    {

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('default/index.html.twig', ['categories' => $categories]);

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
}
