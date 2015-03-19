<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Pagerfanta;
use AppBundle\Model\EmployeesResponse;
use Pagerfanta\Adapter\ArrayAdapter;

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
     *      200="Returned when all parameters was true",
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
        $queryBuilder = $this->getDoctrine()->getManager()->getRepository('AppBundle:Employee')->findAll();

        $paginater = new Pagerfanta(new ArrayAdapter($queryBuilder));
        $paginater
            ->setMaxPerPage($paramFetcher->get('limit'))
            ->setCurrentPage($paramFetcher->get('page'))
        ;
        $employeesResponse = new EmployeesResponse();
        $employeesResponse->setEmployees($paginater->getCurrentPageResults());
        $employeesResponse->setPageCount($paginater->getNbPages());

        $nextPage = $paginater->hasNextPage() ?
            $this->generateUrl('get_employees', array(
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')+1,
                )
            ) :
            'false';

        $previsiousPage = $paginater->hasPreviousPage() ?
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
     *  description="Returns an Employee by unique property {slug}",
     *  statusCodes={
     *      200="Returned when employee by {slug} found in database" ,
     *      404="Returned when employee by {slug} not found in database",
     *  },
     *  parameters={
     *      {"name"="slug", "dataType"="string", "required"=true, "description"="Unique name for every employee"}
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
     *  description="Returns Employee roles by his slug",
     *  statusCodes={
     *      200="Returned when employee by {slug} found in database" ,
     *      404="Returned when employee by {slug} not found in database",
     *  },
     *  parameters={
     *      {"name"="slug", "dataType"="string", "required"=true, "description"="Unique name for every employee"}
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
