<?php

namespace AppBundle\Controller;

use AppBundle\Model\Link;
use AppBundle\Model\PaginationLinks;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Model\EmployeesResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @RouteResource("Employee")
 */
class EmployeesController extends Controller
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of theatre employees.",
     *  statusCodes={
     *      200="Returned when all parameters were correct",
     *      404="Returned when the entities with given limit and offset are not found",
     *  },
     *  output = "array<AppBundle\Model\EmployeesResponse>"
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     * @QueryParam(
     *     name="locale",
     *     requirements="^[a-zA-Z]+",
     *     default="uk",
     *     description="Selects language of data you want to receive"
     * )
     *
     * @RestView
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $employees = $em
            ->getRepository('AppBundle:Employee')
            ->findBy(
                [],
                ['lastName' => 'ASC'],
                $paramFetcher->get('limit'),
                ($paramFetcher->get('page')-1) * $paramFetcher->get('limit')
            )
        ;

        $employeesTranslated = array();

        foreach ($employees as $employee) {
            $employee->setLocale($paramFetcher->get('locale'));

            $em->refresh($employee);

            if ($employee->getTranslations()) {
                $employee->unsetTranslations();
            }

            $this->get('translator')->setLocale($paramFetcher->get('locale'));
            $employee->setPosition($this->get('translator')->trans($employee->getPosition()));

            $employeesTranslated[] = $employee;
        }

        $employees = $employeesTranslated;

        $employeesResponse = new EmployeesResponse();
        $employeesResponse->setEmployees($employees);
        $employeesResponse->setTotalCount(
            $this->getDoctrine()->getManager()->getRepository('AppBundle:Employee')->getCount()
        );
        $employeesResponse->setPageCount(ceil($employeesResponse->getTotalCount() / $paramFetcher->get('limit')));
        $employeesResponse->setPage($paramFetcher->get('page'));

        $self = $this->generateUrl(
            'get_employees',
            [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page'),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $first = $this->generateUrl(
            'get_employees',
            [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $nextPage = $paramFetcher->get('page') < $employeesResponse->getPageCount() ?
            $this->generateUrl(
                'get_employees',
                [
                    'locale' => $paramFetcher->get('locale'),
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')+1,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ) :
            'false';

        $previsiousPage = $paramFetcher->get('page') > 1 ?
            $this->generateUrl(
                'get_employees',
                [
                    'locale' => $paramFetcher->get('locale'),
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')-1,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ) :
            'false';

        $last = $this->generateUrl(
            'get_employees',
            [
                'locale' => $paramFetcher->get('locale'),
                'limit' => $paramFetcher->get('limit'),
                'page' => $employeesResponse->getPageCount(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
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
     * @ApiDoc(
     * resource=true,
     *  description="Returns an Employee by unique property {slug}",
     *  statusCodes={
     *      200="Returned when employee by {slug} found in database" ,
     *      404="Returned when employee by {slug} not found in database",
     *  },
     *  output = "AppBundle\Entity\Employee"
     * )
     *
     * @QueryParam(
     *     name="locale",
     *     requirements="^[a-zA-Z]+",
     *     default="uk",
     *     description="Selects language of data you want to receive"
     * )
     *
     * @RestView
     */
    public function getAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $employee = $em->
                        getRepository('AppBundle:Employee')->findOneByslug($slug);

        if (!$employee) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $employee->setLocale($paramFetcher->get('locale'));
        $em->refresh($employee);

        if ($employee->getTranslations()) {
            $employee->unsetTranslations();
        }

        $this->get('translator')->setLocale($paramFetcher->get('locale'));
        $employee->setPosition($this->get('translator')->trans($employee->getPosition()));

        return $employee;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns Employee roles by his slug",
     *  statusCodes={
     *      200="Returned when employee by {slug} found in database" ,
     *      404="Returned when employee by {slug} not found in database",
     *  },
     *  output = "array<AppBundle\Entity\Role>"
     * )
     *
     *  @QueryParam(
     *     name="locale",
     *     requirements="^[a-zA-Z]+",
     *     default="uk",
     *     description="Selects language of data you want to receive"
     * )
     *
     * @RestView
     */
    public function getRolesAction(ParamFetcher $paramFetcher, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $employee = $em
            ->getRepository('AppBundle:Employee')->findOneByslug($slug);

        if (!$employee) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $employee->setLocale($paramFetcher->get('locale'));
        $em->refresh($employee);

        if ($employee->getTranslations()) {
            $employee->unsetTranslations();
        }

        $this->get('translator')->setLocale($paramFetcher->get('locale'));
        $employee->setPosition($this->get('translator')->trans($employee->getPosition()));

        $roles = $employee->getRoles();

        $rolesTranslated = [];

        foreach ($roles as $role) {
            $role->setLocale($paramFetcher->get('locale'));

            $performance = $role->getPerformance();
            $performance->setLocale($paramFetcher->get('locale'));

            $em->refresh($role);
            $em->refresh($performance);

            if ($role->getTranslations()) {
                $role->unsetTranslations();
            }
            if ($performance->getTranslations()) {
                $performance->unsetTranslations();
            }

            $rolesTranslated[] = $role;
        }

        $roles = $rolesTranslated;

        return $roles;
    }
}
