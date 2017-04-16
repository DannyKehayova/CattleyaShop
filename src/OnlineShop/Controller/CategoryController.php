<?php
/**
 * Created by PhpStorm.
 * User: Danny
 * Date: 16.4.2017 г.
 * Time: 20:58
 */

namespace OnlineShop\Controller;
use OnlineShop\Entity\Category;
use OnlineShop\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/categories")
 *
 * Class CategoryController
 * @package OnlineShop\Controller
 */
class CategoryController extends Controller
{


    /**
     * @Route("/create", name="categories_create")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createCategory(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('products_index');
        }

        return $this->render('/category/create.html.twig', array('form' => $form->createView()));
    }
}