<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.07.2018
 * Time: 21:35
 */

namespace projectBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

class BookController extends Controller
{
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
}