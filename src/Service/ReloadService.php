<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\OutletTuote;

class ReloadService{
    private $client;
 
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    private function getJsonFromVk($i):string
    {
        $response = $this->client->request(
                'GET',
                'https://web-api.service.verkkokauppa.com/search?context=customer_returns_page&pageNo='.$i
        );
        return $response->getContent();
    }
    
    private function generateOutletTuoteFromTable($vkProduct): OutletTuote
    {
        $outletTuote = new OutletTuote();
        $outletTuote->setOutId($vkProduct["customerReturnsInfo"]["id"]);
        $outletTuote->setPid($vkProduct["customerReturnsInfo"]["pid"]);
        $outletTuote->setName($vkProduct["customerReturnsInfo"]["product_name"]);
        $outletTuote->setOutPrice($vkProduct["customerReturnsInfo"]["price_with_tax"]);
        $outletTuote->setPoistotuote($vkProduct["active"]);
        if ($vkProduct["active"] == 1){
            $outletTuote->setNorPrice($vkProduct["price"]["current"]);
        }
        else{
            $outletTuote->setNorPrice($vkProduct["price"]["original"]);
        }
        $outletTuote->setUpdated(false);
        $outletTuote->setDumppituote($vkProduct["isFireSale"]);
        $outletTuote->setWarranty($vkProduct["customerReturnsInfo"]["warranty"]);
        $outletTuote->setCondition($vkProduct["customerReturnsInfo"]["condition"]);
        $outletTuote->setDeleted(null);
        
        //$outletTuote->setPidLuotu(date('Y-m-d', strtotime($vkProduct["createdAt"])));
        /*$outletTuote->setPriceUpdatedDate($priceUpdatedDate);
        $outletTuote->setAlennus($alennus);
        $outletTuote->setKampanja($kampanja);
        $outletTuote->setKamploppuu($kamploppuu);
        $outletTuote->setFirstSeen($firstSeen);
        $outletTuote->setVarastossa($varastossa);
        $outletTuote->setOnVarasto($onVarasto);
        $outletTuote->setKoko($koko);
        
        */
        return $outletTuote;
    }
    
    
    public function reloadProducts()
    {
        
        $jsonStr = $this->getJsonFromVk(0);
        $json = json_decode($jsonStr,true);
        $numPages = $json['numPages'];
        
        //for ($i=0;$i<$numPages;$i++){
        for ($i=0;$i<1;$i++){
            $jsonStr = $this->getJsonFromVk($i);
            $json = json_decode($jsonStr,true);
            $vkProducts = $json['products'];
            $outProducts[] = new \ArrayObject();
            
            for ($j=0;$j<1;$j++){
                $outletTuote = $this->generateOutletTuoteFromTable($vkProducts[$j]);
                array_push($outProducts,$outletTuote);
            }
        }
        
        //return $this->redirectToRou1te('homepage');
        //return new Response(print_r($vkProducts[0]["customerReturnsInfo"]));
        //return new Response(print_r($outProducts));
        return $outProducts;
    }
}
