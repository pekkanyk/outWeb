<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReloadController extends AbstractController{
    private $client;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    public function getJsonFromVk(string $i):string
    {
        $response = $this->client->request(
                'GET',
                'https://web-api.service.verkkokauppa.com/search?context=customer_returns_page&pageNo='.$i
        );
        return $response->getContent();
    }
    /**
     *@Route("/reload")
     */
    public function reloadJson(): Response
    {
        $string = $this->getJsonFromVk('0');
        
        //return $this->redirectToRoute('homepage');
        return new Response($string);
    }
}
