<?php

namespace OnlineShop\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsOrder
 *
 * @ORM\Table(name="products_order")
 * @ORM\Entity(repositoryClass="OnlineShop\Repository\ProductsOrderRepository")
 */
class ProductsOrder
{


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="productPrice", type="decimal", precision=10, scale=2)
     */
    private $productPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="totalPrice", type="decimal", precision=10, scale=2)
     */
    private $totalPrice;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="modifiedAt", type="datetime")
     */
    private $modifiedAt;




    /**
     *
     *
     * @var Orders
     *
     * @ORM\ManyToOne(targetEntity="OnlineShop\Entity\Orders", inversedBy="products")
     * @ORM\JoinColumn(name="products_order_id", referencedColumnName="id")
     */
    private $productsOrder;

    /**
     *
     *
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="OnlineShop\Entity\Product", inversedBy="orderProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @return Orders
     */
    public function getProductsOrder(): Orders
    {
        return $this->productsOrder;
    }

    /**
     * @param Orders $productsOrder
     */
    public function setProductsOrder(Orders $productsOrder)
    {
        $this->productsOrder = $productsOrder;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ProductsOrder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return ProductsOrder
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set productPrice
     *
     * @param float $productPrice
     *
     * @return ProductsOrder
     */
    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    /**
     * Get productPrice
     *
     * @return float
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }

    /**
     * Set totalPrice
     *
     * @param float $totalPrice
     *
     * @return ProductsOrder
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return ProductsOrder
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param DateTime $modifiedAt
     *
     * @return ProductsOrder
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }


}

