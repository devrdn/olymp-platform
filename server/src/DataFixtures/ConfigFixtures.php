<?php

namespace App\DataFixtures;

use App\Entity\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // default theme
        $defaultThemeConfig = new Config();
        $defaultThemeConfig->setName("theme");
        $defaultThemeConfig->setValue("default");
        
        $manager->persist($defaultThemeConfig);

        // items per page
        $itemsPerPageConfig = new Config();
        $itemsPerPageConfig->setName("items_per_page");
        $itemsPerPageConfig->setValue("20");
        
        $manager->persist($itemsPerPageConfig);

        // site title
        $siteTitleConfig = new Config();
        $siteTitleConfig->setName("site_title");
        $siteTitleConfig->setValue("Olymp");
        
        $manager->persist($siteTitleConfig);

        $manager->flush();
    }
}
