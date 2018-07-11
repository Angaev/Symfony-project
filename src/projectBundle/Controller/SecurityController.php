<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.07.2018
 * Time: 21:17
 */

namespace projectBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login");
     */
    public function loginAction(Request $request)
    {
        $authUtils = $this->get('security.authentication_utils');
        // получить ошибку входа, если она есть
        $error = $authUtils->getLastAuthenticationError();

        // последнее имя пользователя, введенное пользователем
        $lastUsername = $authUtils->getLastUsername();

//        var_dump($error);
//        var_dump($lastUsername);
//        die();
        return $this->render('projectBundle:security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'titleText' => 'Представьтесь'
        ));
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }
}