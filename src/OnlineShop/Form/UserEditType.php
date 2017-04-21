<?php
/**
 * Created by PhpStorm.
 * User: Danny
 * Date: 18.4.2017 г.
 * Time: 18:17
 */

namespace OnlineShop\Form;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder

            ->add('roles', ChoiceType::class, array(
                'choices' => [
                    'Admin' => "ROLE_ADMIN",
                    'User' => "ROLE_USER",
                    'Editor' => "ROLE_EDITOR"
                ],
                'expanded' => true,
                'multiple' => true
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'OnlineShop\Entity\User'));
    }


}