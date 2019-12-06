<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Critic;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");
        $faker->addProvider(new \Metrakit\EddyMalou\EddyMalouProvider($faker));
        $faker->addProvider(new \Metrakit\EddyMalou\TextProvider($faker));

        $gta = new Game;

        $status = [Critic::STATUS_PENDING, Critic::STATUS_REJECTED, Critic::STATUS_VALIDATE];

        $gta
            ->setDescription("Jeu d'action-aventure en monde ouvert, Grand Theft Auto (GTA) V  vous place dans la peau de trois personnages inédits : Michael, Trevor et Franklin. Ces derniers ont élu domicile à Los Santos, ville de la région de San Andreas. Braquages et missions font partie du quotidien du joueur qui pourra également cohabiter avec 29 autres utilisateurs dans cet univers persistant.")
            ->setTitle("Grand Theft Auto V")
            ->setImage("http://image.jeuxvideo.com/images-sm/jaquettes/00034710/jaquette-grand-theft-auto-v-pc-cover-avant-g-1415122060.jpg")
            ->setPrice(2999)
            ->setPublishingHouse("Rockstar Games")
            ->setReleaseDate("2015");
        $manager->persist($gta);

        $wakfu = new Game;

        $wakfu
            ->setDescription("Jeu de rôle en ligne massivement multijoueur, Wakfu est reconnaissable grâce à son design coloré et inspiré des univers manga. Créez votre personnage, choisissez-lui une classe parmi les seize proposées et lancez-vous dans une aventure épique et fantastique. Dans un monde en 3D isométrique, parcourez les îles de Wakfu, apprenez à vous battre, à maîtriser l'art de la magie et découvrez une communauté qui s'étend au monde entier.")
            ->setTitle("Wakfu")
            ->setImage("http://image.jeuxvideo.com/images-sm/pc/w/a/wafupc0f.jpg")
            ->setPrice(0)
            ->setPublishingHouse("Ankama")
            ->setReleaseDate("2012");
        $manager->persist($wakfu);

        $users = [];
        $wow = new Game;

        $wow
            ->setDescription("Jeu de rôle en ligne massivement multijoueur, World of Warcraft vous transporte dans le monde heroic-fantasy d'Azeroth où la guerre fait rage entre la Horde et l'Alliance. Incarnez les nombreuses races du jeu (Humains, Taurens, Elfes de la nuit et bien d'autres), et faites votre choix parmi les nombreuses classes disponibles afin d'aller vous frotter au contenu astronomique du jeu et de ses extensions. Fabriquer armes et armures, affronter des hordes de monstres et de démons seul ou en groupe, constituer des guildes, et tellement plus encore, voilà ce qui vous attend dans WoW.")
            ->setTitle("World Of Warcraft")
            ->setImage("http://image.jeuxvideo.com/images-sm/pc/w/o/wowapc0f.jpg")
            ->setPrice(1199)
            ->setPublishingHouse("Blizzard Entertainment")
            ->setReleaseDate("2005");
        $manager->persist($wow);

        $admin = new User;
        $games = [$gta, $wakfu, $wow];

        $admin
            ->setEmail("admin@gmail.com")
            ->setFullName("Perez Nicolas")
            ->setRoles(["ROLE_ADMIN"])
            ->setUserName("Kaporal Hunter")
            ->setPassword($this->encoder->encodePassword($admin, "password"))
            ->setAvatar("https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/6f/6f28658f811eeeedb92338ad421330a921991262_full.jpg");
        $manager->persist($admin);

        $users[] = $admin;

        for ($u = 0; $u < 6; $u++) {
            $user = new User;

            $user->setAvatar($faker->imageUrl(400, 400))
                ->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($this->encoder->encodePassword($user, "password"))
                ->setUserName($faker->userName);

            $manager->persist($user);

            $users[] = $user;


            for ($c = 0; $c < \mt_rand(0, 3); $c++) {
                $critic = new Critic;

                $critic->setCreatedAt($faker->dateTimeThisYear())
                    ->setAuthor($faker->randomElement($users))
                    ->setDescription($faker->paragraph(1))
                    ->setGame($faker->randomElement($games))
                    ->setScore(\mt_rand(1, 20))
                    ->setStatus($faker->randomElement($status))
                    ->setContent($faker->paragraph(6));
                $manager->persist($critic);

                for ($co = 0; $co < \mt_rand(0, 6); $co++) {
                    $comment = new Comment;

                    $comment->setAuthor($faker->randomElement($users))
                        ->setContent($faker->paragraph(\mt_rand(1, 4)))
                        ->setCreatedAt($faker->dateTimeThisMonth())
                        ->setCritic($critic);

                    $manager->persist($comment);
                }
            }
        }


        $manager->flush();
    }
}
