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
use App\Service\BookmarksService;
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
    
    public function __construct(OutletTuoteService $outletTuoteService, UpdateStatsService $updateStatsService, UserService $userService, BookmarksService $bookmarksService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->updateStatsService = $updateStatsService;
        $this->userService = $userService;
        $this->bookmarksService = $bookmarksService;
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
        $outIds = $this->bookmarksService->getBookmarks($dbuser->getId());
        $bookmarks = $this->outletTuoteService->getBookmarkedIds($outIds);
        
        $priceWatchRows = $this->bookmarksService->priceWatchRows($dbuser->getId());
        return $this->render('account.html.twig',[
            'pricewathcrows' => $priceWatchRows,
            'changePassForm' => $form->createView(),
            'bookmarks' => $bookmarks,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
        
    }
    /**
     *@Route("/account/addemail", name="addemail")
     */
    public function addEmail(Request $request): Response
    {
        $dbUser = $this->userService->findUsername($this->getUser()->getUsername())[0];
        //$userId = $dbUser->getId();
        $this->userService->addEmail($request->get('email'),$dbUser);
        return $this->redirectToRoute('account');
    }
    /**
     *@Route("/bookmark/pricewatch/edit", name="pricewatchEdit")
     */
    public function pricewatchEdit(Request $request): Response
    {
        $dbUser = $this->userService->findUsername($this->getUser()->getUsername())[0];
        $userId = $dbUser->getId();
        $pid = $request->get('pid');
        $limit = $request->get('limit');
        $armed = $request->get('armed');
        if ($armed == null){$armed=false;}
        $this->bookmarksService->editPid($pid,$userId,$limit,$armed);
        return $this->redirectToRoute('account');
    }
    
    /**
     *@Route("/bookmark/pricewatch/add_no_db", name="pricewatchAddNew")
     */
    public function pricewatchAddNew(Request $request): Response
    {
        $dbUser = $this->userService->findUsername($this->getUser()->getUsername())[0];
        $userId = $dbUser->getId();
        $pid = intval($request->get('pid'));
        $limit = intval($request->get('limit'));
        $name = $request->get('name');
        $this->bookmarksService->addNoDbPid($pid,$userId,$limit,$name);
        return $this->redirectToRoute('account');
    }
    
    /**
     *@Route("/bookmark/{mode}/{outId}", name="bookmark")
     */
    public function bookmark(string $mode,int $outId, Request $request): Response
    {
        $outId = intval($outId);
        $dbUser = $this->userService->findUsername($this->getUser()->getUsername())[0];
        $userId = $dbUser->getId();
        if ($mode == "add"){
            $this->bookmarksService->add($outId,$userId);
        }
        elseif($mode == "del"){
            $this->bookmarksService->del($outId,$userId);
        }
        else{
            
        }
        $referer = $request->headers->get('referer');
        if($referer==null){
            $referer = "/";
        }
        return $this->redirect($referer);
    }
    
    /**
     *@Route("/bookmark/pricewatch/{mode}/{pid}", name="pricewatch")
     */
    public function pricewatch(string $mode,int $pid, Request $request): Response
    {
        $pid = intval($pid);
        $dbUser = $this->userService->findUsername($this->getUser()->getUsername())[0];
        $userId = $dbUser->getId();
        if ($mode == "add"){
            $this->bookmarksService->addPid($pid,$userId);
        }
        elseif($mode == "del"){
            $this->bookmarksService->delPid($pid,$userId);
        }
        else{
            
        }
        $referer = $request->headers->get('referer');
        if($referer==null){
            $referer = "/";
        }
        return $this->redirect($referer);
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
            $active_sum_outprice = $aleprosentit['active_sum_outprice'];
            $deleted_sum_outprice = $aleprosentit['deleted_sum_outprice'];
            
            return $this->render('search.html.twig',[
            'active'=> $active,
            'deleted'=> $deleted,
            'deleted_prosentit'=>$deleted_prosentit,
            'active_prosentit'=>$active_prosentit,
            'active_sum_outprice'=> $active_sum_outprice,
            'deleted_sum_outprice'=>$deleted_sum_outprice,
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