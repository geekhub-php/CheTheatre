<?php

namespace AppBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PriceCategoryAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\PriceCategory';
    protected $baseRoutePattern = 'PriceCategory';

    /**
     * @param FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $performanceEventId = $this->getRequest()->query->get('performanceEvent_id');
        $formMapper
            ->add('venueSector', null, [
                'required' => true,
                'query_builder' => function (EntityRepository $rep) use ($performanceEventId) {
                    $qb = $rep->createQueryBuilder('p');
                    if ($performanceEventId) {
                        $performanceEvent = $this->getConfigurationPool()
                            ->getContainer()->get('doctrine.orm.default_entity_manager')
                            ->getRepository('AppBundle\Entity\PerformanceEvent')
                            ->find($performanceEventId);
                        $venueSectors = $performanceEvent->getVenue()->getVenueSector();
                        foreach ($venueSectors as $sector) {
                            $qb->orWhere("p.id = ".$sector->getId());
                        }
                    }
                    if (($this->getSubject() !== null) && ($performanceEventId === null)) {
                        if ($this->getSubject()->getPerformanceEvent() !== null) {
                            $performanceEvent = $this->getSubject()->getPerformanceEvent();
                            $venueSectors = $performanceEvent->getVenue()->getVenueSector();
                            foreach ($venueSectors as $sector) {
                                $qb->orWhere("p.id = ".$sector->getId());
                            }
                        }
                    }
                    return $qb;
                }
            ])
            ->add('color', 'sonata_type_color_selector', [
                'label' => 'Color'
            ])
            ->add('rows', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => '1-5,6,7,10-15',
                ]
            ])
            ->add('places', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => '1-5,6,7,10-15',
                ]
            ])
            ->add('price', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => '50',
                ]
            ])
            ->add('performanceEvent', null, [
                'required' => true,
                'attr' => ['class' => 'hidden'],
                'label' => false,
                'query_builder' => function (EntityRepository $rep) use ($performanceEventId) {
                    $qb = $rep->createQueryBuilder('p');
                    if ($performanceEventId) {
                        $qb ->where('p.id = :performanceEvent_id')
                            ->setParameter('performanceEvent_id', $performanceEventId);
                    }
                    return $qb;
                }
            ])
        ;
    }
}
