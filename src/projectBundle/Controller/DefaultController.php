<?php

namespace projectBundle\Controller;

use projectBundle\Entity\Book;
use projectBundle\Entity\Comment;
use projectBundle\Entity\Like;
use projectBundle\Entity\User;
use projectBundle\Froms\BookAddImgForm;
use projectBundle\Froms\CommentForm;
use projectBundle\Froms\UserTypeForm;
use projectBundle\Entity\PublishingHouse;
use projectBundle\Froms\AddHouseForm;
use projectBundle\Froms\BookDeleteForm;
use projectBundle\Froms\BookEditForm;
use projectBundle\Froms\BookForm;
use projectBundle\Froms\RenameHouseForm;
use Symfony\Component\HttpFoundation\File\File;
use projectBundle\projectBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

class DefaultController extends Controller
{
    public function deleteCommentAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user == null)
        {
            return $this->redirectToRoute('book_list');
        }
        $idComment = $request->get('idComment');
        $em = $this->getDoctrine()->getManager();
        $findComment = $em->getRepository('projectBundle:Comment')->find($idComment);
        if ($findComment == null)
        {
            return $this->redirectToRoute('book_list');
        }
        /** @var Book $book */
        $book = $findComment->getBook();
        /** @var User $authorComment */
        $authorComment = $findComment->getUser();
        if (($authorComment->getId() == $user->getId()) or ($user->getRole() == "ROLE_ADMIN"))
        {
            $em->remove($findComment);
            $em->flush();
            return $this->redirectToRoute('book_view', ['id' => $book->getId()]);
        }
        else
            return $this->redirectToRoute('book_list');
    }

    public function likeAction(Request $request)
    {
        $user = $this->getUser();
        $bookId = $request->get('book_id');
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('projectBundle:Book')->find($bookId);
        $findLike = $em->getRepository('projectBundle:Like')->findOneBy(array('user' => $user, 'book' => $book));
        //если лайк уже поставлен, то его надо удалить
        if ($findLike)
        {
            $em->remove($findLike);
        }
        else
        {
            //поставить like для указанной книги от пользователя
            $like = new Like();
            $like->setUser($user);
            $like->setBook($book);
            $em->persist($like);
        }
        $em->flush();
        $likes = $book->getLike();
        $likeCount = count($likes);
        return new JsonResponse($likeCount);
    }

    public function registerAction(Request $request)
    {
        if (($this->getUser()))
        {
            return $this->redirectToRoute('book_list');
        }
        $user = new User();
        $form = $this->createForm(UserTypeForm::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            // Encode the new users password
            $encoder = $this->get('security.password_encoder');
            $password = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // Set their role
            $user->setRole('ROLE_USER');

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('projectBundle:security:register.html.twig', [
            'form' => $form->createView(),
            'titleText' => 'Регистрация нового пользователя'
        ]);
    }

}

