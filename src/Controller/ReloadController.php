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
use App\Service\ReloadService;
use App\Service\BookmarksService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Service\UserService;
use App\Service\OutletTuoteService;
use App\Entity\OutletTuote;
use App\Entity\PriceWatch;
use App\Entity\User;

class ReloadController extends AbstractController{
    private ReloadService $reloadService;
    public function __construct(ReloadService $reloadService,BookmarksService $bookmarksService, UserService $userService, OutletTuoteService $outletTuoteService) {
        $this->reloadService = $reloadService;
        $this->bookmarksService = $bookmarksService;
        $this->userService = $userService;
        $this->outletTuoteService = $outletTuoteService;
    }
    /**
     *@Route("/reload")
     */
    public function reloadProducts(): Response {
        $this->reloadService->updateDb();
        //return new Response(print("done"));
        return $this->redirectToRoute('email_pricewatch');
        //return $this->redirectToRoute('homepage');
    }
    
    /**
     * @Route("/reload/email", name="email_pricewatch")
     */
    public function sendEmail(MailerInterface $mailer): Response
    {
        $priceWathcObjects = $this->bookmarksService->getAllPriceWatches();
        for ($i=0;$i<count($priceWathcObjects);$i++){
           $outletTuote = $this->outletTuoteService->getCheapestActivePid($priceWathcObjects[$i]->getPid());
           if ($outletTuote != null && ($priceWathcObjects[$i]->getPriceLimit() > $outletTuote->getOutPrice())){
               $user = $this->userService->findByUserId($priceWathcObjects[$i]->getUserId());
               if ($user->getEmail()!=null && $priceWathcObjects[$i]->getArmed()){
                   $email = (new Email())
                    ->from('outweb@outweb.ddns.net')
                    ->to($user->getEmail())
                    ->subject($outletTuote->getName())
                    ->text('Tarkkailuun laittamasi tuote '.$outletTuote->getPid().' - '.$outletTuote->getName().' on outletissa  ja '
                    . 'alle määrrittelemäsi raja-arvon. '.$priceWathcObjects[$i]->getPriceLimit()
                    . ' e'
                    . 'Tuotelinkki: https://www.verkkokauppa.com/fi/outlet/yksittaiskappaleet/'.$outletTuote->getOutId()
                    . ' Merkinnän aktivointi poistettu' );
                    $mailer->send($email);
                    
                    $this->bookmarksService->unarmPriceWatch($outletTuote->getPid(),$user->getId());
               }
               
           }
           
           
        }
        

        return $this->redirectToRoute('homepage');
    }

}
