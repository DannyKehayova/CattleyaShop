<?php
namespace OnlineShop\Controller;
use OnlineShop\Entity\User;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use OnlineShop\Entity\CartItem;
use OnlineShop\Entity\Orders;
use OnlineShop\Entity\ProductsOrder;

class CheckoutController extends Controller
{
    /**
     * Constructs first checkout step gathering the shipment information.
     * @Route("/checkout",name="checkout")
     * @return Response
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('OnlineShop:Cart')->findOneBy(['user' => $user]);
        if(!$cart)
        {
            throw new Exception("You dont have products for checkout!");
        }
        $items = $cart->getItems();
        $total = CartController::calculateTotalPrice($items);
        return $this->render('checkout/index.html.twig', [
            'user' => $user,
            'items' => $items,
            'cart_subtotal' => $total,
        ]);
    }



    /**
     * Creates an order.
     *
     * Once the POST submission hits the controller, a new order with all of the related
     * items gets created. At the same time, the cart and cart items are cleared. Finally, the
     * customer is redirected to the order success page.
     * @Route("/checoutprocess",name="checkoutprocess")
     * @return RedirectResponse
     */
    public function processAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('OnlineShop:Cart')->findOneBy(['user' => $user]);
        $items = $cart->getItems();
        $salesOrder = new Orders();
        $now = new DateTime;


        $checkoutInfo = $this->get('session')->get('checkoutInfo');

        foreach ($items as $item) {
            /** @var CartItem $item */
            $orderItem = new ProductsOrder();
            $orderItem->setProductsOrder($salesOrder);
            $orderItem->setName($item->getProduct()->getName());
            $orderItem->setQuantity($item->getQuantity());
            $orderItem->setProductPrice($item->getItemPrice());
            $orderItem->setTotalPrice($item->getQuantity() * $item->getItemPrice());
            $orderItem->setModifiedAt($now);
            $orderItem->setCreatedAt($now);
            $orderItem->setProduct($item->getProduct());
            $item->getProduct()->setQuantity($item->getProduct()->getQuantity() - $item->getQuantity());
            $em->persist($orderItem);
            $em->remove($item);

            $salesOrder->setUser($user);


            $salesOrder->setProductsPrice($orderItem->getProductPrice());

            $salesOrder->setTotalSum($orderItem->getTotalPrice());

            $salesOrder->setCreatedAt($now);
            $salesOrder->setModifiedAt($now);
            $salesOrder->setUserEmail($user->getEmail());
            $salesOrder->setUserFirstName($user->getFirstName());
            $salesOrder->setUserSecondName($user->getSecondName());
            $salesOrder->setUserCity($user->getCity());

            $salesOrder->setUserAdress($user->getAddress());
            $salesOrder->setUserPhone($user->getPhone());
            $salesOrder->setStatus(Orders::STATUS_PROCESSING);
            /** @var User $user */
            $userCash=$user->getCash();
            $total=$salesOrder->getTotalSum();
            if($userCash>=$total){
               $user->setCash($userCash-$total);

            }
            else {
                throw new Exception("You don`t have enough money!");
            }
        }



        $em->persist($salesOrder);
        $em->flush();


        $em->remove($cart);
        $em->flush();
        $this->get('session')->set('last_order', $salesOrder->getId());
        return $this->redirectToRoute('success_order');
    }

    /**
     * Creates the order success page.
     * @Route("/successorder",name="success_order")
     * @return Response
     */
    public function successAction()
    {
        return $this->render('checkout/success.html.twig', [
            'last_order' => $this->get('session')->get('last_order')
        ]);
    }

}
