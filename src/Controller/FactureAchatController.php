<?php

namespace App\Controller;

use App\Entity\FactureAchat;
use App\Form\FactureAchatType;
use App\Repository\FactureAchatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/facture/achat')]
final class FactureAchatController extends AbstractController
{
    #[Route(name: 'app_facture_achat_index', methods: ['GET'])]
    public function index(FactureAchatRepository $factureAchatRepository): Response
    {
        return $this->render('facture_achat/index.html.twig', [
            'facture_achats' => $factureAchatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_facture_achat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $factureAchat = new FactureAchat();
        $form = $this->createForm(FactureAchatType::class, $factureAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($factureAchat);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_achat/new.html.twig', [
            'facture_achat' => $factureAchat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_achat_show', methods: ['GET'])]
    public function show(FactureAchat $factureAchat): Response
    {
        return $this->render('facture_achat/show.html.twig', [
            'facture_achat' => $factureAchat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FactureAchat $factureAchat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureAchatType::class, $factureAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_achat/edit.html.twig', [
            'facture_achat' => $factureAchat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_achat_delete', methods: ['POST'])]
    public function delete(Request $request, FactureAchat $factureAchat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factureAchat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($factureAchat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
