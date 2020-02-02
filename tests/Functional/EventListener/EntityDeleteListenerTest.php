<?php

namespace App\Tests\Functional\EventListener;

use App\Entity\Employee;
use App\Entity\Performance;
use App\Repository\EmployeeRepository;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\User;

class EntityDeleteListenerTest extends WebTestCase
{
    protected $user;
    /** @var EntityManager */
    protected $em;

    public function setUp()
    {
        static::bootKernel();
        $container = self::$container;
        $this->em = $container->get(EntityManagerInterface::class);

        // login
        $tokenStorage = $container->get(TokenStorageInterface::class);
        $firewallName = 'secure_area';
        $this->user = new User('admin', '123456');
        $token = new UsernamePasswordToken($this->user, null, $firewallName, ['ROLE_ADMIN']);
        $tokenStorage->setToken($token);
    }
    
    public function testEmployeeSoftDeletable()
    {
        $container = self::$container;
        $employeeRepository = $container->get(EmployeeRepository::class);

        /** @var Employee $employee */
        $employee = $employeeRepository->findOneBy([]);
        $this->assertEquals(Employee::class, get_class($employee));
        $this->assertNull($employee->getDeletedAt());
        $this->assertNull($employee->getDeletedBy());

        $this->em->remove($employee);
        $this->em->flush();

        $this->assertNotNull($employee->getDeletedAt());
        $this->assertEquals($this->user->getUsername(), $employee->getDeletedBy());
    }

    public function testCascadeDeleteRoles()
    {
        $container = self::$container;
        $performanceRepository = $container->get(PerformanceRepository::class);
        /** @var Performance $performance */
        $performance = $performanceRepository->findOneBy([]);

        $this->assertEquals(Performance::class, get_class($performance));
        $this->assertNull($performance->getDeletedAt());
        $this->assertNull($performance->getDeletedBy());

        $this->em->remove($performance);
        $this->em->flush();

        $this->assertNotNull($performance->getDeletedAt());
        $this->assertEquals($this->user->getUsername(), $performance->getDeletedBy());

        $this->assertNotNull($performance->getRoles()[0]->getDeletedAt());
        $this->assertEquals($this->user->getUsername(), $performance->getRoles()[0]->getDeletedBy());
    }
}
