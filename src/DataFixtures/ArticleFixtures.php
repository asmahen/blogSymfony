<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categroy;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // créer 3 catégories fakées
        $faker= \Faker\Factory ::create('fr_FR');
        for ($i=1; $i<=3; $i++){
            $category = new Categroy();
            $category->setTitle($faker->sentence)
                     ->setDescription($faker->paragraph());
            $manager->persist($category);

            // créer entre 5/6 articles

            for ($j=1; $j <= mt_rand(4, 6); $j++) {
                $article= new Article();

                // pour afficher en html le contenu du tableau paragraphs de faker
                $content = '<p>'. join($faker->paragraphs(5), '</p><p>'). '</p>';

                // paramettre l'article
                $article->setTitle($faker->sentence)
                    ->setContent($content)
                    ->setImage('https://via.placeholder.com/350x150')
                    ->setCreatedAt($faker->dateTimeBetween('- 6 months'))
                    ->setCategroy($category);

                $manager->persist($article);

                //pour créer les commentaires d'un article
                for ($k=1; $k<= mt_rand(4, 10); $k++){
                    $comment= new Comment();

                    // pour afficher en html le contenu du tableau paragraphs de faker
                    $content = '<p>'. join($faker->paragraphs(2), '</p><p>'). '</p>';

                    // pour afficher une date de création de commentaire par rapport à la date de création de l'article
                    $now= new \dateTime();
                    $interval= $now->diff($article->getCreatedAt());
                    $days= $interval->days;
                    $minimum= '-'.$days. 'days';

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist(($comment));
                }
            }
        }



        $manager->flush();
    }
}