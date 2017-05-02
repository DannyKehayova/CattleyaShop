<?php
/**
 * Created by PhpStorm.
 * User: Danny
 * Date: 23.4.2017 г.
 * Time: 12:46
 */

namespace OnlineShop\Controller;
use OnlineShop\Entity\User;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use OnlineShop\Entity\Product;
use OnlineShop\Entity\Cart;
use OnlineShop\Entity\CartItem;
use Doctrine\ORM\EntityManager;

/**
 * Cart controller.
 *
 * @Route("addcart")
 */
class CartController extends Controller
{

    /**
     * Displays "Shopping Cart" page.
     * @Route("/cartview",name="cart_view")
     * @return RedirectResponse|Response
     *
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('OnlineShop:Cart')->findOneBy(['user' => $user]);
        if(!$cart)
        {
            throw new Exception("You dont have products in the cart yet! Go shop some :)");
        }
        else if ($user = $this->getUser()) {
            /**
             * @var $user User
             */
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $cart = $em->getRepository('OnlineShop:Cart')->findOneBy(['user' => $user]);
            $items = $cart->getItems();
            $total = self::calculateTotalPrice($items);

            return $this->render('cart/index.html.twig', [
                'items' => $items,
                'total' => $total,
            ]);
        }

        else {

                return $this->redirectToRoute('security_login');
            }
        }




    /**
     * Adds products to shopping cart.
     *
     * @param int $id
     * @Route("/{id}", name="add_to_cart")
     * @Method({"GET", "POST"})
     * @return RedirectResponse
     */
    public function addAction($id)
    {
        if ($user = $this->getUser()) {
            /**
             * @var $product Product
             * @var $user User
             * @var $em EntityManager
             */
            $now = new DateTime;
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('OnlineShop:Product')->find($id);
            $cart = $em->getRepository('OnlineShop:Cart')->findOneBy(['user' => $user]);
            $cart = $this->persistCart($user, $now, $em, $cart);
            $em->flush();
            $cartItem = $em->getRepository('OnlineShop:CartItem')->findOneBy(['cart' => $cart, 'product' => $product]);
            $this->persistCartItem($now, $cart, $product, $em, $cartItem);

            $em->flush();
            $this->addFlash('success', sprintf('%s successfully added to cart', $product->getName()));
            return $this->redirectToRoute('cart_view');
        } else {

            return $this->redirectToRoute('security_login');
        }
    }




    /**
     * Creates the cart for current user.
     *
     * @param User $user
     * @param DateTime $now
     * @param EntityManager $em
     * @param Cart $cart
     *
     * @return Cart
     */
    private function persistCart (User $user, DateTime $now, EntityManager $em, Cart $cart = null)
    {
        if (!$cart) {
            $cart = new Cart;
            $cart->setUser($user);
            $cart->setCreatedAt($now);
            $cart->setModifiedAt($now);
        } else {
            $cart->setModifiedAt($now);
        }
        $em->persist($cart);
        return $cart;
    }

    /**
     * Creates the chosen cart item.
     *
     * @param DateTime $now
     * @param Cart $cart
     * @param Product $product
     * @param EntityManager $em
     * @param CartItem $cartItem
     */
    private function persistCartItem(DateTime $now, Cart $cart, Product $product, EntityManager $em, CartItem $cartItem = null)
    {
        if (!$cartItem) {
            $cartItem = new CartItem;
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);

            $cartItem->setItemPrice($product->getPrice());
            $cartItem->setCreatedAt($now);
            $cartItem->setModifiedAt($now);
        } else {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
            $cartItem->setModifiedAt($now);
        }
        $em->persist($cartItem);
        $product->setQuantity($product->getQuantity()-1);

    }


    /**
     * Calculates total price.
     *
     * @param CartItem[] $items
     *
     * @return float
     */
    public static function calculateTotalPrice($items)
    {
        $total = null;
        foreach ($items as $item) {
            $total += floatval($item->getQuantity() * $item->getItemPrice());
        }
        return $total;
    }


    /**
     * @Route("/delete/{id}", name="user_items_delete")
     * @param $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteProductFromCart($id,Request $request)
    {
        $currentUser=$this->getUser();
        if(!$currentUser){
            $this->redirectToRoute('security_login');
        }
        else {
            $item = $this->getDoctrine()->getRepository(CartItem::class)->find($id);
            $em = $this->getDoctrine()->getManager();
            if (!$item) {
                throw $this->createNotFoundException('No product found for id ' . $id);
            }
            $em->remove($item);
            $em->flush();
        }
            return $this->redirectToRoute("cart_view");


}}