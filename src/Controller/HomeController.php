<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 18/01/2019
 * Time: 08:31
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('homepage/index.html.twig');
    }
}
