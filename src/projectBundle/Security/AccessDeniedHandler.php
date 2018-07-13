<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.07.2018
 * Time: 21:41
 */

namespace projectBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
//        echo('shit');
//        die();
//        return render('', [
//            'titleText' => 'Редактирование издательств'
//        ]);
        $content = '';
        return new Response($content, 403);
    }
}