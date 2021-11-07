<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Employee;
use App\Entity\EmployeeGroup;
use App\Repository\EmployeeGroupRepository;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeRepositoryTest extends WebTestCase
{
    private const EPOCH_SLUG = 'epoch';

    public function testEpochNotInAll()
    {
        self::bootKernel();
        /** @var EmployeeRepository $repo */
        $repo = self::$container->get(EmployeeRepository::class);
        $epoch = self::$container->get(EmployeeGroupRepository::class)->find(EmployeeGroup::EPOCH_ID);

        $countAll = $repo->count([]);
        self::assertGreaterThan(0, $countAll);

        $employeesMinusEpoch = $repo->findByFilters($countAll, 1, 1,);
        $employeesEpoch = $repo->findByFilters($countAll, 1, 1, $epoch);

        self::assertGreaterThan(0, count($employeesMinusEpoch));
        self::assertGreaterThan(0, count($employeesEpoch));
        $ids = array_map(fn(Employee $em) => $em->getSlug(), $repo->findAll());
        $ids1 = array_map(fn(Employee $em) => $em->getSlug(), $employeesMinusEpoch);
        $ids2 = array_map(fn(Employee $em) => $em->getSlug(), $employeesEpoch);
        $idss = array_merge($ids1, $ids2);

        sort($idss);
        sort($ids);

        self::assertEquals($ids, $idss);

        foreach ($employeesEpoch as $employee) {
            self::assertEquals(self::EPOCH_SLUG, $employee->getEmployeeGroup()->getSlug());
        }

        foreach ($employeesMinusEpoch as $employee) {
            if ($employee->getEmployeeGroup() !== null) {
                self::assertNotEquals(self::EPOCH_SLUG, $employee->getEmployeeGroup()->getSlug());
            }
        }
    }
}
