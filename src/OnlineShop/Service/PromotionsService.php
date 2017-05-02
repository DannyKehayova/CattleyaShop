<?php
namespace OnlineShop\Service;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use OnlineShop\Entity\Category;
use Symfony\Component\HttpFoundation\Session\Session;
use OnlineShop\Entity\Product;
use OnlineShop\Entity\Promotions;
use OnlineShop\Service\PromotionsServiceInterface;

class PromotionsService implements PromotionsServiceInterface
{
    private $entityManager;
    private $session;
    private $manager;
    public function __construct(
        EntityManagerInterface $entityManager,
        Session $session,
        ManagerRegistry $manager)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->manager = $manager;
    }
    public function setPromotionToCategory(Promotions $promotion,Category $category)
    {
        /** @var ArrayCollection|Product[] $products */
        $products = $category->getProducts();
        foreach ($products as $product) {
            if ($product->getPromotions()->contains($promotion)) {
                continue;
            }
            $product->setPromotion($promotion);
        }
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        $this->session
            ->getFlashBag()
            ->add("success",
                "All products of category '{$category->getName()}' was promoted with '{$promotion->getName()}' promotion");
    }
    public function setPromotionToProducts(Promotions $promotion)
    {
        $allProducts = $this->manager->getRepository(Product::class)
            ->findAll();
        foreach ($allProducts as $product) {
            if ($product->getPromotions()->contains($promotion)) {
                continue;
            }
            $product->setPromotion($promotion);
        }
        $this->entityManager->persist($promotion);
        $this->entityManager->flush();
        $this->session
            ->getFlashBag()
            ->add("success", "All products was promoted with '{$promotion->getName()}' promotion");
    }
    public function unsetPromotionToProducts(Promotions $promotion)
    {
        $allProducts = $this->manager->getRepository(Product::class)
            ->findAll();
        foreach ($allProducts as $product) {
            if (!$product->getPromotions()->contains($promotion)) {
                continue;
            }
            $product->unsetPromotion($promotion);
        }
        $this->entityManager->persist($promotion);
        $this->entityManager->flush();
        $this->session
            ->getFlashBag()
            ->add("success", "'{$promotion->getName()}' promotion was removed from all products!");
    }
}