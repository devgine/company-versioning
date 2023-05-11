<?php

namespace App\DataFixtures;

use App\Entity\Company;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $company = new Company();
        $company->setName('Company name');
        $company->setCapital(10000000);
        $company->setRegistrationCity('Paris');
        $company->setRegistrationDate(new DateTime('2018-04-03'));
        $company->setSirenNumber('12345');
        $company->setLegalStatus('legal status');
        $manager->persist($company);

        $manager->flush();
    }
}
