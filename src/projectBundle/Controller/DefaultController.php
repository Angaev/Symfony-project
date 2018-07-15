<?php

namespace projectBundle\Controller;

use projectBundle\Entity\book;
use projectBundle\Entity\like;
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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="/")
     */
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
            'likeBtn' => 'free',
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

    public function likeAction(Request $request)
    {
        $bookId = $request->get('book_id');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:book');
        $book = $repo->find($bookId);

        $user = $this->getUser();
        //поставить like для указанной книги от пользователя
        $like = new like();
        $like->setUser($user);
        $like->setBook($book);


        $em->persist($like);
        $em->flush();

        $likes = $book->getLike();
        $likeCount = count($likes);
        return new JsonResponse($likeCount);
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

        $form = $this->createForm(BookAddImgForm::class, $book, [
            'data_class' => 'projectBundle\Entity\book'
        ]);
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
//            $book->setImage(
//                new File($this->getParameter('covers_directory') . "/" . $book->getImage())
//            );

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

    public function getTop50Action()
    {
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:book');
        $books = $bookRepo->getTop50();

        $houseRepo = $this->getDoctrine()->getRepository('projectBundle:publishing_house');
        $houses = $houseRepo->findAll();

        $commentRepo = $this->getDoctrine()->getRepository('projectBundle:comment');
        $comment = $commentRepo->findAll();

        return $this->render('projectBundle:Default:books.html.twig', [
            'books' => $books,
            'house' => $houses,
            'titleText' => 'Топ 50 книг',
            'pageDescription' => 'Топ 50 книг'
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
        $houses = $repo->findAll();

        if (!$houses)
        {
            return $this->redirectToRoute('book_list');
        }

        $form = $this->createForm(RenameHouseForm::class, $houses, [
            'data_class' => null
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $requestData = $request->get('rename_house_form');
            $house = $repo->find($requestData['publishing_house']);
            $house->setName($requestData['name']);
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

    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserTypeForm::class, $user);
//        var_dump($user);
//        die();
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
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() уменьшает схожесть имён файлов, сгенерированных
        // uniqid(), которые основанный на временных отметках
        return md5(uniqid());
    }
}
