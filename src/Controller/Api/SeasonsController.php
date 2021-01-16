<?php

namespace App\Controller\Api;

use App\Entity\RepertoireSeason;
use App\Entity\Performance;
use App\Repository\RepertoireSeasonRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/seasons")
 */
class SeasonsController extends AbstractController
{
    protected $seasonRepository;
    protected $serializer;

    public function __construct(RepertoireSeasonRepository $seasonRepository, SerializerInterface $serializer)
    {
        $this->seasonRepository = $seasonRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("", name="get_seasons", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns when all parameters were correct",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=RepertoireSeason::class))
     *     )
     * )
     */
    public function index()
    {
        $seasons = $this->seasonRepository->findAllNotEmpty();
        return $seasons;
    }

    /**
     * @Route("/{number}/performances", name="get_season_performances", methods={"GET"})
     * @Entity("season", expr="repository.findOneByNumber(number)")
     * @SWG\Parameter(
     *     name="number",
     *     in="path",
     *     type="number",
     *     description="Season number. -2 for all performances for not active seasons, -1 for all performances for all seasons, 0 for most recent season"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns performances for choosen season",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Performance::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when season number does not exists",
     * )
     * @Rest\QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     * @Rest\QueryParam(name="audience", requirements="adults|kids", description="Filter by given audience")
     */
    public function getSeasonPerformances(RepertoireSeason $season, ParamFetcher $paramFetcher)
    {
        $performances = $this->getPerformancesFilteredByAudience($paramFetcher, $season);
        $em = $this->getDoctrine()->getManager();
        $performancesTranslated = [];

        foreach ($performances as $performance) {
            $performance->setLocale($paramFetcher->get('locale'));
            $em->refresh($performance);

            if ($performance->getTranslations()) {
                $performance->unsetTranslations();
            }

            $performancesTranslated[] = $performance;
        }

        return $performancesTranslated;
    }

    /**
     * This is a hack.
     * It probably should be filtered in db query
     */
    private function getPerformancesFilteredByAudience(ParamFetcher $paramFetcher, RepertoireSeason $season): array
    {
        if ('' === $paramFetcher->get('audience')) {
            $performances = $season->getPerformances()->toArray();
        } else {
            $performances = $season->getPerformances()
                ->filter(function (Performance $performance) use ($paramFetcher) {
                    return $paramFetcher->get('audience') === $performance->getAudience();
                })
                ->toArray();
        }
        return $performances;
    }
}
