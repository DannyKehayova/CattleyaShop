<?php
namespace OnlineShop\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use OnlineShop\Entity\Promotions;
class PromotionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("promotion", EntityType::class, [
                "class" => Promotions::class,
                "placeholder" => "Select promotion",
                "constraints" => [
                    new NotBlank()
                ]
            ]);
    }
}