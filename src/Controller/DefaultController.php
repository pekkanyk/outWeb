<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OutletTuoteService;
use App\Service\UpdateStatsService;
use App\Service\UserService;
use App\Form\Type\SearchType;
use App\Model\SearchProducts;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ChangePassFormType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController{
    
    private OutletTuoteService $outletTuoteService;
    private UpdateStatsService $updateStatsService;
    private UserService $userService;
    
    public function __construct(OutletTuoteService $outletTuoteService, UpdateStatsService $updateStatsService, UserService $userService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->updateStatsService = $updateStatsService;
        $this->userService = $userService;
    }
    
    /**
     *@Route("/", name="homepage")
     */
    public function index(Request $request): Response
    {
        return $this->render('index.html.twig',[
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
        
    }
    /**
     *@Route("/account", name="account")
     */
    public function account(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $dbuser = $this->userService->findUsername($this->getUser()->getUsername())[0];
        $user = new User();
        
        $form = $this->createForm(ChangePassFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $newEncodedPass = $passwordEncoder->encodePassword($dbuser,$form->get('plainPassword')->getData());
            $this->userService->updatePassword($dbuser, $newEncodedPass);

            return $this->redirectToRoute('account');
        }
        
        return $this->render('account.html.twig',[
            'changePassForm' => $form->createView(),
            'bookmarksLkm' => "1",
            'bookmarks' => null,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
        
    }
    
    /**
     *@Route("/search", name="search")
     */
    public function search(Request $request): Response
    {
        //$form = $this->createForm(SearchType::class,new SearchProducts());
        $form = $this->createForm(SearchType::class,new SearchProducts(), array('csrf_protection' => false));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $products = $this->outletTuoteService->searchWith($form->getData());
            $active = $products['active'];
            $deleted = $products['deleted'];
            $aleprosentit = $this->outletTuoteService->aleprosentit($form->getData());
            $deleted_prosentit = $aleprosentit['deleted'];
            $active_prosentit = $aleprosentit['active'];
            
            return $this->render('search.html.twig',[
            'active'=> $active,
            'deleted'=> $deleted,
            'deleted_prosentit'=>$deleted_prosentit,
            'active_prosentit'=>$active_prosentit,
            'form'=> $form->createView(),
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
        }
        
        
        return $this->render('search.html.twig',[
            'active'=>null,
            'deleted'=>null,
            'form'=> $form->createView(),
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
        
    }
    
    
    
}