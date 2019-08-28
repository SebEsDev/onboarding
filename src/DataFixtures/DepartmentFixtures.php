<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DepartmentFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {

        $listDepartment = ['Développement', 'Ressources Humaines', 'Direction', 'Commercial', 'Technologique'];
        $listMail = ['dev@onboarding.fr', 'rh@onboarding.fr', 'dir@onboarding.fr', 'com@onboarding.fr', 'tech@onboarding.fr'];
        $index = 0;
        foreach ($listDepartment as $nameDepartment) {
            $department = new Department();
            $department->setName($nameDepartment);
            $department->setMail($listMail[$index]);
            $em->persist($department);
            $index++;
        }

        $em->flush();
    }
}