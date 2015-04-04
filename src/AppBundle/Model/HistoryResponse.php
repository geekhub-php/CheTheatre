<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class HistoryResponse
 * @package AppBundle\Model
 * @ExclusionPolicy("all")
 */
class HistoryResponse extends AbstractPaginatedModel
{
    /**
     * @var Array[]
     * @Type("array<AppBundle\Entity\History>")
     * @Expose
     */
    protected $history;

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
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param  mixed $history
     * @return $this
     */
    public function setHistory($history)
    {
        $this->history = $history;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->getHistory());
    }
}
