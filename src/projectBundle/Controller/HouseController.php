<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.07.2018
 * Time: 23:46
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


class HouseController extends Controller
{
    public function housesAction()
    {
        $houses = $this->getDoctrine()->getRepository('projectBundle:publishing_house')->findAll();
        return $this->render('projectBundle:Default:publishing_houses.html.twig', [
            'publishing_houses' => $houses,
            'titleText' => 'Редактирование издательств',
            'message' => ''
        ]);
    }

    public function addHouseAction(Request $request)
    {
        /** @var publishing_house $house */
        $house = new publishing_house();

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
        $repo = $em->getRepository('projectBundle:publishing_house');

        $id = $request->get('id');
        $house = $repo->find($id);
        if (!$house)
        {
            die();
        }

        $em->remove($house);
        $em->flush();
        return new JsonResponse($id);
    }

    public function renameHouseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('projectBundle:publishing_house');
        $houseId = $request->get('id');
        $newName = $request->get('newName');
        $house = $repo->find($houseId);

        if (!$house)
        {
            die();
        }

        $house->setName($newName);

        $em->persist($house);
        $em->flush();

        return new JsonResponse(array(
           'id' => $house->getId(),
           'name' => $house->getName()
        ));

    }
}