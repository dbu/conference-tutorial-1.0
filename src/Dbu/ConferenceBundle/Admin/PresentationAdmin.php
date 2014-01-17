<?php

namespace Dbu\ConferenceBundle\Admin;

use Dbu\ConferenceBundle\Document\Speaker;
use Doctrine\Bundle\PHPCRBundle\Form\DataTransformer\ReferenceManyCollectionToArrayTransformer;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Admin\PageAdmin;

class PresentationAdmin extends PageAdmin
{
    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('speakers')
            ->add('parent')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('form.group_general')
                ->add(
                    'parent',
                    'phpcr_document',
                    array(
                        'choices' => $this->getRooms(),
                        'class' => 'Dbu\ConferenceBundle\Document\Room',
                        'property' => 'title',
                        'label' => 'form.label_presentation_room'
                ))
                ->add(
                    'speakers',
                    'phpcr_document',
                    array(
                        'property' => 'title',
                        'class' => 'Dbu\ConferenceBundle\Document\Speaker',
                        'multiple' => true,
                        'translation_domain' => 'DbuConferenceBundle',
                    )
                )
            ->end()
        ;

        $formMapper
            ->remove('addFormatPattern')
            ->remove('addTrailingSlash')
            ->remove('addLocalePattern')
            ->remove('label')
            ->remove('createDate')
        ;
    }

    private function getRooms()
    {
        return $this->getModelManager()->getDocumentManager()->find(null, $this->getRootPath())->getChildren();
    }
}
