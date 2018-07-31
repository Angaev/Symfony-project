<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.07.2018
 * Time: 19:53
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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;


class ApiController extends Controller
{
    public function indexAction()
    {
        $bookRepo = $this->getDoctrine()->getRepository('projectBundle:Book');
        $books = $bookRepo->getAllBooks();
//        var_dump($books);
//        die();
        return new JsonResponse($books);
    }
}