<?php

namespace OnlineShop\Form;

use OnlineShop\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username',TextType::class,[
                    'required'=>false,
        ])
                ->add('email',EmailType::class,[
                    'required'=>false,
                ])
                ->add('password_raw',RepeatedType::class,[
                      'type'=>PasswordType::class,
                      'first_options' => [
                          'required'=>false,
                          'label' =>  'Password',

                      ],
                      'second_options' => [
                          'required'=>false,
                          'label' => 'Repeat Password'
                      ]
                ])
            ->add('firstName',TextType::class)
            ->add('secondName',TextType::class)
            ->add('phone')
            ->add('address',TextType::class)
            ->add('city',TextType::class)
            ->add('submit',SubmitType::class);

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'onlineshop_user';
    }


}
