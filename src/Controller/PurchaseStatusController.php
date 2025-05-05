<?php

namespace App\Controller;

use App\Entity\PurchaseStatus;
use App\Form\PurchaseStatusForm;
use App\Repository\PurchaseStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/purchase/status')]
final class PurchaseStatusController extends AbstractController
{
    #[Route(name: 'app_purchase_status_index', methods: ['GET'])]
    public function index(PurchaseStatusRepository $purchaseStatusRepository): Response
    {
        return $this->render('purchase_status/index.html.twig', [
            'purchase_statuses' => $purchaseStatusRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_purchase_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $purchaseStatus = new PurchaseStatus();
        $form = $this->createForm(PurchaseStatusForm::class, $purchaseStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($purchaseStatus);
            $entityManager->flush();

            return $this->redirectToRoute('app_purchase_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('purchase_status/new.html.twig', [
            'purchase_status' => $purchaseStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_purchase_status_show', methods: ['GET'])]
    public function show(PurchaseStatus $purchaseStatus): Response
    {
        return $this->render('purchase_status/show.html.twig', [
            'purchase_status' => $purchaseStatus,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_purchase_status_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PurchaseStatus $purchaseStatus, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PurchaseStatusForm::class, $purchaseStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_purchase_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('purchase_status/edit.html.twig', [
            'purchase_status' => $purchaseStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_purchase_status_delete', methods: ['POST'])]
    public function delete(Request $request, PurchaseStatus $purchaseStatus, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$purchaseStatus->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($purchaseStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_purchase_status_index', [], Response::HTTP_SEE_OTHER);
    }
}
