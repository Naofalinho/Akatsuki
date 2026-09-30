<?php

namespace Database\Seeders;

use App\Models\JobOffer;
use Illuminate\Database\Seeder;

class JobOfferSeeder extends Seeder
{
    public function run(): void
    {
        JobOffer::create([
            'user_id' => null,
            'title' => 'Développeur Laravel / PHP',
            'company' => 'AKATSUKI Digital',
            'category' => 'Informatique',
            'location' => 'Cotonou',
            'contract' => 'CDI',
            'salary' => '300 000 - 500 000 FCFA',
            'description' => 'Nous recherchons un développeur Laravel/PHP pour participer au développement de nos applications web.',
            'requirements' => 'Maîtrise de PHP, Laravel, MySQL, Git et JavaScript.',
            'benefits' => 'Environnement de travail dynamique et possibilités d’évolution.',
            'deadline' => now()->addDays(30),
            'status' => 'published',
        ]);

        JobOffer::create([
            'user_id' => null,
            'title' => 'Commercial(e)',
            'company' => 'AKATSUKI Business',
            'category' => 'Commerce',
            'location' => 'Cotonou',
            'contract' => 'CDI',
            'salary' => '250 000 - 400 000 FCFA',
            'description' => 'Rejoignez notre équipe commerciale et participez au développement de notre portefeuille clients.',
            'requirements' => 'Bonne communication, dynamisme et expérience commerciale.',
            'benefits' => 'Primes sur objectifs et environnement professionnel.',
            'deadline' => now()->addDays(25),
            'status' => 'published',
        ]);

        JobOffer::create([
            'user_id' => null,
            'title' => 'Community Manager',
            'company' => 'Digital Agency',
            'category' => 'Marketing',
            'location' => 'Porto-Novo',
            'contract' => 'CDD',
            'salary' => '200 000 - 350 000 FCFA',
            'description' => 'Gestion des réseaux sociaux, création de contenus et développement de la présence digitale.',
            'requirements' => 'Maîtrise des réseaux sociaux et des outils de création de contenu.',
            'benefits' => 'Télétravail partiel possible.',
            'deadline' => now()->addDays(20),
            'status' => 'published',
        ]);

        JobOffer::create([
            'user_id' => null,
            'title' => 'Comptable',
            'company' => 'Business Center',
            'category' => 'Finance',
            'location' => 'Cotonou',
            'contract' => 'CDI',
            'salary' => '250 000 - 450 000 FCFA',
            'description' => 'Gestion comptable, suivi financier et préparation des documents administratifs.',
            'requirements' => 'Formation en comptabilité et maîtrise des logiciels comptables.',
            'benefits' => 'Cadre professionnel et possibilités de formation.',
            'deadline' => now()->addDays(28),
            'status' => 'published',
        ]);

        JobOffer::create([
            'user_id' => null,
            'title' => 'Assistant administratif',
            'company' => 'Services Plus',
            'category' => 'Administration',
            'location' => 'Abomey-Calavi',
            'contract' => 'CDD',
            'salary' => '180 000 - 280 000 FCFA',
            'description' => 'Assurer le suivi administratif et l’organisation quotidienne de l’entreprise.',
            'requirements' => 'Organisation, maîtrise de Word et Excel et bonne communication.',
            'benefits' => 'Formation interne.',
            'deadline' => now()->addDays(18),
            'status' => 'published',
        ]);

        JobOffer::create([
            'user_id' => null,
            'title' => 'Graphiste',
            'company' => 'Creative Studio',
            'category' => 'Design',
            'location' => 'Cotonou',
            'contract' => 'Freelance',
            'salary' => 'Selon profil',
            'description' => 'Création de visuels, identité graphique et supports de communication.',
            'requirements' => 'Maîtrise de Photoshop, Illustrator ou outils similaires.',
            'benefits' => 'Missions variées et travail flexible.',
            'deadline' => now()->addDays(15),
            'status' => 'published',
        ]);
    }
}