<?php

namespace App\Model\Search;

use App\Entity\Employee;
use App\Entity\History;
use App\Entity\Performance;
use App\Entity\Post;
use JMS\Serializer\Annotation as Serializer;

class SearchResults
{
    /**
     * @var Employee[]
     * @Serializer\Type("array<App\Entity\Employee>")
     */
    public array $persons;

    /**
     * @var Performance[]
     * @Serializer\Type("array<App\Entity\Performance>")
     */
    public array $performances;

    /**
     * @var History[]
     * @Serializer\Type("array<App\Entity\History>")
     */
    public array $histories;

    /**
     * @var Post[]
     * @Serializer\Type("array<App\Entity\Post>")
     */
    public array $posts;
}
