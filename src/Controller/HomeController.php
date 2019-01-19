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
    private $results_per_page = 6;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $manager = $this->getDoctrine()->getRepository(Trick::class);

        // find out the number of results stored in database
        $number_of_tricks = $manager->getNumberOfTricks();

        // determine number of total pages available
        $number_of_pages = ceil($number_of_tricks / $this->results_per_page);

        // retrieve selected results from database and display them on page
        $tricks = $manager->getTricksPagination(1, $this->results_per_page);

        return $this->render('homepage/index.html.twig', [
            'tricks' => $tricks,
            'current_page' => 1,
            'total_page' => $number_of_pages,
        ]);
    }

    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/ajax-pagination/{page}", name="pagination", methods={"POST"})
     */
    public function pagination($page)
    {
        $manager = $this->getDoctrine()->getRepository(Trick::class);

        // find out the number of results stored in database
        $number_of_tricks = $manager->getNumberOfTricks();

        // determine number of total pages available
        $number_of_pages = ceil($number_of_tricks / $this->results_per_page);

        // Minimum 1 page
        if (0 == $number_of_pages) {
            $number_of_pages = 1;
        }

        // determine the sql LIMIT starting number for the results on the displaying page
        $this_page_first_result = ($page - 1) * $this->results_per_page;

        // retrieve selected results from database and display them on page
        $tricks = $manager->getTricksPagination($this_page_first_result, $this->results_per_page);

        return $this->render('homepage/_partials/content.html.twig', [
            'tricks' => $tricks,
            'current_page' => $page,
            'total_page' => $number_of_pages,
        ]);
    }
}
