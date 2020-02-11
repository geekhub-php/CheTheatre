<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Entity\Performance;
use App\Model\Link;
use App\Entity\Role;
use App\Model\PaginationLinks;
use App\Model\EmployeesResponse;
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
    private $translator;
    private $serializer;

    public function __construct(
        TranslatorInterface $translator,
        SerializerInterface $serializer
    ) {
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("", name="get_employees", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns a collection of theatre employees.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=EmployeesResponse::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when the entities with given limit and offset are not found",
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want to receive")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $employees = $em
            ->getRepository('App:Employee')
            ->findBy([], ['lastName' => 'ASC'], $paramFetcher->get('limit'), ($paramFetcher->get('page')-1) * $paramFetcher->get('limit'))
        ;

        $employeesTranslated = array();

        foreach ($employees as $employee) {
            $employee->setLocale($paramFetcher->get('locale'));

            $em->refresh($employee);

            if ($employee->getTranslations()) {
                $employee->unsetTranslations();
            }

            $this->translator->setLocale($paramFetcher->get('locale'));
            $employee->setPosition($this->translator->trans($employee->getPosition()));

            $employeesTranslated[] = $employee;
        }

        $employees = $employeesTranslated;

        $employeesResponse = new EmployeesResponse();
        $employeesResponse->setEmployees($employees);
        $employeesResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('App:Employee')->getCount());
        $employeesResponse->setPageCount(ceil($employeesResponse->getTotalCount() / $paramFetcher->get('limit')));
        $employeesResponse->setPage($paramFetcher->get('page'));

        $self = $this->generateUrl('get_employees', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
            'page' => $paramFetcher->get('page'),
        ], true
        );

        $first = $this->generateUrl('get_employees', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
        ], true
        );

        $nextPage = $paramFetcher->get('page') < $employeesResponse->getPageCount() ?
            $this->generateUrl('get_employees', [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')+1,
            ], true
            ) :
            'false';

        $previsiousPage = $paramFetcher->get('page') > 1 ?
            $this->generateUrl('get_employees', [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')-1,
            ], true
            ) :
            'false';

        $last = $this->generateUrl('get_employees', [
            'locale' => $paramFetcher->get('locale'),
            'limit' => $paramFetcher->get('limit'),
            'page' => $employeesResponse->getPageCount(),
        ], true
        );

        $links = new PaginationLinks();

        $employeesResponse->setLinks($links->setSelf(new Link($self)));
        $employeesResponse->setLinks($links->setFirst(new Link($first)));
        $employeesResponse->setLinks($links->setNext(new Link($nextPage)));
        $employeesResponse->setLinks($links->setPrev(new Link($previsiousPage)));
        $employeesResponse->setLinks($links->setLast(new Link($last)));

        return $employeesResponse;
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
