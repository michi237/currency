<?php

namespace App\Controller;

use GuzzleHttp\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/add', name: 'add_new', methods: 'GET')]
    public function add(Request $request) : JsonResponse
    {
        if ($request->isMethod('GET')) {
            $client = new Client;

            $request = $client->request('GET', 'http://api.nbp.pl/api/exchangerates/tables/a/?format=json');

            echo $request->getBody();

        }
    }
}
