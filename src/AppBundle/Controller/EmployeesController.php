<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

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
     *      404="Returned when the entity is not found",
     *  },
     *  output = "array<AppBundle\Entity\Employee>"
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count entries at one page")
     * @QueryParam(name="offset", requirements="\d+", default="1", description="Count entries for offset")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $queryBuilder = $this->getDoctrine()->getManager()->getRepository('AppBundle:Employee')->createQueryBuilder('e')->getQuery();

        $paginater = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $paginater
            ->setMaxPerPage($paramFetcher->get('limit'))
            ->setCurrentPage($paramFetcher->get('offset'))
        ;

        return $paginater->getCurrentPageResults()->getArrayCopy();
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns an Employee by slug",
     *
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the entity is not found",
     *         }
     *     },
     *  parameters={
     *      {"name"="Slug", "dataType"="string", "required"=true, "description"="Employee slug"}
     *  },
     * output = { "class" = "AppBundle\Entity\Employee" }
     * )
     *
     * @return Response
     *
     * @RestView
     */
    public function getAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $employee = $em->getRepository('AppBundle:Employee')->findOneByslug($slug);

        if (!$employee) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $restView = View::create();
        $restView
            ->setData($employee)
            ->setHeaders(array(
                "Content-Type" => "application/json",
                "Location" => $this->generateUrl('get_employees').'/'.$employee->getSlug()
                )
            )
        ;
        return $restView;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Returns an Employee by slug and his roles",
     *
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the entity is not found",
     *         }
     *     },
     *  parameters={
     *      {"name"="Slug", "dataType"="string", "required"=true, "description"="Employee slug"}
     *  },
     * output = { "class" = "AppBundle\Entity\Role", "collection" = true, "collectionName" = "Roles" }
     * )
     *
     * @return Response
     *
     * @RestView
     */
    public function getRolesAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $employee = $em->getRepository('AppBundle:Employee')->findOneByslug($slug);

        if (!$employee) {
            throw $this->createNotFoundException('Unable to find '.$slug.' entity');
        }

        $roles = $employee->getRoles();

        $restView = View::create();
        $restView
            ->setData($roles)
            ->setHeaders(array(
                "Content-Type" => "application/json",
                "Location" => $this->generateUrl('get_employees').'/'.$employee->getSlug().'/roles'
                )
            )
        ;
        return $restView;
    }
}
