<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Link;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getLinkData() as [$url, $title, $description]) {
            $link = new Link();
            $link->setTitle($title);
            $link->setUrl($url);
            $link->setDescription($description);
            $link->setCreatedAt(new \DateTime());
            $link->setUpdatedAt(new \DateTime());

            $manager->persist($link);
            $manager->flush();
        }
    }
     private  function getLinkData(): array
        {
            return [
                // $link = [$url, $title, $description];
                ['https://www.symfony.com', 'Symfony, High Performance PHP Framework for Web', 'Symfony is a set of reusable PHP components and a PHP framework to build web applications, APIs, microservices and web services'],
                ['https://github.com', 'The world\'s leading software development platform', 'GitHub brings together the world\'s largest community of developers to discover, share, and build better software. From open source projects to private team ...'],
                ['https://twitter.com', 'Twitter. It\'s what\'s happening.', 'From breaking news and entertainment to sports and politics, get the full story with all the live commentary.'],
            ];

        }
    }
