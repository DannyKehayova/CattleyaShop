<?php
namespace OnlineShop\Form;
use OnlineShop\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use OnlineShop\Entity\Product;
use OnlineShop\Entity\Promotions;
class CategoryPromoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("promotion", EntityType::class, [
                "class" => Promotions::class,
                "placeholder" => "Choose promotion",
                "constraints" => [
                    new NotBlank()
                ]
            ])
            ->add("category", EntityType::class, [
                "class" => Category::class,
                "label" => "Products category",
                "placeholder" => "Choose category",
                "constraints" => [
                    new NotBlank()
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}