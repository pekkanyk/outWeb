<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class UserService{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function findUsername($username){
        $db = $this->entityManager->getRepository(User::class);
        $user = $db->findByUsername($username);
        
        return $user;
    }
    
    
    public function findByUserId($userId){
        $db = $this->entityManager->getRepository(User::class);
        return $db->find($userId);
    }
    
    public function updatePassword($user, $hashedPassword):void{
        $db = $this->entityManager->getRepository(User::class);
        $db->upgradePassword($user, $hashedPassword);
    }
    
    public function addEmail($email,$user){
        $db = $this->entityManager->getRepository(User::class);
        $user->setEmail($email);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
    
}
