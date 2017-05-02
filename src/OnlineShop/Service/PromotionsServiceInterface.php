<?php
namespace OnlineShop\Service;
use OnlineShop\Entity\Category;
use OnlineShop\Entity\Promotions;
use OnlineShop\Service\PromotionsService;

interface PromotionsServiceInterface
{
    public function setPromotionToCategory(Promotions $promotion, Category $category);
    public function setPromotionToProducts(Promotions $promotion);
    public function unsetPromotionToProducts(Promotions $promotion);
}