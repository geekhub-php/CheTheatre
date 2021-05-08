<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Entity\EmployeeGroup;
use App\Entity\Performance;
use App\Entity\Role;
use App\Model\EmployeesResponse;
use App\Model\PerformancesResponse;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/employees")
 */
class EmployeesController extends AbstractController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/groups", name="get_employees_groups", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns a collection of theatre employees.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=EmployeeGroup::class))
     *     )
     * )
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getEmployeeGroups()
    {
        $employeeGroups = $this->getDoctrine()
            ->getManager()
            ->getRepository(EmployeeGroup::class)
            ->findBy([], ["position" => "ASC"]);

        return $employeeGroups;
    }

    /**
     * @Route("", name="get_employees", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns a collection of theatre employees.",
     *     @SWG\Schema(ref=@Model(type=EmployeesResponse::class))
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when the entities with given limit and offset are not found",
     * )
     *
     * @QueryParam(name="limit", requirements="\d+|all", default="all", description="Count entries at one page")
     * @QueryParam(name="random", requirements="\d+", default=0, description="Should we suffle the order. Use seed in response to keep the same order")
     * @QueryParam(name="seed", requirements="\d+", default=0, description="Ignored if random is 1")
     * @QueryParam(name="page", requirements="\d+|middle", default="1", description="Number of page to be shown or 'middle' for middle page")
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     * @QueryParam(name="group", requirements="^[a-zA-Z]+", description="Group to filter employees")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $paramFetcher->get('page');
        $overAllCount = $em->getRepository('App:Employee')->count([]);
        $limit = $paramFetcher->get('limit', $strict = true) == "all"
            ? $overAllCount
            : $paramFetcher->get('limit');

        $rand = 0 != $paramFetcher->get('random');
        $seed = $paramFetcher->get('seed');
        if ($rand) {
            $seed = rand(1, 1000);
        }
        if ('middle' == $page) {
            $page = round($overAllCount/$limit/2);
        }

        $group = null;
        if ($groupSlug = $paramFetcher->get('group')) {
            $group = $em->getRepository(EmployeeGroup::class)
                ->findOneBy(['slug' => $groupSlug]);
        }

        $employeesTranslated = $em->getRepository('App:Employee')
            ->rand($limit, $page, $seed, $paramFetcher->get('locale'), $group);

        $response = new EmployeesResponse();
        $response->employees = $employeesTranslated;
        $response->currentPage = $page;
        $response->overAllCount = $overAllCount;
        $response->seed = $seed;
        $response->rand = $rand;

        return $response;
    }

    /**
     * @Route("/{slug}", name="get_employee", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns an Employee by unique property {slug}",
     *     @Model(type=Employee::class)
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when employee by {slug} not found in database",
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Employee $employee */
        $employee = $em->getRepository('App:Employee')->findOneByslug($slug);

        if (!$employee) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $employee->setLocale($paramFetcher->get('locale'));
        $em->refresh($employee);

        if ($employee->getTranslations()) {
            $employee->unsetTranslations();
        }

        $this->translator->setLocale($paramFetcher->get('locale'));
        $employee->setPosition($this->translator->trans($employee->getPosition()));

        return $employee;
    }

    /**
     * @Route("/{slug}/roles", name="get_employee_roles", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns employee roles by employee {slug}",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Role::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returns when employee by {slug} not found in database",
     * )
     *
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function getRolesAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Employee $employee */
        $employee = $em->getRepository('App:Employee')->findOneByslug($slug);

        if (!$employee) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $employee->setLocale($paramFetcher->get('locale'));
        $em->refresh($employee);

        $employee->unsetTranslations();

        $this->translator->setLocale($paramFetcher->get('locale'));
        $employee->setPosition($this->translator->trans($employee->getPosition()));

        $roles = $employee->getRoles();

        $rolesTranslated = [];

        foreach ($roles as $role) {
            /** @var Performance $performance */
            $performance = $role->getPerformance();

            $role->setLocale($paramFetcher->get('locale'));
            $em->refresh($role);
            $performance->setLocale($paramFetcher->get('locale'));
            $em->refresh($performance);

            $role->unsetTranslations();
            $performance->unsetTranslations();

            $rolesTranslated[] = $role;
        }

        $roles = $rolesTranslated;

        return $roles;
    }
}
