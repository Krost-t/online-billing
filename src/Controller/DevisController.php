<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Facture;
use App\Entity\LigneFacture;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/devis')]
final class DevisController extends AbstractController
{
    #[Route(name: 'app_devis_index', methods: ['GET'])]
    public function index(DevisRepository $devisRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $devis = in_array('ROLE_ADMIN', $user->getRoles(), true)
            ? $devisRepository->findAll()
            : $devisRepository->findBy(['user' => $user]);

        return $this->render('devis/index.html.twig', [
            'devis' => $devis,
        ]);
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $devi = new Devis();
        $devi->setUser($this->getUser());

        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($devi);
            $em->flush();

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devi): Response
    {
        $user = $this->getUser();
        $devisUser = $devi->getUser();

        if (
            !$user
            || (
                !in_array('ROLE_ADMIN', $user->getRoles(), true)
                && (!$devisUser || $user->getId() !== $devisUser->getId())
            )
        ) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $devisUser = $devi->getUser();

        if (
            !$user
            || (
                !in_array('ROLE_ADMIN', $user->getRoles(), true)
                && (!$devisUser || $user->getId() !== $devisUser->getId())
            )
        ) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devi, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $devisUser = $devi->getUser();

        if (
            !$user
            || (
                !in_array('ROLE_ADMIN', $user->getRoles(), true)
                && (!$devisUser || $user->getId() !== $devisUser->getId())
            )
        ) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        if ($this->isCsrfTokenValid('delete' . $devi->getId(), $request->request->get('_token'))) {
            $em->remove($devi);
            $em->flush();
        }

        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/to-facture', name: 'app_devis_to_facture', methods: ['GET'])]
    public function convertirEnFacture(int $id, DevisRepository $devisRepository, EntityManagerInterface $em): Response
    {
        $devis = $devisRepository->find($id);

        if (!$devis) {
            throw $this->createNotFoundException('Devis non trouvé.');
        }

        $user = $this->getUser();
        $devisUser = $devis->getUser();

        if (
            !$user
            || (
                !in_array('ROLE_ADMIN', $user->getRoles(), true)
                && (!$devisUser || $user->getId() !== $devisUser->getId())
            )
        ) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $facture = new Facture();
        $facture->setClient($devis->getClient());
        $facture->setDateEmission(new \DateTimeImmutable());
        $facture->setCreatedAt(new \DateTimeImmutable());
        $facture->setStatut('En attente');
        $facture->setTotalTTC($devis->getTotalTTC());
        $facture->setNumero('FAC-' . strtoupper(uniqid()));
        $facture->setUser($user);

        foreach ($devis->getLignes() as $ligneDevis) {
            $ligneFacture = new LigneFacture();
            $ligneFacture->setDescription($ligneDevis->getDescription());
            $ligneFacture->setQuantite($ligneDevis->getQuantite());
            $ligneFacture->setPrixUnitaire($ligneDevis->getPrixUnitaire());
            $ligneFacture->setMontant($ligneDevis->getMontant());
            $ligneFacture->setFacture($facture);

            $em->persist($ligneFacture);
        }

        $em->persist($facture);
        $em->flush();

        $this->addFlash('success', 'Le devis a été transformé en facture avec succès.');

        return $this->redirectToRoute('app_facture_show', ['id' => $facture->getId()]);
    }
}
