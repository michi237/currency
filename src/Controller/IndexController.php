<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use GuzzleHttp\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @var CurrencyRepository
     */
    private CurrencyRepository $currencyRepository;


    public function __construct(CurrencyRepository $currencyRepository) {
        $this->currencyRepository = $currencyRepository;
    }


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

            $data = json_decode($request->getBody(), true)[0]['rates'];


            foreach ($data as $item) {
                $currency = $this->currencyRepository->getByCurrency($item['code']);
                    if($currency == null) {
                        $currency = new currency();
                        $currency->setName($item['currency']);
                        $currency->setCurrencyCode($item['code']);
                        $currency->setExchangeRate((float)$item['mid']);
                        $currency->setUploadedAt(new \DateTime());


                    } else {
                        $currency->setExchangeRate((float)$item['mid']);
                        $currency->setUploadedAt(new \DateTime());
                    }
                $this->currencyRepository->save($currency, true);
            }
        } return new JsonResponse(['status' => true]);
    }
}
