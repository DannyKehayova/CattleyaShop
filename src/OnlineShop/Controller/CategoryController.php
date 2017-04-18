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
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Route("/", name="admin_categories")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCategories()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('admin/category/list.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/edit/{id}",name="admin_categories_edit")
     * @param $id
     * @param Request $request
     * @return Response
     */

    public function editCategory($id,Request $request)
    {
        $category=$this->getDoctrine()->getRepository(Category::class)->find($id);
        $form=$this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_categories');

        }

        return $this->render('admin/category/edit.html.twig',
            ['category' => $category, 'form' => $form->createView()]);

    }

    /**
     * @Route("/delete/{id}",name="admin_categories_delete")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteCategory($id, Request $request)
    {
        $category=$this->getDoctrine()->getRepository(Category::class)->find($id);
        $form=$this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            foreach ($category->getProducts() as $product){
                $em->remove($product);
            }
            $em->remove($category);
            $em->flush();

            return $this->redirectToRoute('admin_categories');

        }

        return $this->render('admin/category/delete.html.twig',
            ['category' => $category, 'form' => $form->createView()]);

    }

}