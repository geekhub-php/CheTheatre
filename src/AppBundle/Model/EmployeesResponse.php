<?php

namespace AppBundle\Model;

use AppBundle\Entity\Employee;
use JMS\Serializer\Annotation\Accessor;
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
     * @var Employee[]
     * @Type("array<AppBundle\Entity\Employee>")
     * @Expose
     */
    protected $employees;

    /**
     * @var int
     *
     * @Type("integer")
     * @Accessor(getter="getCount")
     * @Expose
     */
    protected $count;

    /**
     * @return mixed
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * @param  mixed $employees
     * @return $this
     */
    public function setEmployees($employees)
    {
        $this->employees = $employees;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->getEmployees());
    }
}
