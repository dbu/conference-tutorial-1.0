<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2013 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Dbu\ConferenceBundle\PublishWorkflow\Voter;

use Dbu\ConferenceBundle\Document\Presentation;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableReadInterface;

/**
 * Voter to check if a presentation has any speakers that are published.
 * If there are no published speakers, the presentation is considered to be
 * unpublished.
 *
 * @author David Buchmann <mail@davidbu.ch>
 */
class PresentationVoter implements VoterInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * We need to inject the container as we otherwise end up in a circular
     * reference.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return PublishWorkflowChecker
     */
    private function getPublishWorkflowChecker()
    {
        return $this->container->get('cmf_core.publish_workflow.checker');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        return PublishWorkflowChecker::VIEW_ATTRIBUTE === $attribute
            || PublishWorkflowChecker::VIEW_ANONYMOUS_ATTRIBUTE === $attribute
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        // subclass, also work with doctrine proxies
        return is_subclass_of($class, 'Dbu\ConferenceBundle\Document\Presentation');
    }

    /**
     * {@inheritdoc}
     *
     * @param Presentation $object
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass(get_class($object))) {
            return self::ACCESS_ABSTAIN;
        }

        $checker = $this->getPublishWorkflowChecker();
        foreach ($object->getSpeakers() as $speaker) {
            if ($checker->isGranted($attributes, $speaker)) {
                return self::ACCESS_GRANTED;
            }
        }

        return self::ACCESS_DENIED;
    }
}
