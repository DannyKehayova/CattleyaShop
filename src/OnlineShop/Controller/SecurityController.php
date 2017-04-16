<?php

namespace OnlineShop\Controller;

use OnlineShop\Entity\Role;
use OnlineShop\Entity\User;
use OnlineShop\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{

    /**
     * @Route ("/login", name="security_login")
     * @Template()
     *
     */
    public function loginAction(Request $request)
    {
        $auth_utils=$this->get('security.authentication_utils');
        $error=$auth_utils->getLastAuthenticationError();
        $last_username=$auth_utils->getLastUsername();
         return [
             'error' => $error,
             'last_username' =>$last_username
         ];
    }


    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route ("/register",name="user_register")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function registerAction(Request $request)
    {
     $form=$this->createForm(UserType::class);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            /** @var User $user */
          $user=$form->getData();
          $encrypter=$this->get('security.password_encoder');
          $user->setPassword($encrypter->encodePassword($user,$user->getPasswordRaw()));
          $manager=$this->getDoctrine()->getManager();
            $roleRepository = $this->getDoctrine()->getRepository(Role::class);
            $userRole = $roleRepository->findOneBy(['name' => 'ROLE_USER']);

            $user->addRole($userRole);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');

        }

        return [
            'form'=>$form->createView()
        ];
    }
}
