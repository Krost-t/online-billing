<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Entity\Devis;
use App\Entity\Facture;
use App\Entity\FactureAchat;
use App\Entity\LigneDevis;
use App\Entity\LigneFacture;
use App\Entity\User;
use App\Enum\EtatDevis;
use App\Enum\EtatFacture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $roles = [
            ['ROLE_SUPER_ADMIN'],
            ['ROLE_ADMIN'],
            ['ROLE_ADMIN'],
            ['ROLE_ADMIN'],
            ['ROLE_ADMIN'],
        ];

        $users = [];
        // Création des utilisateurs
        foreach ($roles as $i => $role) {
            $user = new User();
            $user->setEmail(sprintf('%s@example.com', $i === 0 ? 'superadmin' : 'admin' . $i));
            $user->setRoles($role);
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
            $user->setNom($faker->lastName());
            $user->setPrenom($faker->firstName());
            $manager->persist($user);
            $users[] = $user;
        }

        // Pour chaque utilisateur
        foreach ($users as $user) {
            // Création de FactureAchat sample (5 par user)
            for ($k = 0; $k < 5; $k++) {
                $achat = new FactureAchat();
                $achat->setUserFactureAchat($user)
                      ->setDatePaiement($faker->dateTimeBetween('-1 years', 'now'))
                      ->setPdfPath(null);
                $manager->persist($achat);
            }

            // Création de devis et factures
            for ($i = 0; $i < 10; $i++) {
                // Adresse et client associés
                $adresse = new Adresse();
                $adresse->setNomRue($faker->streetName())
                        ->setNumeroLogement($faker->buildingNumber())
                        ->setCodePostal((int)$faker->postcode())
                        ->setBatiment($faker->randomElement([null, 'A', 'B', 'C']))
                        ->setUser($user);
                $manager->persist($adresse);

                $client = new Client();
                $client->setNom($faker->lastName())
                       ->setPrenom($faker->firstName())
                       ->setMail($faker->email())
                       ->setTelephone($faker->phoneNumber())
                       ->setSiret($faker->boolean(50) ? $faker->numerify('#############') : null)
                       ->setCreatedAt(new \DateTimeImmutable())
                       ->setUpdatedAt(new \DateTime())
                       ->setAdresseClient($adresse)
                       ->setUserClient($user);
                $manager->persist($client);

                // Devis
                $devis = new Devis();
                $devis->setClient($client)
                      ->setUserDevis($user)
                      ->setContions($faker->sentence())
                      ->setEtat(EtatDevis::EN_ATTENTE);
                $manager->persist($devis);

                // Lignes Devis
                $nbLignes = $faker->numberBetween(1, 5);
                for ($j = 0; $j < $nbLignes; $j++) {
                    $ld = new LigneDevis();
                    $ld->setNameProduit($faker->word())
                       ->setDescription($faker->sentence())
                       ->setQuantite($faker->numberBetween(1, 10))
                       ->setPrixUnitaireHt($faker->randomFloat(2, 10, 500))
                       ->setTva($faker->randomElement([0.2, 0.1, 0.055]))
                       ->setDevis($devis);
                    $manager->persist($ld);
                }

                // Facture issue du Devis (50% chance)
                if ($faker->boolean(50)) {
                    $devis->setEtat(EtatDevis::ACCEPTE);
                    $factureLinked = new Facture();
                    $factureLinked->setClient($client)
                                  ->setUserFacture($user)
                                  ->setDevisToFacture($devis)
                                  ->setStatutPayement($faker->randomElement([
                                      EtatFacture::NON_PAYEE,
                                      EtatFacture::PARTIELLEMENT_PAYEE,
                                      EtatFacture::PAYEE
                                  ]));
                    $manager->persist($factureLinked);

                    foreach ($devis->getLigneDevis() as $ligneDevis) {
                        $lf = new LigneFacture();
                        $lf->setNameProduit($ligneDevis->getNameProduit())
                           ->setDescription($ligneDevis->getDescription())
                           ->setQuantite($ligneDevis->getQuantite())
                           ->setPrixUnitaireHt($ligneDevis->getPrixUnitaireHt())
                           ->setTva($ligneDevis->getTva())
                           ->setFacture($factureLinked);
                        $manager->persist($lf);
                    }
                }

                // Facture indépendante
                $facture = new Facture();
                $facture->setClient($client)
                        ->setUserFacture($user)
                        ->setStatutPayement($faker->randomElement([
                            EtatFacture::NON_PAYEE,
                            EtatFacture::PARTIELLEMENT_PAYEE,
                            EtatFacture::PAYEE
                        ]));
                $manager->persist($facture);

                $nbLignesF = $faker->numberBetween(1, 5);
                for ($j = 0; $j < $nbLignesF; $j++) {
                    $lf = new LigneFacture();
                    $lf->setNameProduit($faker->word())
                       ->setDescription($faker->sentence())
                       ->setQuantite($faker->numberBetween(1, 10))
                       ->setPrixUnitaireHt($faker->randomFloat(2, 10, 500))
                       ->setTva($faker->randomElement([0.2, 0.1, 0.055]))
                       ->setFacture($facture);
                    $manager->persist($lf);
                }
            }
        }
        
        $manager->flush();
    }
}
