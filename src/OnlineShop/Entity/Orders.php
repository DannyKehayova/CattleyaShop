<?php

namespace OnlineShop\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="OnlineShop\Repository\OrdersRepository")
 */
class Orders
{

    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETE = 'complete';
    const STATUS_CANCELED = 'canceled';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="productsPrice", type="decimal", precision=10, scale=2)
     */
    private $productsPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSum", type="decimal", precision=10, scale=2)
     */
    private $totalSum;


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
     * @var string
     *
     * @ORM\Column(name="userEmail", type="string", length=255)
     */
    private $userEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="userFirstName", type="string", length=255)
     */
    private $userFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="userSecondName", type="string", length=255)
     */
    private $userSecondName;



    /**
     * @var string
     *
     * @ORM\Column(name="userCity", type="string", length=255)
     */
    private $userCity;

    /**
     * @var string
     *
     * @ORM\Column(name="userAdress", type="string", length=255)
     */
    private $userAdress;


    /**
     * The status of the order.
     *
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="userPhone", type="string", length=255)
     */
    private $userPhone;


    /**
     *
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="OnlineShop\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProductsOrder", mappedBy="products_order")
     */
    private $products;

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
     * Set productsPrice
     *
     * @param float $productsPrice
     *
     * @return Orders
     */
    public function setProductsPrice($productsPrice)
    {
        $this->productsPrice = $productsPrice;

        return $this;
    }

    /**
     * Get productsPrice
     *
     * @return float
     */
    public function getProductsPrice()
    {
        return $this->productsPrice;
    }

    /**
     * Set totalSum
     *
     * @param float $totalSum
     *
     * @return Orders
     */
    public function setTotalSum($totalSum)
    {
        $this->totalSum = $totalSum;

        return $this;
    }

    /**
     * Get totalSum
     *
     * @return float
     */
    public function getTotalSum()
    {
        return $this->totalSum;
    }



    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Orders
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return Orders
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     *
     * @return Orders
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set userFirstName
     *
     * @param string $userFirstName
     *
     * @return Orders
     */
    public function setUserFirstName($userFirstName)
    {
        $this->userFirstName = $userFirstName;

        return $this;
    }

    /**
     * Get userFirstName
     *
     * @return string
     */
    public function getUserFirstName()
    {
        return $this->userFirstName;
    }

    /**
     * Set userSecondName
     *
     * @param string $userSecondName
     *
     * @return Orders
     */
    public function setUserSecondName($userSecondName)
    {
        $this->userSecondName = $userSecondName;

        return $this;
    }

    /**
     * Get userSecondName
     *
     * @return string
     */
    public function getUserSecondName()
    {
        return $this->userSecondName;
    }

    /**
     * Set userCity
     *
     * @param string $userCity
     *
     * @return Orders
     */
    public function setUserCity($userCity)
    {
        $this->userCity = $userCity;

        return $this;
    }

    /**
     * Get userCity
     *
     * @return string
     */
    public function getUserCity()
    {
        return $this->userCity;
    }

    /**
     * Set userAdress
     *
     * @param string $userAdress
     *
     * @return Orders
     */
    public function setUserAdress($userAdress)
    {
        $this->userAdress = $userAdress;

        return $this;
    }

    /**
     * Get userAdress
     *
     * @return string
     */
    public function getUserAdress()
    {
        return $this->userAdress;
    }

    /**
     * Set userPhone
     *
     * @param string $userPhone
     *
     * @return Orders
     */
    public function setUserPhone($userPhone)
    {
        $this->userPhone = $userPhone;

        return $this;
    }

    /**
     * Get userPhone
     *
     * @return string
     */
    public function getUserPhone()
    {
        return $this->userPhone;
    }

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection $products
     */
    public function setProducts(ArrayCollection $products)
    {
        $this->products = $products;
    }


}

