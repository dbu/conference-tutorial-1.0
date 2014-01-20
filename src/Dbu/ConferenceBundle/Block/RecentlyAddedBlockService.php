<?php

namespace Dbu\ConferenceBundle\Block;

use Dbu\ConferenceBundle\Document\RecentlyAddedBlock;
use PHPCR\PropertyType;
use PHPCR\Query\QueryInterface;
use PHPCR\Util\ValueConverter;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ODM\PHPCR\DocumentManager;

class RecentlyAddedBlockService extends BaseBlockService
{
    /**
     * @var DocumentManager
     */
    private $documentManager;

    public function setDocumentManager(DocumentManager $dm)
    {
        $this->documentManager = $dm;
    }

    // ...
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'Dbu\ConferenceBundle\Document\Presentation',
            'limit' => 3,
        ));
    }
    // ...

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        /** @var $block RecentlyAddedBlock */
        $block = $blockContext->getBlock();

        if (!$block->getEnabled()) {
            return new Response();
        }

        $settings = $blockContext->getSettings();
        $resolver = new OptionsResolver();
        $resolver->setDefaults($settings);
        $settings = $resolver->resolve($block->getOptions());

        $converter = new ValueConverter();
        $now = $converter->convertType(new \DateTime, PropertyType::STRING);

        $items = $this->documentManager->createQuery(
            "SELECT u.*
             FROM [nt:unstructured] AS u
             WHERE u.[phpcr:class] = " . $settings['class'] . "
               AND (u.publishable = true)
               AND (u.publishStartDate IS NULL OR u.publishStartDate < '$now')
               AND (u.publishEndDate IS NULL OR u.publishEndDate > '$now')
             ORDER BY u.publishStartDate DESC
             ",
            QueryInterface::JCR_SQL2)
            ->setMaxResults($settings['limit'])
            ->execute()
        ;

        return $this->renderResponse('DbuConferenceBundle:Block:recently_added.html.twig',
            array(
                'items' => $items,
            ),
            $response
        );
    }

    // these methods are required by the sonata block service interface.
    // we do not use them in the cmf. to edit, create a sonata admin or
    // something else that builds a form and collects the data.

    public function buildEditForm(FormMapper $form, BlockInterface $block)
    {
        throw new \Exception('this is not supposed to be called');
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        throw new \Exception('this is not supposed to be called');
    }
}
