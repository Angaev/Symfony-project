<?php

namespace projectBundle\Controller;

use projectBundle\Entity\book;
use projectBundle\Entity\comment;
use projectBundle\Entity\like;
use projectBundle\Entity\user;
use projectBundle\Froms\BookAddImgForm;
use projectBundle\Froms\CommentForm;
use projectBundle\Froms\UserTypeForm;
use projectBundle\Entity\publishing_house;
use projectBundle\Froms\addHouseForm;
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
        /** @var user $user */
//        var_dump($request);
//        die();
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute('book_list');
        }
        $idComment = $request->get('idComment');
        $em = $this->getDoctrine()->getManager();
        $findComment = $em->getRepository('projectBundle:comment')->find($idComment);
        if ($findComment == null) {
            return $this->redirectToRoute('book_list');
        }

        /** @var book $book */
        $book = $findComment->getBook();
        /** @var user $authtorComment */
        $authtorComment = $findComment->getUser();

        if (($authtorComment->getId() == $user->getId()) or ($user->getRole() == "ROLE_ADMIN"))
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
        $repo = $em->getRepository('projectBundle:book');
        $book = $repo->find($bookId);

        $likeRepo = $em->getRepository('projectBundle:like');
        $findLike = $likeRepo->findOneBy(array('user' => $user, 'book' => $book));

        //если лайк уже поставлен, то его надо удалить
        if ($findLike)
        {
            $em->remove($findLike);
        }
        else
        {
            //поставить like для указанной книги от пользователя
            $like = new like();
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
        if (!($this->isAnonymousUser($this->getUser())))
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

    /**
     * @return bool
     */
    private function isAnonymousUser($user)
    {
        if ($user != null)
        {
            return false;
        }
        return true;
    }
}

