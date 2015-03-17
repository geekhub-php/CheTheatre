<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Model\EmployeesResponse;

/**
 * @RouteResource("Employee")
 */
class EmployeesController extends Controller
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Employees",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the entities with given limit and offset are not found",
     *  },
     *  output = "array<AppBundle\Model\EmployeesResponse>"
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $employees = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Employee')
            ->findBy([], null, $paramFetcher->get('limit'), ($paramFetcher->get('page')-1) * $paramFetcher->get('limit'));

        $employeesResponse = new EmployeesResponse();
        $employeesResponse->setEmployees($employees);
        $employeesResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('AppBundle:Employee')->getCount());
        $employeesResponse->setPageCount(ceil($employeesResponse->getTotalCount() / $paramFetcher->get('limit')));

        $nextPage = $paramFetcher->get('page') < $employeesResponse->getPageCount() ?
            $this->generateUrl('get_employees', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')+1,
                )
            ) :
            'false';

        $previsiousPage = $paramFetcher->get('page') > 1 ?
            $this->generateUrl('get_employees', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')-1,
                )
            ) :
            'false';

        $employeesResponse->setNextPage($nextPage);
        $employeesResponse->setPreviousPage($previsiousPage);

        return $employeesResponse;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns an Employee by slug",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the entity is not found",
     *  },
     *  parameters={
     *      {"name"="slug", "dataType"="string", "required"=true, "description"="Employee slug"}
     *  },
     *  output = "AppBundle\Entity\Employee"
     * )
     *
     * @RestView
     */
    public function getAction($slug)
    {
        $employee = $this->getDoctrine()->getManager()->
            getRepository('AppBundle:Employee')->findOneByslug($slug);

        if (!$employee) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        return $employee;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns an Employee by slug and his roles",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the entity is not found",
     *  },
     *  parameters={
     *      {"name"="slug", "dataType"="string", "required"=true, "description"="Employee slug"}
     *  },
     *  output = "array<AppBundle\Entity\Role>"
     * )
     *
     * @RestView
     */
    public function getRolesAction($slug)
    {
        $employee = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Employee')->findOneByslug($slug);

        if (!$employee) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $roles = $employee->getRoles();

        return $roles;
    }
}
