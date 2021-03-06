<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.07.2018
 * Time: 23:46
 */

namespace projectBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;


class HouseController extends Controller
{
    public function housesAction()
    {
        $houses = $this->getDoctrine()->getRepository('projectBundle:PublishingHouse')->findAll();
        return $this->render('projectBundle:Default:publishing_houses.html.twig', [
            'publishing_houses' => $houses,
            'titleText' => 'Редактирование издательств',
            'message' => ''
        ]);
    }

    public function addHouseAction(Request $request)
    {
        /** @var PublishingHouse $house */
        $house = new PublishingHouse();

        $data = $request->get('newHouse');
        $house->setName($data);
        $em = $this->getDoctrine()->getManager();

        $em->persist($house);
        $em->flush();

        return new JsonResponse(array(
            'name' => $house->getName(),
            'id' => $house->getId())
        );
    }

    public function deleteHouseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:PublishingHouse');

        $id = $request->get('id');
        $house = $repo->find($id);

        $em->remove($house);
        $em->flush();
        return new JsonResponse($id);
    }

    public function renameHouseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:PublishingHouse');
        $houseId = $request->get('id');
        $newName = $request->get('newName');
        $house = $repo->find($houseId);

        $house->setName($newName);

        $em->persist($house);
        $em->flush();

        return new JsonResponse(array(
           'id' => $house->getId(),
           'name' => $house->getName()
        ));

    }
}