<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\OutletTuote;
use App\Entity\UpdateStats;
use App\Entity\PidInfo;
use Doctrine\ORM\EntityManagerInterface;


class ReloadService{
    private $client;
    private $entityManager;
        
    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }
    
    private function reloadProducts()
    {
        
        $jsonStr = $this->getJsonFromVk(0);
        $json = json_decode($jsonStr,true);
        $numPages = $json['numPages'];
        $outProducts = [];
        for ($i=0;$i<$numPages;$i++){
            $jsonStr = $this->getJsonFromVk($i);
            $json = json_decode($jsonStr,true);
            $vkProducts = $json['products'];
            
            for ($j=0;$j<count($vkProducts);$j++){
                $outletTuote = $this->generateOutletTuoteFromTable($vkProducts[$j]);
                array_push($outProducts,$outletTuote);
            }
        }
 
        return $outProducts;
    }
    
    public function updateDb(){
        set_time_limit(0);
        $newOutProducts = $this->reloadProducts();
        $this->entityManager->getConnection()->beginTransaction();
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $sum = 0;
        try {
            $db->setAllActiveDeleted();
            for ($i=0;$i<count($newOutProducts);$i++){
                $sum=$sum+$newOutProducts[$i]->getOutPrice();
                $dbOutTuote = $db->find($newOutProducts[$i]->getOutId());
                if ($dbOutTuote == null){
                    $this->entityManager->persist($newOutProducts[$i]);
                    $this->entityManager->flush();
                }
                else{
                    if ( $dbOutTuote->getOutPrice() != $newOutProducts[$i]->getOutPrice()) { $dbOutTuote->setPriceUpdatedDate($newOutProducts[$i]->getPriceUpdatedDate()); }
                    $dbOutTuote->setName($newOutProducts[$i]->getName());
                    $dbOutTuote->setOutPrice($newOutProducts[$i]->getOutPrice());
                    $dbOutTuote->setNorPrice($newOutProducts[$i]->getNorPrice());
                    $dbOutTuote->setAlennus($newOutProducts[$i]->getAlennus());
                    $dbOutTuote->setDumppituote($newOutProducts[$i]->getDumppituote());
                    $dbOutTuote->setPoistotuote($newOutProducts[$i]->getPoistotuote());
                    $dbOutTuote->setWarranty($newOutProducts[$i]->getWarranty());
                    $dbOutTuote->setCondition($newOutProducts[$i]->getCondition());
                    $dbOutTuote->setDeleted(null);
                    $dbOutTuote->setKampanja($newOutProducts[$i]->getKampanja());
                    $dbOutTuote->setKamploppuu($newOutProducts[$i]->getKamploppuu());
                    $dbOutTuote->setOnVarasto($newOutProducts[$i]->getOnVarasto());
                    $dbOutTuote->setVarastossa($newOutProducts[$i]->getVarastossa());
                    $dbOutTuote->setKoko($newOutProducts[$i]->getKoko());
                    $this->entityManager->flush();
                }
            }
        $updatetime = new UpdateStats();
        $updatetime->setTimestamp(date_create('now', new \DateTimeZone('Europe/Helsinki')));
        $updatetime->setTotalItems(count($newOutProducts));
        $updatetime->setSum($sum);
        $this->entityManager->persist($updatetime);
        $this->entityManager->flush();
        $this->entityManager->getConnection()->commit();
        } catch (Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            throw $ex;
        }
        
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
        $pidDb = $this->entityManager->getRepository(PidInfo::class);
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
        if (array_key_exists("isFireSale", $vkProduct)){
            $outletTuote->setDumppituote($vkProduct["isFireSale"]);
        }
        else{
            $outletTuote->setDumppituote(false);
        }
        $outletTuote->setWarranty($vkProduct["customerReturnsInfo"]["warranty"]);
        $outletTuote->setCondition($vkProduct["customerReturnsInfo"]["condition"]);
        $outletTuote->setDeleted(null);
        $outletTuote->setFirstSeen($this->strDateToDate("today"));
        $outletTuote->setPidLuotu($this->strDateToDate($vkProduct["createdAt"]));
        $outletTuote->setPriceUpdatedDate($this->strDateToDate("today"));
        if ($outletTuote->getNorPrice()!=0){
            $alennus = 100.0*(1-($outletTuote->getOutPrice()/$outletTuote->getNorPrice()));
        }
        else { $alennus = 0;}
        $outletTuote->setAlennus($alennus);
        $outletTuote->setKampanja(false);
        $outletTuote->setKamploppuu(null);
        if (array_key_exists("discount", $vkProduct["price"])){
            if ($vkProduct["price"]["discount"]!=null){
                if ($vkProduct["price"]["discountAmount"]>0){
                    $outletTuote->setKampanja(true);
                    if ($vkProduct["price"]["discount"]["endAt"]!=null){
                        $outletTuote->setKamploppuu($this->strDateToDate($vkProduct["price"]["discount"]["endAt"]));
                    }
                    
                }
                
            }
            
        }
        if ($vkProduct["availability"]!=null){
            $outletTuote->setOnVarasto($vkProduct["availability"]["isPurchasable"]);
            if ($vkProduct["availability"]["hasStock"]){
                if(array_key_exists("web", $vkProduct["availability"]["stocks"])){
                    $outletTuote->setVarastossa($vkProduct["availability"]["stocks"]["web"]["stock"]);
                }
                else{
                    $outletTuote->setVarastossa(0);
                }
            }
        }
        else{
            $outletTuote->setVarastossa(null);
            $outletTuote->setOnVarasto(false);
        }
        $dbPidInfo = $pidDb->find($vkProduct["customerReturnsInfo"]["pid"]);
        if ($dbPidInfo == null){
                $pidInfo = new PidInfo($vkProduct["customerReturnsInfo"]["pid"]
                        , $vkProduct["package"]["width"]
                        , $vkProduct["package"]["depth"]
                        , $vkProduct["package"]["weight"]
                        , $vkProduct["package"]["height"]
                        , $vkProduct["package"]["volume"]);
                $this->entityManager->persist($pidInfo);
            }
        if ($vkProduct["package"]["volume"]<=1200000) {$outletTuote->setKoko("P");}    
        else if ($vkProduct["package"]["volume"]<=10000000) {$outletTuote->setKoko("K");}
        else {$outletTuote->setKoko("I");}
        
        return $outletTuote;
    }
       
    private function strDateToDate(string $str)
    {
        if($str == 'today'){
            $str = "now";
        }
        else{
            $str = substr($str, 0, 10);
        }
            $date = date_create($str, new \DateTimeZone('Europe/Helsinki'));
        
        return $date;
    }
}
