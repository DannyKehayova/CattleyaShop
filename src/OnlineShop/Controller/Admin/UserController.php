<?php
namespace OnlineShop\Controller\Admin;
use OnlineShop\Entity\Role;
use OnlineShop\Form\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OnlineShop\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/users")
 * Class UserController
 * @package OnlineShop\Controller\Admin
 */
class UserController extends Controller
{
    /**
     * @Route("/",name="admin_users")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsers()
    {
        $users=$this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('admin/users/list.html.twig', ['users'=>$users]);
    }

    /**
     * @Route("/edit/{id}",name="admin_user_edit")
     *
     * @param id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editUser($id,Request $request)
    {

        $user=$this->getDoctrine()->getRepository(User::class)->find($id);

        if($user===null)
        {
            return $this->redirectToRoute("admin_users");


        }

        $originalPassword=$user->getPassword();


        $form=$this->createForm(UserEditType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $rolesRequest=$user->getRoles();
            $roleRepository=$this->getDoctrine()->getRepository(Role::class);
            $roles=[];

            foreach ($rolesRequest as $roleName)
            {
                $roles[]= $roleRepository->findOneBy(['name'=>$roleName]);
            }
            $user->setRoles($roles);

            if($user->getPassword())
            {
                $password=$this->get('security.password_encoder')
                    ->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
            }
            else
            {
                $user->setPassword($originalPassword);
            }

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("admin_users");
        }
        return $this->render('admin/user/edit.html.twig',['user'=>$user,
        'form'=>$form->createView()]);

    }

    /**
     * @Route("/delete/{id}",name="admin_user_delete")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteUser($id,Request $request)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);


        if($user===null)
        {
            return $this->redirectToRoute("admin_users");


        }


            $em=$this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute("admin_users");





    }


}