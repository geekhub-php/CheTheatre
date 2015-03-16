<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class EmployeesResponse
 * @package AppBundle\Model
 * @ExclusionPolicy("all")
 */
class EmployeesResponse extends AbstractPaginatedModel
{
    /**
     * @var Array[]
     * @Type("array<AppBundle\Entity\Employee>")
     * @Expose
     */
    protected $employees;

    /**
     * @return mixed
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * @param mixed $employees
     * @return $this
     */
    public function setEmployees($employees)
    {
        $this->employees = $employees;

        return $this;
    }
}
