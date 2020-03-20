<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Country extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $json = file_get_contents('./allcountrys.json');

        $countrys = json_decode($json, true);

        foreach ($countrys as $country)
        {
            $saveCountry = new \App\Entity\Country();
            $saveCountry->setName($country['Name']);
            $saveCountry->setTag($country['Code']);
            $manager->persist($saveCountry);
        }

        $manager->flush();
    }
}
