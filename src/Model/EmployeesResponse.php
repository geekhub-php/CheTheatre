<?php

namespace App\Model;

use App\Entity\Employee;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class EmployeesResponse
 * @package App\Model
 * @ExclusionPolicy("all")
 */
class EmployeesResponse extends AbstractPaginatedModel
{
    /**
     * @var Employee[]
     * @Type("array<App\Entity\Employee>")
     * @Expose
     */
    public $employees;

    /**
     * @Type("integer")
     * @Accessor(getter="getCount")
     * @Expose
     */
    public int $count;

    /**
     * @Type("integer")
     * @Expose
     */
    public int $currentPage;

    /**
     * @Type("integer")
     * @Expose
     */
    public int $overAllCount;

    /**
     * @Type("integer")
     * @Expose
     */
    public int $seed;

    /**
     * @Type("bool")
     * @Expose
     */
    public bool $rand;

    public function getCount(): int
    {
        return count($this->employees);
    }
}
