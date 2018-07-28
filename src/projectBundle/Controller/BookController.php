<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.07.2018
 * Time: 21:35
 */

namespace projectBundle\Controller;


use projectBundle\Entity\Book;
use projectBundle\Entity\Comment;
use projectBundle\Entity\user;
use projectBundle\Froms\BookAddImgForm;
use projectBundle\Froms\CommentForm;
use projectBundle\Froms\BookDeleteForm;
use projectBundle\Froms\BookEditForm;
use projectBundle\Froms\BookForm;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;


class BookController extends Controller
{
    public function indexAction()
    {
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:Book');
        $books = $bookRepo->getAllBooks();

        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
            'titleText' => 'Все книги',
            'pageDescription' => 'Все книги'
        ]);
    }

    public function addAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookForm::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $book->getImage();
            $fileName = $this->get('app.cover_uploader')->upload($file);
            $book->setImage($fileName);
            $this->updateEntity($book);
            return $this->redirectToRoute('book_list');
        }
        return $this->render('projectBundle:Default:add.html.twig', [
            'form' => $form->createView(),
            'titleText' => 'Добавление новой книги'
        ]);
    }

    public function viewAction($id, Request $request)
    {
        $book = $this->getDoctrine()->getRepository('projectBundle:Book')->find($id);
        $comment = $this->getDoctrine()->getRepository('projectBundle:Comment')->findByBook($book);
        $user =  $this->getUser();
        $findLike = $this->getDoctrine()->getRepository('projectBundle:Like')->findOneBy([
            'user' => $user,
            'book' => $book
        ]);

        $isAdmin = false;
        if ($user != null)
        {
            $isAdmin =  ($user->getRole() == 'ROLE_ADMIN') ? true : false;
        }

        $commentForm = $this->createForm(CommentForm::class);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted())
        {
            /** @var Comment $newComment */
            $newComment = $commentForm->getData();
            $newComment->setUser($user);
            $newComment->setBook($book);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($newComment);
            $em->flush();
            return $this->redirectToRoute('book_view', ['id' => $book->getId()]);
        }
        return $this->render('projectBundle:Default:book.html.twig', [
            'book' => $book,
            'comments' => $comment,
            'titleText' => $book->getName(),
            'likeBtn' => $findLike ? 'lock' : 'free',
            'admin' => $isAdmin,
            'user' => $user,
            'comment_form' => $commentForm->createView()
        ]);
    }

    public function getTop50Action()
    {
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:Book');
        $books = $bookRepo->getTop50();

        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
            'titleText' => 'Топ 50 книг',
            'pageDescription' => 'Топ 50 книг'
        ]);
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:Book');
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
            'data_class' => 'projectBundle\Entity\Book'
        ]);
        $form_cover->handleRequest($request);

        if($form_cover->isSubmitted())
        {
            $file = $book->getImage();
            $fileName = $this->get('app.cover_uploader')->upload($file);
            $book->setImage($fileName);

            $this->updateEntity($book);
            return $this->redirectToRoute('book_view', ['id' => $id]);
        }

        $form = $this->createForm(BookEditForm::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $this->updateEntity($book);
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
        $repo = $em->getRepository('projectBundle:Book');
        $book = $repo->find($id);
        if (!$book)
        {
            return $this->redirectToRoute('book_list');
        }
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('book_list');
    }

    public function bookAddImgAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:Book');
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
            'data_class' => 'projectBundle\Entity\Book'
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
        $repo = $em->getRepository('projectBundle:Book');
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
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:Book');
        $books = $bookRepo->searchByWord($word);

        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
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

        $books = $this->getDoctrine()->getRepository('projectBundle:Book')->getLikedBooks($user->getId());

        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
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

        $comments = $this->getDoctrine()->getManager()->getRepository('projectBundle:Comment')->findBy(['user' => $user->getId()]);

        return $this->render('projectBundle:Default:all_comments.html.twig', [
            'comments' => $comments,
            'titleText' => 'Все комментарии',
            'pageDescription' => 'Все комментарии',
            'user' => $user
        ]);
    }

    private function updateEntity($book)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
    }
}