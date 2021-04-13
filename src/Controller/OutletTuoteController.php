<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\OutletTuote;

class OutletTuoteController extends AbstractController
{
    /**
     * @Route("/outid/{outId}", name="outlet_tuote")
     */
    public function show(int $outId): Response
    {
	$outletTuote = $this->getDoctrine()
		->getRepository(OutletTuote::class)
		->find($outId);
	
	if (!$outletTuote) {
		throw $this->createNotFoundException(
			'Outlet ID Not found:' ,$outId
			);
	}
	return new Response('Outlet ID nimi: '.$outletTuote->getName());
	
    }
}
