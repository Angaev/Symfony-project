<?php

namespace projectBundle\Controller;

use projectBundle\projectBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        return $this->render('projectBundle:Default:index.html.twig', [
           'books' => $books,
           'house' => $houses
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

        return $this->render('projectBundle:Default:index.html.twig', [
            'books' => $book,
            'house' => $houses,
            'comments' => $comment,
            'likes' => $likes
        ]);
    }

    public function editAction($id)
    {

        return $this->render('projectBundle:Default:index.html.twig', [
        ]);
    }
}
