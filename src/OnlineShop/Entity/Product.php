<?php

namespace OnlineShop\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="OnlineShop\Repository\ProductRepository")
 */
class Product
{



    /**
     * @ORM\ManyToMany(targetEntity="OnlineShop\Entity\Promotions", inversedBy="products")
     * @ORM\JoinTable(name="product_promotions")
     *
     * @var ArrayCollection
     */
    private $promotions;

    /**
     * @return mixed
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * @param mixed $promotions
     */
    public function setPromotions($promotions)
    {
        $this->promotions = $promotions;
    }

    public function setPromotion(Promotions $promotion)
    {
        $this->promotions[] = $promotion;
    }
    public function unsetPromotion(Promotions $promotion)
    {
        $this->promotions->removeElement($promotion);
    }

    /**
     * @var int
     * @ORM\Column(name="category_id", type="integer")
     */
    private $categoryId;

    /**
     * @return int
     */
    public function getCategoryId() : int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="OnlineShop\Entity\Category",inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * The one-to-many association between Product and CartItem.
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OnlineShop\Entity\CartItem", mappedBy="product")
     */
    private $items;

    /**
     *
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OnlineShop\Entity\ProductsOrder", mappedBy="product")
     */
    private $orderProducts;

    /**
     * @return ArrayCollection
     */
    public function getItems(): ArrayCollection
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems(ArrayCollection $items)
    {
        $this->items = $items;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrderProducts(): ArrayCollection
    {
        return $this->orderProducts;
    }

    /**
     * @param ArrayCollection $orderProducts
     */
    public function setOrderProducts(ArrayCollection $orderProducts)
    {
        $this->orderProducts = $orderProducts;
    }


    /**
     * Initializes cart items and order items.
     */
    public function __construct() {
        $this->items = new ArrayCollection;
        $this->orderProducts = new ArrayCollection;
        $this->promotions = new ArrayCollection();

    }
    /**
     * @Assert\DateTime()
     */
    protected $createdAt;
    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }


    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="price", type="decimal", precision=5, scale=2)
     */
    private $price;

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @Assert\Image(mimeTypes={"image/png", "image/jpeg"}, maxSize="5M")
     */
    private $photo_form;

    /**
     * @return mixed
     */
    public function getPhotoForm()
    {
        return $this->photo_form;
    }

    /**
     * @param mixed $photo_form
     */
    public function setPhotoForm($photo_form)
    {
        $this->photo_form = $photo_form;
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
     * @return Product
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
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {

        return $this->price;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="promotionPrice", type="decimal", precision=11, scale=2)
     */
    private $promotionPrice;

    /**
     * @return string
     */
    public function getPromotionPrice(): string
    {
        if ($this->hasActivePromotion()) {
            $discount = $this->getPrice() * $this->getActualPromotion()->getDiscount() / 100;

            return $this->getPrice() - $discount;
        }
        return $this->promotionPrice;
    }

    /**
     * @param string $promotionPrice
     */
    public function setPromotionPrice(string $promotionPrice)
    {

        $this->promotionPrice = $promotionPrice;
    }


    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Product
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return bool
     */
    public function hasActivePromotion()
    {
        return $this->getActualPromotion() !== null;
    }

    /**
     * @return Promotions|null
     */
    public function getActualPromotion()
    {
        $activePromotions = $this->promotions->filter(
            function (Promotions $p) {
                return $p->getEndDate() > new \DateTime("now") &&
                $p->getStartDate() <= new \DateTime("now");
            });
        if ($activePromotions->count() == 0) {
            return null;
        }
        if ($activePromotions->count() == 1) {
            return $activePromotions->first();
        }
        $arr = $activePromotions->getValues();
        usort($arr, function (Promotions $p1, Promotions $p2) {
            return $p2->getDiscount() - $p1->getDiscount();
        });
        return $arr[0];
    }

    /**
     * @return float
     */
    public function getOriginalPrice()
    {
        return $this->price;
    }

    public function __toString()
    {
        return $this->name;
    }
}

