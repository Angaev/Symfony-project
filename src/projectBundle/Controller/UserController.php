<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.07.2018
 * Time: 13:58
 */

namespace projectBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use projectBundle\Entity\user;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;


class UserController extends Controller
{
    public function userIndexAction()
    {
        $userRepo = $this->getDoctrine()->getRepository('projectBundle:user');
        $users = $userRepo->findAll();

        return $this->render('projectBundle:Default:users.html.twig', [
            'users' => $users,
            'titleText' => 'Редактирование пользователей'
        ]);
    }

    public function flipUserRoleAction(Request $request)
    {
        $userId = $request->get('id');

        $userRepo = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepo->find($userId);

        if ($user->getRole() == "ROLE_USER")
        {
            //set admin role
            $user->setRole('ROLE_ADMIN');
        }
        else
        {
            //set user role
            $user->setRole('ROLE_USER');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirect('user_edit');
    }

    public function flipBanAction(Request $request)
    {
        $userId = $request->get('id');

        $userRepo = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepo->find($userId);

        if ($user->getRole() != "ROLE_BAN")
        {
            //set ban
            $user->setRole('ROLE_BAN');
        }
        else
        {
            //set set user
            $user->setRole('ROLE_USER');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirect('user_edit');
    }

    public function editProfileAction(Request $request)
    {

        /** @var user $user */
        $user = $this->getUser();

        if ($user == null)
        {
            return $this->redirectToRoute('login');
        }

        $message = $request->get('message');
        return $this->render('projectBundle:Default:edit_profile.html.twig', [
            'user' => $user,
            'titleText' => 'Редактирование профиля',
            'message' => $message
        ]);
    }

    public function changePasswordAction(Request $request)
    {
        /** @var user $user */
        $user = $this->getUser();

        if ($user == null)
        {
            return $this->redirectToRoute('login');
        }

        $oldPass = $request->get('oldPass');
        $pass1 = $request->get('pass1');
        $pass2 = $request->get('pass2');

        if ($pass1 != $pass2)
        {
            return $this->redirectToRoute('edit_profile', ['message' => 'Не совпадают пароли']);
        }

        $encoder = $this->get('security.password_encoder');

        if (!($encoder->isPasswordValid($user, $oldPass)))
        {
            return $this->redirectToRoute('edit_profile', ['message' => 'Не правильный пароль']);
        }

        $newPassword = $encoder->encodePassword($user, $pass1);
        $user->setPassword($newPassword);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('edit_profile', ['message' => 'Пароль изменен']);
    }

    public function changeProfileDataAction(Request $request)
    {
        /** @var user $user */
        $user = $this->getUser();

        if ($user == null)
        {
            return $this->redirectToRoute('login');
        }

        $newName = $request->get('name');
        $newEmail = $request->get('email');

        $user->setName($newName);
        $user->setEmail($newEmail);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('edit_profile', ['message' => 'Данные обновлены']);
    }

    public function addAvatarAction(Request $request)
    {
        /** @var user $user */
        $user = $this->getUser();
        if ($user == null)
        {
            die();
        }
        if($user->getAvatar() != null)
        {
            $user->setAvatar(new File($user->getAvatar()));
        }

        $em = $this->getDoctrine()->getManager();

        $request->get('form_data');
        $file = $request->files->get('file');

        $fileName = $this->get('app.avatar_uploader')->upload($file);
        $user->setAvatar($fileName);
        $em->persist($user);
        $em->flush();

        return new JsonResponse($user->getAvatar());
    }

    public function deleteAvatarAction(Request $request)
    {
        /** @var user $user */
        $user = $this->getUser();
        if ($user == null)
        {
            die();
        }

        $user->setAvatar(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse('was delete');
    }

    public function areaViewAction()
    {
        /** @var user $user */
        $user = $this->getUser();
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:book');
        $likes = $bookRepo->getLastUserBook($user->getId(), 6);
        $commentRepo = $this->getDoctrine()->getRepository('projectBundle:comment');
        $lastComment = $commentRepo->getLastUserComment($user);

        return $this->render('projectBundle:Default:area.html.twig', [
            'user' => $user,
            'titleText' => 'Личный кабинет',
            'likes' => $likes,
            'lastComment' => $lastComment
        ]);
    }
}
