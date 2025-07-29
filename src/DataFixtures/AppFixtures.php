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
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use App\Enum\EtatDevis;
use App\Enum\EtatFacture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('fr_FR');

        // 1. USERS (Super Admin, Admins, Users)
        $users = [];

        // 1.a Super Admin
        $superAdmin = new User();
        $superAdmin->setEmail('superadmin@example.com')
            ->setPassword(password_hash('password', PASSWORD_BCRYPT))
            ->setPrenom('Super')
            ->setNom('Admin')
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setIsVerified(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTime());
        $manager->persist($superAdmin);
        $users[] = $superAdmin;

        // 1.b Admins
        for ($i = 0; $i < 6; $i++) {
            $admin = new User();
            $created = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years'));
            $updated = $faker->dateTime();

            $admin->setEmail("admin{$i}@example.com")
                ->setPassword(password_hash('password', PASSWORD_BCRYPT))
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setRoles(['ROLE_ADMIN'])
                ->setIsVerified(true)
                ->setCreatedAt($created)
                ->setUpdatedAt($updated);

            $manager->persist($admin);
            $users[] = $admin;
        }

        // 1.c Simple Users
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $created = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months'));
            $updated = $faker->dateTime();

            $user->setEmail("user{$i}@example.com")
                ->setPassword(password_hash('password', PASSWORD_BCRYPT))
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setRoles(['ROLE_USER'])
                ->setIsVerified(true)
                ->setCreatedAt($created)
                ->setUpdatedAt($updated);

            $manager->persist($user);
            $users[] = $user;
        }

        // 2. ADDRESSES
        $addresses = [];
        foreach ($users as $user) {
            for ($j = 0; $j < 2; $j++) {
                $adresse = new Adresse();
                $adresse->setNumeroLogement((int)$faker->buildingNumber)
                    ->setNomRue($faker->streetName)
                    ->setCodePostal((int)$faker->postcode)
                    ->setBatiment($faker->boolean(30) ? strtoupper($faker->randomLetter) : null)
                    ->setUser($user);
                $manager->persist($adresse);
                $addresses[] = $adresse;
            }
        }

        // 3. CLIENTS
        $clients = [];
        foreach ($users as $user) {
            if (!in_array('ROLE_ADMIN', $user->getRoles())) continue;

            for ($i = 0; $i < 4; $i++) {
                $client = new Client();
                $created = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months'));
                $updated = $faker->dateTime();

                $client->setNom($faker->company)
                    ->setPrenom($faker->firstName)
                    ->setMail($faker->companyEmail)
                    ->setTelephone($faker->phoneNumber)
                    ->setSiret($faker->boolean(50) ? $faker->vat : null)
                    ->setCreatedAt($created)
                    ->setUpdatedAt($updated)
                    ->setAdresseClient($faker->randomElement($addresses))
                    ->setUserClient($user);

                $manager->persist($client);
                $clients[] = $client;
            }
        }

        // 4. DEVIS & LIGNES
        $devisList = [];
        foreach ($clients as $client) {
            for ($i = 0; $i < 10; $i++) {
                $devis = new Devis();
                $devis->setContions('Valid 30 days')
                    ->setEtat($faker->randomElement(EtatDevis::cases()))
                    ->setClient($client)
                    ->setUserDevis($client->getUserClient());

                $totalHt = 0;
                $nbLignes = rand(3, 5);
                for ($j = 0; $j < $nbLignes; $j++) {
                    $qty = $faker->numberBetween(1, 10);
                    $unit = $faker->randomFloat(2, 20, 500);
                    $lineHt = round($qty * $unit, 2);
                    $tvaRate = 20.0;
                    $lineTtc = round($lineHt * (1 + $tvaRate/100), 2);

                    $ligne = new LigneDevis();
                    $ligne->setNameProduit($faker->word)
                        ->setDescription($faker->sentence)
                        ->setQuantite($qty)
                        ->setPrixUnitaireHt($unit)
                        ->setTva($tvaRate)
                        ->setTotalHt($lineHt)
                        ->setTotalTtc($lineTtc)
                        ->setDevis($devis);

                    $manager->persist($ligne);
                    $totalHt += $lineHt;
                }

                $tva = round($totalHt * 0.2, 2);
                $devis->setTotalHt($totalHt)
                    ->setTotalTva($tva)
                    ->setTotalTtc(round($totalHt + $tva, 2));

                $manager->persist($devis);
                $devisList[] = $devis;
            }
        }

        // 5. FACTURES & LIGNES
        foreach ($devisList as $devis) {
            if ($faker->boolean(70) && null === $devis->getFacture()) {
                $facture = new Facture();
                $facture->setClient($devis->getClient())
                    ->setUserFacture($devis->getUserDevis())
                    ->setDevisToFacture($devis)
                    ->setStatutPayement($faker->randomElement(EtatFacture::cases()));

                $totalHt = 0;
                foreach ($devis->getLigneDevis() as $dLine) {
                    $tvaRate = $dLine->getTva();
                    $lineHt  = $dLine->getTotalHt();
                    $lineTtc = round($lineHt * (1 + $tvaRate/100), 2);

                    $fLine = new LigneFacture();
                    $fLine->setNameProduit($dLine->getNameProduit())
                        ->setDescription($dLine->getDescription())
                        ->setQuantite($dLine->getQuantite())
                        ->setPrixUnitaireHt($dLine->getPrixUnitaireHt())
                        ->setTva($tvaRate)
                        ->setTotalHt($lineHt)
                        ->setTotalTtc($lineTtc)
                        ->setFacture($facture);

                    $manager->persist($fLine);
                    $totalHt += $lineHt;
                }

                $tva = round($totalHt * 0.2, 2);
                $facture->setTotalHt($totalHt)
                    ->setTotalTva($tva)
                    ->setTotalTtc(round($totalHt + $tva, 2));

                $manager->persist($facture);
            }
        }

        // 6. FACTURES D'ACHAT FACTURES D'ACHAT FACTURES D'ACHAT
        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $fa = new FactureAchat();
                $fa->setPdfPath('facture_achat_' . $faker->uuid() . '.pdf')
                    ->setDatePaiement($faker->optional()->dateTimeThisYear)
                    ->setUserFactureAchat($user);

                $manager->persist($fa);
            }
        }

        $manager->flush();
    }
}
