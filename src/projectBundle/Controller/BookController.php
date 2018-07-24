<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.07.2018
 * Time: 21:35
 */

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


class BookController extends Controller
{
    public function indexAction()
    {
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:book');
        $books = $bookRepo->findAll();

        $houseRepo = $this->getDoctrine()->getRepository('projectBundle:publishing_house');
        $houses = $houseRepo->findAll();

        $commentRepo = $this->getDoctrine()->getRepository('projectBundle:comment');
        $comment = $commentRepo->findAll();

        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
            'house' => $houses,
            'titleText' => 'Все книги',
            'pageDescription' => 'Все книги'
        ]);
    }

    public function addAction(Request $request)
    {
        $book = new book();
        $form = $this->createForm(BookForm::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $book->getImage();
            $fileName = $this->get('app.cover_uploader')->upload($file);

            $book->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book_list');
        }
        return $this->render('projectBundle:Default:add.html.twig', [
            'form' => $form->createView(),
            'titleText' => 'Добавление новой книги'
        ]);
    }

    public  function viewAction($id, Request $request)
    {
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:book');
        $book = $bookRepo->find($id);

        $houseRepo = $this->getDoctrine()->getRepository('projectBundle:publishing_house');
        $houses = $houseRepo->findAll();

        $commentRepo = $this->getDoctrine()->getRepository('projectBundle:comment');
        $comment = $commentRepo->findByBook($book);

        $likeRepo = $this->getDoctrine()->getRepository('projectBundle:like');
        $likes = $likeRepo->findByBook($book);

        $user =  $this->getUser();
        $findLike = $likeRepo->findOneBy(array(
            'user' => $user,
            'book' => $book
        ));

        if ($user != null)
        {
            $isAdmin =  ($user->getRole() == 'ROLE_ADMIN') ? true : false;
        }
        else
        {
            $isAdmin = false;
        }

        $commentForm = $this->createForm(CommentForm::class);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted())
        {
            /** @var comment $newComment */
            $newComment = $commentForm->getData();
            $newComment->setUser($user);
            $newComment->setBook($book);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($newComment);
            $em->flush();
            return $this->redirectToRoute('book_view', ['id' => $book->getId()]);
        }

        $userRepo = $this->getDoctrine()->getRepository('projectBundle:user');
        $users = $userRepo->findAll();

        return $this->render('projectBundle:Default:book.html.twig', [
            'book' => $book,
            'house' => $houses,
            'comments' => $comment,
            'likes' => $likes,
            'titleText' => $book->getName(),
            'likeBtn' => $findLike ? 'lock' : 'free',
            'admin' => $isAdmin,
            'user' => $user,
            'comment_form' => $commentForm->createView()
        ]);
    }

    public function getTop50Action()
    {
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:book');
        $books = $bookRepo->getAllBooks();

        $houseRepo = $this->getDoctrine()->getRepository('projectBundle:publishing_house');
        $houses = $houseRepo->findAll();

        $commentRepo = $this->getDoctrine()->getRepository('projectBundle:comment');
        $comment = $commentRepo->findAll();

        var_dump($books);
        die();
        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
            'house' => $houses,
            'titleText' => 'Топ 50 книг',
            'pageDescription' => 'Топ 50 книг'
        ]);
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:book');
        $book = $repo->find($id);

        if (!$book)
        {
            return $this->redirectToRoute('book_list');
        }

        if($book->getImage() != null)
        {
            $book->setImage(new File($book->getImage()));
        }
        $form_cover = $this->createForm(BookAddImgForm::class, $book, [
            'data_class' => 'projectBundle\Entity\book'
        ]);
        $form_cover->handleRequest($request);

        if($form_cover->isSubmitted())
        {
            $file = $book->getImage();
            $fileName = $this->get('app.cover_uploader')->upload($file);
            $book->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book_view', ['id' => $id]);
        }

        $form = $this->createForm(BookEditForm::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book_view', [ 'id' => $book->getId()]);
        }

        return $this->render('@project/Default/edit_book.html.twig', [
            'form' => $form->createView(),
            'form_cover' => $form_cover->createView(),
            'titleText' => 'Редактирование книги',
            'id' => $book->getId()
        ]);
    }

    public function deleteAction($id, Request $request)
    {
        //удаляет указаную книгу без придупреждения

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:book');
        $book = $repo->find($id);
        if (!$book)
        {
            return $this->redirectToRoute('book_list');
        }
        $form = $this->createForm(BookDeleteForm::class, null, [
            'delete_id' => $book->getId()
        ]);
        $form->handleRequest($request);

        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('book_list');

    }

    public function bookAddImgAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:book');
        $book = $repo->find($id);
        if (!$book)
        {
            return $this->redirectToRoute('book_list');
        }
        if($book->getImage() != null)
        {
            $book->setImage(new File($book->getImage()));
        }
        $form = $this->createForm(BookAddImgForm::class, $book, [
            'data_class' => 'projectBundle\Entity\book'
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $book->getImage();
            $fileName = $this->get('app.cover_uploader')->upload($file);
            $book->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book_list');
        }
        return $this->render('projectBundle:Default:add_book_img.html.twig', [
            'form' => $form->createView(),
            'titleText' => 'Добавление новой книги',
            'id' => $id
        ]);
    }

    public function deleteCoverAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:book');
        $book = $repo->find($id);
        $book->setImage(null);
        $em->persist($book);
        $em->flush();

        return $this->redirectToRoute('book_view', array(
            'id' => $id
        ));
    }

    public function searchAction(Request $request)
    {
        $word = $request->query->get('name');
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:book');
        $books = $bookRepo->searchByWord($word);

        $houseRepo = $this->getDoctrine()->getRepository('projectBundle:publishing_house');
        $houses = $houseRepo->findAll();

        $commentRepo = $this->getDoctrine()->getRepository('projectBundle:comment');
        $comment = $commentRepo->findAll();

        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
            'house' => $houses,
            'titleText' => 'Книги по запросу ' . $word,
            'pageDescription' => 'Поиск ' . $word
        ]);
    }

    public function likedBooksViewAction()
    {
        /** @var user $user */
        $user = $this->getUser();
        if ($user == null)
        {
            return $this->redirectToRoute('book_list');
        }

        $books = $this->getDoctrine()->getRepository('projectBundle:book')->getLikedBooks($user);
        $houses = $this->getDoctrine()->getRepository('projectBundle:publishing_house')->findAll();

        var_dump($books);
        die();
        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
            'house' => $houses,
            'titleText' => 'Все книги',
            'pageDescription' => 'Все книги'
        ]);
    }

    public function allCommentsAction()
    {
        /** @var user $user */
        $user = $this->getUser();
        if ($user == null)
        {
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('projectBundle:comment')->findBy(['user' => $user->getId()]);
        $books = $em->getRepository('projectBundle:book')->findAll();

        return $this->render('projectBundle:Default:all_comments.html.twig', [
            'comments' => $comments,
            'titleText' => 'Все комментарии',
            'pageDescription' => 'Все комментарии',
            'user' => $user
        ]);
    }
}