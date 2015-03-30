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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use AppBundle\Model\EmployeesResponse;

/**
 * @RouteResource("Employee")
 * @Cache(smaxage="604800", public=true)
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
     * @QueryParam(name="locale", requirements="^[a-zA-Z]+", default="uk", description="Selects language of data you want ro recieve")
     *
     * @RestView
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $employees = $em
            ->getRepository('AppBundle:Employee')
            ->findBy([], ['lastName' => 'ASC'], $paramFetcher->get('limit'), ($paramFetcher->get('page')-1) * $paramFetcher->get('limit'))
        ;

        if ($paramFetcher->get('locale') !== $paramFetcher->getParams()['locale']->default){
            $employeesTranslated = null;
            foreach ($employees as $employee) {
                $employee->setLocale($paramFetcher->get('locale'));
                $em->refresh($employee);
                $employeesTranslated[] = $employee;
            }
            $employees = $employeesTranslated;
        }

        $employeesResponse = new EmployeesResponse();
        $employeesResponse->setEmployees($employees);
        $employeesResponse->setTotalCount($this->getDoctrine()->getManager()->getRepository('AppBundle:Employee')->getCount());
        $employeesResponse->setPageCount(ceil($employeesResponse->getTotalCount() / $paramFetcher->get('limit')));
        $employeesResponse->setPage($paramFetcher->get('page'));

        $self = $this->generateUrl('get_employees', [
            'limit' => $paramFetcher->get('limit'),
            'page' => $paramFetcher->get('page'),
        ], true
        );

        $first = $this->generateUrl('get_employees', [], true);

        $nextPage = $paramFetcher->get('page') < $employeesResponse->getPageCount() ?
            $this->generateUrl('get_employees', [
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')+1,
            ], true
            ) :
            'false';

        $previsiousPage = $paramFetcher->get('page') > 1 ?
            $this->generateUrl('get_employees', [
                'limit' => $paramFetcher->get('limit'),
                'page' => $paramFetcher->get('page')-1,
            ], true
            ) :
            'false';

        $last = $this->generateUrl('get_employees', [
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
