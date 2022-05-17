<?php

namespace App\Controller;

use App\Entity\Applicant;
use App\Entity\JobOffer;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class offerController extends AbstractController {
    /**
     * @Route("/", name="offer_index")
     */
    public function index(EntityManagerInterface $entityManager) : Response {
        $offers = $entityManager->getRepository(JobOffer::class)
            ->findAll();
        
        return $this -> render('offer/index.html.twig',
        [
            'offers' => $offers,
        ]);
    }

    /**
     * @IsGranted("ROLE_COMPANY_OWNER")
     * @Route("/company/", name="company_offers_index")
     * @return Response
     */
    public function companyOffers() : Response {
        $user = $this->getUser();
        $company = $user->getCompany();

        if (!$company) {
            return $this->redirectToRoute("company_create");
        }

        return $this->render('offer/company_index.html.twig', [
            'offers' => $company ? $company->getJobOffers() : []
        ]);
    }
}