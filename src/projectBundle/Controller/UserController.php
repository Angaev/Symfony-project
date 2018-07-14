<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.07.2018
 * Time: 13:58
 */

namespace projectBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use projectBundle\Entity\book;
use projectBundle\Entity\user;
use projectBundle\Froms\BookAddImgForm;
use projectBundle\Froms\UserTypeForm;
use projectBundle\Entity\publishing_house;
use projectBundle\Froms\addHouseForm;
use projectBundle\Froms\BookDeleteForm;
use projectBundle\Froms\BookEditForm;
use projectBundle\Froms\BookForm;
use projectBundle\Froms\RenameHouseForm;
use projectBundle\projectBundle;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

class UserController extends Controller
{
    public function userIndexAction()
    {
        $userRepo = $this->getDoctrine()->getRepository('projectBundle:user');
        $users = $userRepo->findAll();

        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:book');
        $books = $bookRepo->findAll();

        $houseRepo = $this->getDoctrine()->getRepository('projectBundle:publishing_house');
        $houses = $houseRepo->findAll();

        $commentRepo = $this->getDoctrine()->getRepository('projectBundle:comment');
        $comment = $commentRepo->findAll();

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
}