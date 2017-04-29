<?php
/**
 * Created by PhpStorm.
 * User: Danny
 * Date: 29.4.2017 г.
 * Time: 21:00
 */

namespace OnlineShop\Form;

use OnlineShop\Entity\Orders;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;


class ProductsOrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    Orders::STATUS_PROCESSING => Orders::STATUS_PROCESSING,
                    Orders::STATUS_CANCELED => Orders::STATUS_CANCELED,
                    Orders::STATUS_COMPLETE => Orders::STATUS_COMPLETE,
                ],
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'OnlineShop\Entity\Orders'
        ]);
    }
}