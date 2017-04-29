<?php
namespace OnlineShop\Controller\Admin;
use OnlineShop\Entity\ProductsOrder;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use OnlineShop\Entity\Orders;
/**
 *
 *
 * @Route("/order")
 */
class OrdersController extends Controller
{
    /**
     *
     *
     * @return Response
     *
     * @Route("/", name="productsorder_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $productsOrders = $em->getRepository('OnlineShop:Orders')->findAll();
        return $this->render('admin/productsorder/index.html.twig', [
            'productsOrders' => $productsOrders,
        ]);
    }

    /**
     *
     *
     * @param Orders $productsOrder
     *
     * @return Response
     *
     * @Route("/{id}", name="salesorder_show")
     * @Method("GET")
     */
    public function showAction(Orders $productsOrder)
    {
        return $this->render('admin/productsorder/show.html.twig', [
            'productsOrder' => $productsOrder,
        ]);
    }

    /**
     *
     *
     * @param Request $request
     * @param Orders $productsOrder
     *
     * @return RedirectResponse|Response
     *
     * @Route("/{id}/edit", name="productsorder_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Orders $productsOrder)
    {
        $editForm = $this->createForm('OnlineShop\Form\ProductsOrderType', $productsOrder);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productsOrder);
            $em->flush();

            return $this->redirectToRoute('productsorder_edit', ['id' => $productsOrder->getId()]);
        }
        return $this->render('admin/productsorder/edit.html.twig', [
            'productsOrder' => $productsOrder,
            'edit_form' => $editForm->createView(),
        ]);
    }
    /**
     *
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function cancelAction($id)
    {
        if ($user = $this->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $productsOrder = $em->getRepository('OnlineShop:Orders')
                ->findOneBy(['user' => $user, 'id' => $id]);
            if ($productsOrder->getStatus() === Orders::STATUS_PROCESSING) {
                $productsOrder->setStatus(Orders::STATUS_CANCELED);
                $em->persist($productsOrder);
                $em->flush();
                $this->addFlash('success', 'Order updated.');
                return $this->redirectToRoute('user_profile');
            }
        }
        $this->addFlash('warning', 'Order can not be updated.');
        return $this->redirectToRoute('user_profile');
    }
}