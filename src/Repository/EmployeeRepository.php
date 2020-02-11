<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Common\Persistence\ManagerRegistry;

class EmployeeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }
}
