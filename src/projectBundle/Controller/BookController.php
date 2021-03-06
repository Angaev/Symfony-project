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
use projectBundle\Entity\User;
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
            $this->updateObject($book);
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
        $commentForm = $this->createForm(CommentForm::class);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted())
        {
            /** @var Comment $newComment */
            $newComment = $commentForm->getData();
            $newComment->setUser($user);
            $newComment->setBook($book);
            $this->updateObject($newComment);
            return $this->redirectToRoute('book_view', ['id' => $book->getId()]);
        }
        return $this->render('projectBundle:Default:book.html.twig', [
            'book' => $book,
            'comments' => $comment,
            'titleText' => $book->getName(),
            'likeBtn' => $findLike ? 'lock' : 'free',
            'admin' => $this->isAdmin($user),
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
        $book = $em->getRepository('projectBundle:Book')->find($id);
        if (!$book)
        {
            return $this->redirectToRoute('book_list');
        }
        $this->prepareBookImage($book);
        $formCover = $this->createForm(BookAddImgForm::class, $book, ['data_class' => 'projectBundle\Entity\Book']);
        $formCover->handleRequest($request);
        if($formCover->isSubmitted())
        {
            $file = $book->getImage();
            $book->setImage($this->get('app.cover_uploader')->upload($file));
            $this->updateObject($book);
            return $this->redirectToRoute('book_view', ['id' => $id]);
        }
        $form = $this->createForm(BookEditForm::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $this->updateObject($book);
            return $this->redirectToRoute('book_view', [ 'id' => $book->getId()]);
        }
        return $this->render('@project/Default/edit_book.html.twig', [
            'form' => $form->createView(),
            'form_cover' => $formCover->createView(),
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
//        $books = $bookRepo->getAllBooks();
        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
            'titleText' => 'Книги по запросу ' . $word,
            'pageDescription' => 'Поиск ' . $word
        ]);
    }

    public function likedBooksViewAction()
    {
        /** @var User $user */
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
        /** @var User $user */
        $user = $this->getUser();
        if ($user == null)
        {
            return $this->redirectToRoute('login');
        }

        $comments = $this->getDoctrine()->getManager()->getRepository('projectBundle:Comment')->findBy([
            'user' => $user->getId()
        ]);

        return $this->render('projectBundle:Default:all_comments.html.twig', [
            'comments' => $comments,
            'titleText' => 'Все комментарии',
            'pageDescription' => 'Все комментарии',
            'user' => $user
        ]);
    }

    private function updateObject($object)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();
    }

    private function isAdmin($user)
    {
        return ($user && $user->getRole() == 'ROLE_ADMIN') ? true : false;
    }

    private function prepareBookImage(&$book)
    {
        if($book->getImage() != null)
        {
            $book->setImage(new File($book->getImage()));
        }
    }

}