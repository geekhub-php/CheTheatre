<?php

namespace App\Controller\Api;

use App\Model\Search\SearchResults;
use App\Repository\EmployeeRepository;
use App\Repository\HistoryRepository;
use App\Repository\PerformanceRepository;
use App\Repository\PostRepository;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/search")
 */
class SearchController extends AbstractController
{
    private EmployeeRepository $employeeRepository;
    private PerformanceRepository $performanceRepository;
    private HistoryRepository $historyRepository;
    private PostRepository $postRepository;

    public function __construct(
        EmployeeRepository $employeeRepository,
        PerformanceRepository $performanceRepository,
        HistoryRepository $historyRepository,
        PostRepository $postRepository
    )
    {
        $this->employeeRepository = $employeeRepository;
        $this->performanceRepository = $performanceRepository;
        $this->historyRepository = $historyRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("", name="get_search_query", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns search results by categories",
     *     @Model(type=SearchResults::class)
     * )
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     * @QueryParam(name="q", description="Search query")
     */
    public function searchQuery(ParamFetcher $paramFetcher)
    {
        $result = new SearchResults();

        $result->persons = $this->employeeRepository
            ->search(
                ['firstName', 'lastName'],
                $paramFetcher->get('q')
            );

        $result->performances = $this->performanceRepository
            ->search(
                ['title'],
                $paramFetcher->get('q')
            );

        $result->histories = $this->historyRepository
            ->search(
                ['title'],
                $paramFetcher->get('q')
            );

        $result->posts = $this->postRepository
            ->search(
                ['title'],
                $paramFetcher->get('q')
            );

        return $result;
    }
}
