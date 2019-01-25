<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 18/01/2019
 * Time: 08:31.
 */

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // define how many results you want per page
    private $number_of_results = 6;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $manager = $this->getDoctrine()->getRepository(Trick::class);

        $tricks = $manager->getTricksPagination(0, $this->number_of_results);

        return $this->render('homepage/index.html.twig', [
            'tricks' => $tricks,
            'number_of_results' => $this->number_of_results,
        ]);
    }

    /**
     * @param $offset
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/more/{offset}/", name="loadPagination", methods={"POST"})
     */
    public function loadPagination($offset)
    {
        $manager = $this->getDoctrine()->getRepository(Trick::class);

        $tricks = $manager->getTricksPagination($offset, $this->number_of_results);

        return $this->render('homepage/_partials/ajax-content.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
