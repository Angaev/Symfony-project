<?php

namespace projectBundle\Controller;

use projectBundle\Entity\book;
use projectBundle\Entity\publishing_house;
use projectBundle\Froms\addHouseForm;
use projectBundle\Froms\BookDeleteForm;
use projectBundle\Froms\BookEditForm;
use projectBundle\Froms\BookForm;
use projectBundle\Froms\RenameHouseForm;
use projectBundle\projectBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

class DefaultController extends Controller
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

    public  function viewAction($id)
    {
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:book');
        $book = $bookRepo->find($id);

        $houseRepo = $this->getDoctrine()->getRepository('projectBundle:publishing_house');
        $houses = $houseRepo->findAll();

        $commentRepo = $this->getDoctrine()->getRepository('projectBundle:comment');
        $comment = $commentRepo->findByBook($book);

        $likeRepo = $this->getDoctrine()->getRepository('projectBundle:like');
        $likes = $likeRepo->findByBook($book);

        $userRepo = $this->getDoctrine()->getRepository('projectBundle:user');
        $users = $userRepo->findAll();

        return $this->render('projectBundle:Default:book.html.twig', [
            'book' => $book,
            'house' => $houses,
            'comments' => $comment,
            'likes' => $likes,
            'titleText' => $book->getName(),
            'likeBtn' => 'off',
            'admin' => false
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
            'titleText' => 'Редактирование книги',
            'id' => $book->getId()
        ]);
    }

    public function deleteAction($id, Request $request)
    {
        //удаляет указаную книку без придупреждения

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


    public function addAction(Request $request)
    {
        $book = new book();
        $form = $this->createForm(BookForm::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            // $file сохраняет загруженный файл
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $book->getImage();

            $fileName = 'img/book/' . $this->generateUniqueFileName().'.'.$file->guessExtension();



            // перемещает файл в каталог, где хранятся обложки книг
            $file->move(
                $this->getParameter('covers_directory'),
                $fileName
            );

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

    public function searchAction($word)
    {
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:book');
        $books = $bookRepo->findByName($word);

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

    public function addHouseAction(Request $request)
    {
        $house = new publishing_house();
        $form = $this->createForm(addHouseForm::class, $house);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $em->persist($house);
            $em->flush();
            return $this->redirectToRoute('book_list');
        }
        return $this->render('projectBundle:Default:houseEditor.html.twig', [
            'form' => $form->createView(),
            'titleText' => 'Добавление нового издательства'
        ]);
    }

    public function renameHouseAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:publishing_house');
        $house = $repo->findAll();

        if (!$house)
        {
            return $this->redirectToRoute('book_list');
        }

        $form = $this->createForm(RenameHouseForm::class, $house, [
            'data_class' => null
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
//            var_dump($request);
//            die();
            $em = $this->getDoctrine()->getManager();
            $em->persist($house);
            $em->flush();
            return $this->redirectToRoute('book_list');
        }
        return $this->render('@project/Default/houseEditor.html.twig', [
            'form' => $form->createView(),
            'titleText' => 'Редактирование издательств'
        ]);
    }

    public function deleteHouseAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:publishing_house');
        $house = $repo->find($id);
        if (!$house)
        {
            return $this->redirectToRoute('book_list');
        }
        $form = $this->createForm(BookDeleteForm::class, null, [
            'delete_id' => $house->getId()
        ]);
        $form->handleRequest($request);

        $em->remove($house);
        $em->flush();
        return $this->redirectToRoute('book_list');
    }


    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() уменьшает схожесть имён файлов, сгенерированных
        // uniqid(), которые основанный на временных отметках
        return md5(uniqid());
    }
}
