<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Entity\OutletTuote;
use App\Entity\PidInfo;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\ListaTuoterivi;


class ListaService{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function makeList($listaStr) {
        $rivit = $this->readList($listaStr);
        $list = [];
        $page = [];
        $footer = ["*********************",".",".","."];
        
        for ($i=0;$i<13;$i++){
            if ($rivit[$i]!=null) {
                $header = $this->makeHeader($i);
                //if ($i==12 || $i==10){
                if ($i==12){    
                    $pagePart = array_merge($header,$rivit[$i]);
                }
                elseif ($i==11 && $rivit[12]==null){
                    $pagePart = array_merge($header,$rivit[$i]);
                }
                elseif ($i==10 && ($rivit[12]==null && $rivit[12]==null)){
                    $pagePart = array_merge($header,$rivit[$i]);
                }
                else{
                    $pagePart = array_merge($header,$rivit[$i],$footer);
                }
                if (count($page)+count($pagePart)<41){
                    $page = array_merge($page,$pagePart);
                }
                else{
                    $list = array_merge($list, $this->fillPage($page));
                    $page = [];
                    $page = array_merge($page,$pagePart);
                }
            }
            if ($i==12){
                $list= array_merge($list,$page);
            }
        }
        date_default_timezone_set('Europe/Helsinki');
        $date = date('d.m.Y H:i:s', time());
        array_push($list,"\n\nTulostettu: ".$date." uusin tilnro oli: ".$rivit[13][0]);
        return $list;
    }
    
    public function makeShitList($days,$maxprice) {
        $rivit = $this->makeShittyList($days,$maxprice);
        
        $list = [];
        $page = [];
        $footer = ["*********************",".",".","."];
        
        for ($i=0;$i<13;$i++){
            if ($rivit[$i]!=null) {
                $header = $this->makeHeader($i);
                //if ($i==12 || $i==10){
                if ($i==12){    
                    $pagePart = array_merge($header,$rivit[$i]);
                }
                elseif ($i==11 && $rivit[12]==null){
                    $pagePart = array_merge($header,$rivit[$i]);
                }
                elseif ($i==10 && ($rivit[12]==null && $rivit[12]==null)){
                    $pagePart = array_merge($header,$rivit[$i]);
                }
                else{
                    $pagePart = array_merge($header,$rivit[$i],$footer);
                }
                if (count($page)+count($pagePart)<41){
                    $page = array_merge($page,$pagePart);
                }
                else{
                    $list = array_merge($list, $this->fillPage($page));
                    $page = [];
                    $page = array_merge($page,$pagePart);
                }
            }
            if ($i==12){
                $list= array_merge($list,$page);
            }
        }
        return $list;
    }
    
    private function makeShittyList($days,$maxprice) {
        $listarivit = array_fill(0, 13, []);
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $daysStr = "P".$days."D";
        $activity = "active";
        $alkaen = $this->makeDate(null, "2020-01-01");
        $asti = (new \DateTime('now', new \DateTimeZone('Europe/Helsinki')))->sub(new \DateInterval($daysStr));
        $asti->setTime(23,59,59);
        $minprice = "0";
        //$maxprice = "2";
        $orderby = "pid";
        $direction = "ASC";
        $searchStr = "%%";
        $kl = ['A','B','C','D'];
        $dbTuotteet = $db->searchActive($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl);
        
        $listaTuotteet = [];
        for ($i=0;$i<count($dbTuotteet);$i++){
            $rivinTuotteet = $this->dbTuoteToListaTuoterivi($dbTuotteet[$i]);
            array_push($listaTuotteet,$rivinTuotteet);
            }
        
        
        
        for ($i=0;$i<count($listaTuotteet);$i++){
            $outVika = $listaTuotteet[$i]->getOutidVika();
            $tilausnro = $listaTuotteet[$i]->getTilausnro();
            $toimitus = $listaTuotteet[$i]->getToimitus();
            $hyllypaikka = $listaTuotteet[$i]->getHyllypaikka();
            if ($tilausnro!=null){
                array_push($listarivit[11],$listaTuotteet[$i]);
            }
            elseif($toimitus=="M"){
                array_push($listarivit[10],$listaTuotteet[$i]);
            }
            elseif($hyllypaikka != $outVika){
                array_push($listarivit[12],$listaTuotteet[$i]);
            }
            else{
                //array_push($listarivit[$outVika],$listaTuotteet[$i]); //poikkeus, koska aloitetaan 1.
                if ($outVika==0){array_push($listarivit[9],$listaTuotteet[$i]);}
                else {
                    $luku = intval($outVika);
                    $luku--;
                    array_push($listarivit[$luku],$listaTuotteet[$i]); 
                    
                }
            }
        }
        
        return $this->sortedArr($listarivit);
    }
    private function dbTuoteToListaTuoterivi($outId){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        
                $listatuote = new ListaTuoterivi();
                $listatuote->setOutid($outId->getOutId());
                $listatuote->setHyllypaikka($this->getVika($listatuote->getOutid()));
                $listatuote->setHk("o");
                $listatuote->setTilausnro(null);
                $listatuote->setToimitus("P");
                $listatuote->setOutidTokavika($this->getTokavika($listatuote->getOutid()));
                $listatuote->setOutidVika($this->getVika($listatuote->getOutid()));
                $listatuote->setTuote($this->makeName($outId));
            
        return $listatuote;
    }
    
    private function makeDate($date,$default) {
        $dtz = new \DateTimeZone('Europe/Helsinki');
        //$date = $this->validateDate($date);
        if ($date!=null) { return $date; }
        else { return new \DateTime($default,$dtz); }
        
    }
    
    private function fillPage($page) {
        $temp = [];
        $riveja = count($page);
        while($riveja>=51){
            $riveja=$riveja-51;
        }
        $riveja--;
        $temp = array_fill(0, 51-$riveja, ".");
        return array_merge($page,$temp);
    }
    
    private function makeHeader($i){
        if($i==10){$paikka = "MATKAHUOLTO";}
        elseif($i==11){$paikka= "Moniriviset";}
        elseif($i==12){$paikka= "Muut hyllypaikat";}
        elseif($i==9) {$paikka="0";}
        else {$paikka = $i+1;}
        return ["********** ".$paikka." **********"];
    }
    
    private function readList($listaStr) {
        $listarivit = array_fill(0, 13, []);
        $edellinen = "";
        $isoinTilnro = 0;
        $listaArr = explode("Postal", $listaStr);
        $listaTuotteet = $this->tuoteRivitoListaRivi($listaArr);
        for ($i=0;$i<count($listaTuotteet);$i++){
            $outVika = $listaTuotteet[$i]->getOutidVika();
            $tilausnro = $listaTuotteet[$i]->getTilausnro();
            if ($isoinTilnro<intval($tilausnro)) {$isoinTilnro = intval($tilausnro);}
            $toimitus = $listaTuotteet[$i]->getToimitus();
            $hyllypaikka = $listaTuotteet[$i]->getHyllypaikka();
            if ($listaTuotteet[$i]->getMonirivinen()){
                array_push($listarivit[11],$listaTuotteet[$i]);
            }
            elseif($toimitus=="M"){
                array_push($listarivit[10],$listaTuotteet[$i]);
            }
            elseif($hyllypaikka != $outVika){
                array_push($listarivit[12],$listaTuotteet[$i]);
            }
            else{
                //array_push($listarivit[$outVika],$listaTuotteet[$i]); //poikkeus, koska aloitetaan 1.
                if ($outVika==0){array_push($listarivit[9],$listaTuotteet[$i]);}
                else {
                    $luku = intval($outVika);
                    $luku--;
                    array_push($listarivit[$luku],$listaTuotteet[$i]); 
                    
                }
            }
        }
        $tempArr = array($isoinTilnro);
        $sortedArrays = $this->sortedArr($listarivit);
        array_push($sortedArrays,$tempArr);
        return $sortedArrays;
    }
    private function riviArrToListaTuoterivi($arr){
        $riviArray = preg_split("/[\t]/", $arr);
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $listatuotteet = [];
        if (count($riviArray)>=7 && $riviArray[5]=="Jätkäsaari"){
            $hyllypaikat = explode(",",$riviArray[4]);
            if (count($hyllypaikat)==1){
                $listatuote = new ListaTuoterivi();
                $listatuote->setMonirivinen(false);
                $listatuote->setOutid($this->parseOutId($riviArray[6]));
                $outFromDb = $db->find($listatuote->getOutid());
                $listatuote->setHyllypaikka($riviArray[4]);
                //if ($outFromDb!=null){ $listatuote->setHk($this->koko($outFromDb));}
                $listatuote->setHk($this->henksu($riviArray[2],$outFromDb));
                $listatuote->setTilausnro($riviArray[0]);
                $listatuote->setToimitus($this->kuljetus($riviArray[3]));
                $listatuote->setOutidTokavika($this->getTokavika($listatuote->getOutid()));
                $listatuote->setOutidVika($this->getVika($listatuote->getOutid()));
                
                if ($outFromDb!=null && $listatuote->getOutidVika()!=$listatuote->getHyllypaikka()){
                        $listatuote->setTuote($this->makeNameWithHP($outFromDb,$listatuote->getHyllypaikka()));
                }
                elseif ($outFromDb!=null){
                        $listatuote->setTuote($this->makeName($outFromDb));
                }
                else{
                    $listatuote->setTuote($riviArray[6]." <-CHECK ID");
                }
                array_push($listatuotteet,$listatuote);
            }
            else {
                $tuotteet = explode("\n",$riviArray[6]);
                for ($i=0;$i<count($hyllypaikat);$i++){
                    $listatuote = new ListaTuoterivi();
                    $listatuote->setMonirivinen(true);
                    $listatuote->setOutid($this->parseOutId($tuotteet[$i]));
                    $listatuote->setHyllypaikka($hyllypaikat[$i]);
                    $listatuote->setHk($this->henksu($riviArray[2],null));
                    $listatuote->setTilausnro($riviArray[0]);
                    $listatuote->setToimitus($this->kuljetus($riviArray[3]));
                    $listatuote->setOutidTokavika($this->getTokavika($listatuote->getOutid()));
                    $listatuote->setOutidVika($this->getVika($listatuote->getOutid()));
                    $outFromDb = $db->find($listatuote->getOutid());
                    if ($outFromDb!=null){
                        $listatuote->setTuote($this->makeName($outFromDb));
                    }
                    else{
                        $listatuote->setTuote($tuotteet[$i]." <-CHECK ID");
                    }
                    array_push($listatuotteet,$listatuote);
                    }
            }
        }
        
        return $listatuotteet;
    }
    
    private function sortedArr($listarivit) {
        $sortedArr= [];
        for ($i=0;$i<13;$i++){
            if ($listarivit[$i]!=null){
                
                if ($i==10){
                    array_push($sortedArr, $this->sortByVikaAndTokavika($listarivit[$i]));
                }
                
                elseif ($i==12){
                 
                    //array_push($sortedArr, $this->sortByVikaAndTokavika($listarivit[$i]));
                    usort($listarivit[$i], function($a, $b) {return strcmp($a->hyllypaikka, $b->hyllypaikka);});
                    array_push($sortedArr, $this->otherHP($listarivit[$i]));
                }
                
                else {
                    array_push($sortedArr, $this->sortByTokavika($listarivit[$i])); 
                }
                
            }
            else{
                array_push($sortedArr,[]);
            }
        }
        return $sortedArr;
    }
    
    private function otherHP($arr){
        $temp[] = null;
        for ($i=0;$i<count($arr);$i++){
            array_push($temp,$this->makeOneLine($arr[$i]));
        }
        return $temp;
    }
    
    private function sortByVikaAndTokavika($arr){
        $numerot = array_fill(0,10,[]);
        for ($i=0;$i<count($arr);$i++){
            array_push($numerot[$arr[$i]->getOutIdVika()],$arr[$i]);
            
        }
        $numerot2 = array_fill(0,10,[]);
        for ($i=0;$i<10;$i++){
            $numerot2[$i]= $this->sortByTokavika($numerot[$i]);
            if (count($numerot2[$i])!=0) {array_push($numerot2[$i],"."); }
        }
        return array_merge($numerot2[0],$numerot2[1],$numerot2[2],$numerot2[3],$numerot2[4],$numerot2[5],$numerot2[6],$numerot2[7],$numerot2[8],$numerot2[9]);

        
    }
    
    private function sortByVika($arr){
        $numerot = array_fill(0,10,[]);
        for ($i=0;$i<count($arr);$i++){
            array_push($numerot[$arr[$i]->getOutIdVika()],$this->makeOneLine($arr[$i]));
        }
        return array_merge($numerot[0],$numerot[1],$numerot[2],$numerot[3],$numerot[4],$numerot[5],$numerot[6],$numerot[7],$numerot[8],$numerot[9]);
    }
    
    private function sortByTokavika($arr) {
        $numerot = array_fill(0,10,[]);
        for ($i=0;$i<count($arr);$i++){
            array_push($numerot[$arr[$i]->getOutIdTokaVika()],$this->makeOneLine($arr[$i]));
        }
        return array_merge($numerot[0],$numerot[1],$numerot[2],$numerot[3],$numerot[4],$numerot[5],$numerot[6],$numerot[7],$numerot[8],$numerot[9]);
        
    }
    
    private function makeOneLine($listatuote){
        return $listatuote->getHk()." ".$listatuote->getTuote();
    }
    
    private function tuoteRivitoListaRivi($riviArr) {
        $listaTuotteet = [];
        for ($i=0;$i<count($riviArr);$i++){
            $rivinTuotteet = $this->riviArrToListaTuoterivi($riviArr[$i]);
            for ($j=0;$j<count($rivinTuotteet);$j++){
                array_push($listaTuotteet,$rivinTuotteet[$j]);
            }
        }
        return $listaTuotteet;
    }
    
    private function parseOutId($str) {
        $temp = explode(" ", $str);
        $outid=substr($temp[0],3);
        return intval($outid);
    }
    
    private function getVika($outId) {
        return intval(substr($outId, -1));
    }
    private function getTokavika($outId){
        return intval(substr($outId,-2,1));
    }
    
    private function makeName($outTuote) {
        $pidDb = $this->entityManager->getRepository(PidInfo::class);
        $pidInfo = $pidDb->find($outTuote->getPid());
        if ($pidInfo!=null) { return "OUT".$outTuote->getOutId()." ".substr($outTuote->getName(), 0,69)." ".$pidInfo->sizeStrSm()." ".$outTuote->daysListed(); }
        else {return "OUT".$outTuote->getOutId()." ".substr($outTuote->getName(), 0,69)." "."-x-x-"." ".$outTuote->daysListed();}
    }
    
    private function makeNameWithHP($outTuote,$hyllypaikka) {
        return $hyllypaikka ." OUT".$outTuote->getOutId()." ".substr($outTuote->getName(), 0,69)." ".$outTuote->daysListed();
        
    }
    
    private function kuljetus($str) {
        if (str_contains($str, "atka")){
            return "M";
        }
        else {
            return "P";
            
        }
    }
    
    private function henksu($str, $outTuote) {
        
        if ($str == "Ei"){
            if ($outTuote!=null){
                if ($outTuote->getKoko()=="I") {return "I";  }
                //return $outTuote->getKoko();
            }
            return "o";
        }
        //return "o";
            
        else{
            return "H";
        }
    }
    
    private function koko($outTuote){
        $pidDb = $this->entityManager->getRepository(PidInfo::class);
        $pidInfo = $pidDb->find($outTuote->getPid());
        $raja = 400;
        if ($pidInfo!=null){
            //if (($pidInfo->getDepth()>=$raja || $pidInfo->getHeight()>=$raja)||$pidInfo->getWidth() >=$raja){
            if ($pidInfo->getVolume()>=$raja){
                return "I";
            }
        }
        return "o";
    }
    
}
