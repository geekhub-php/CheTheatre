<?php

namespace AppBundle\Admin;

use AppBundle\Entity\User;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class UserAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\User';
    protected $baseRoutePattern = 'User';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'id',
    ];

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('User', ['class' => 'col-lg-12'])
            ->add('firstName')
            ->add('lastName')
             ->add('email')
            ->add('orders')
            ->end()
        ;
    }

    /**
     * {@inheritdoc} annotation.
     *
     * @param string $context = 'list'
     *
     * @return \Sonata\AdminBundle\Datagrid\ProxyQueryInterface|ProxyQuery
     */
    public function createQuery($context = 'list')
    {
        $em = $this->modelManager->getEntityManager('AppBundle:User');
        $queryBuilder = $em
            ->createQueryBuilder('u')
            ->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.email IS NOT NULL ')
            ->andWhere('u.role = :param')
        ->setParameter('param', 'ROLE_API');
        $query = new ProxyQuery($queryBuilder);

        return $query;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('orders')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email')
        ;
    }

    public function toString($object)
    {
        return $object instanceof User
            ? $object->getEmail()
            : 'User'; // shown in the breadcrumb on the create views
    }
}
