<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\CompanyOwner;
use App\Entity\JobOffer;
use App\Form\CompanyType;
use App\Form\JobOfferType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    /**
     * @IsGranted("ROLE_COMPANY_OWNER")
     * @Route("/", name="company_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->redirectToRoute('job_offer_index', [
            'error'=>'oh shit',
        ]);
    }

    /**
     * @IsGranted("ROLE_COMPANY_OWNER")
     * @Route("/new", name="company_new", methods={"GET","POST"})
     */
    public function create(Request $request): Response {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyOwner = $this->getUser();
            $companyOwner->setCompany($company);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}