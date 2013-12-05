<?php

namespace Dbu\ConferenceBundle\Controller;

use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\PropertyType;
use PHPCR\Query\QueryInterface;
use PHPCR\Util\ValueConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScheduleController extends Controller
{
    /**
     * @var DocumentManager
     */
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function scheduleAction($contentDocument)
    {
        $converter = new ValueConverter();
        $now = $converter->convertType(new \DateTime, PropertyType::STRING);

        $rooms = $this->documentManager->createQuery(
            "SELECT u.*
             FROM [nt:unstructured] AS u
             WHERE u.[phpcr:class] = 'Dbu\ConferenceBundle\Document\Room'
               AND (u.publishable = true)
               AND (u.publishStartDate IS NULL OR u.publishStartDate < '$now')
               AND (u.publishEndDate IS NULL OR u.publishEndDate > '$now')
             ORDER BY u.name DESC",
             QueryInterface::JCR_SQL2)->execute();

        return $this->render('DbuConferenceBundle:Schedule:overview.html.twig', array(
            'cmfMainContent' => $contentDocument,
            'rooms' => $rooms,
        ));
    }
}
