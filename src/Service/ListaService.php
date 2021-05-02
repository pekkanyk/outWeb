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
        
        for ($i=0;$i<12;$i++){
            if ($rivit[$i]!=null) {
                $header = $this->makeHeader($i);
                if ($i==11 || $i==10){
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
            if ($i==11){
                $list= array_merge($list,$page);
            }
        }
        return $list;
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
        if($i==10){$paikka = "MATKAHUOLTO / MUUT";}
        elseif($i==11){$paikka= "Moniriviset";}
        elseif($i==9) {$paikka="0";}
        else {$paikka = $i+1;}
        return ["********** ".$paikka." **********"];
    }
    
    private function readList($listaStr) {
        $listarivit = array_fill(0, 12, []);
        $edellinen = "";
        $listaArr = explode("Postal", $listaStr);
        $listaTuotteet = $this->tuoteRivitoListaRivi($listaArr);
        for ($i=0;$i<count($listaTuotteet);$i++){
            $outVika = $listaTuotteet[$i]->getOutidVika();
            $tilausnro = $listaTuotteet[$i]->getTilausnro();
            $toimitus = $listaTuotteet[$i]->getToimitus();
            $hyllypaikka = $listaTuotteet[$i]->getHyllypaikka();
            if ($tilausnro!=null){
                array_push($listarivit[11],$listaTuotteet[$i]);
            }
            elseif($toimitus=="M" || ($hyllypaikka != $outVika)){
                array_push($listarivit[10],$listaTuotteet[$i]);
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
    private function riviArrToListaTuoterivi($arr){
        $riviArray = preg_split("/[\t]/", $arr);
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $listatuotteet = [];
        if (count($riviArray)>=7 && $riviArray[5]=="Jätkäsaari"){
            $hyllypaikat = explode(",",$riviArray[4]);
            if (count($hyllypaikat)==1){
                $listatuote = new ListaTuoterivi();
                $listatuote->setOutid($this->parseOutId($riviArray[6]));
                $listatuote->setHyllypaikka($riviArray[4]);
                $listatuote->setHk($this->henksu($riviArray[2]));
                $listatuote->setTilausnro(null);
                $listatuote->setToimitus($this->kuljetus($riviArray[3]));
                $listatuote->setOutidTokavika($this->getTokavika($listatuote->getOutid()));
                $listatuote->setOutidVika($this->getVika($listatuote->getOutid()));
                $outFromDb = $db->find($listatuote->getOutid());
                if ($outFromDb!=null){
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
                    $listatuote->setOutid($this->parseOutId($tuotteet[$i]));
                    $listatuote->setHyllypaikka($hyllypaikat[$i]);
                    $listatuote->setHk($this->henksu($riviArray[2]));
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
        for ($i=0;$i<12;$i++){
            if ($listarivit[$i]!=null){
                array_push($sortedArr, $this->sortByTokavika($listarivit[$i])); 
            }
            else{
                array_push($sortedArr,[]);
            }
        }
        return $sortedArr;
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
        if ($pidInfo!=null) { return "OUT".$outTuote->getOutId()." ".substr($outTuote->getName(), 0,69)." ".$pidInfo->sizeStrSm()." ".$outTuote->daysActive(); }
        else {return "OUT".$outTuote->getOutId()." ".substr($outTuote->getName(), 0,69)." "."-x-x-"." ".$outTuote->daysActive();}
    }
    
    private function kuljetus($str) {
        if (str_contains($str, "atka")){
            return "M";
        }
        else {
            return "P";
            
        }
    }
    
    private function henksu($str) {
        if ($str == "Ei"){
            return "o";
        }
        else{
            return "H";
        }
    }
    
}
