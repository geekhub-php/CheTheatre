<?php

namespace AppBundle\Repository;

class EmployeeRepository extends AbstractRepository
{
    public function findWithSortAllEmployees($order, $limit, $offset)
    {
        $employees = $this->createQueryBuilder('e')
                         ->select()
                         ->orderBy('e.lastName', $order)
                         ->getQuery()
                         ->execute();

        return array_slice($employees, $offset, $limit);
    }
}
