<?php

namespace Dbu\ConferenceBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Cmf\Bundle\BlockBundle\Admin\AbstractBlockAdmin;

class RecentlyAddedBlockAdmin extends AbstractBlockAdmin
{
    protected $translationDomain = 'DbuConferenceBundle';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('parentDocument')
            ->addIdentifier('name', 'text')
            ->add('class')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $choices = array(
            'Dbu\ConferenceBundle\Document\Speaker' => 'Speaker',
            'Dbu\ConferenceBundle\Document\Presentation' => 'Presentation',
        );

        $formMapper
            ->with('form.group_general')
                ->add('parentDocument', 'doctrine_phpcr_odm_tree', array('root_node' => $this->getRootPath(), 'choice_list' => array(), 'select_root_node' => true))
                ->add('name', 'text')
                ->add('class', 'choice', array('required' => false, 'choices' => $choices))
                ->add('limit')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', 'doctrine_phpcr_nodename')
            ->add('class')
        ;
    }
}
