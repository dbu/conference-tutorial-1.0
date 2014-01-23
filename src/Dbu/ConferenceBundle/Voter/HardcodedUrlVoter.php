<?php

namespace Dbu\ConferenceBundle\Voter;

use Knp\Menu\ItemInterface;
use Symfony\Cmf\Bundle\MenuBundle\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * A content type class that make a fixed URL highlighted if the content is of
 * a specific class.
 */
class HardcodedUrlVoter implements VoterInterface
{
    private $requestKey;
    private $request;
    private $class;
    private $url;

    /**
     * @param string $requestKey The key to look up the content in the request
     *                           attributes
     * @param string $class      Fully qualified class name of the content
     *                           class in the request to highlight this url.
     * @param string $url        Part of the URL the menu item must have to be
     *                           the current item if the content has this
     *                           class.
     */
    public function __construct($requestKey, $class, $url)
    {
        $this->requestKey = $requestKey;
        $this->class = $class;
        $this->url = $url;
    }

    public function setRequest(Request $request = null)
    {
        if ($this->request) {
            return;
        }
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     */
    public function matchItem(ItemInterface $item = null)
    {
        if (! $this->request) {
            // according to error logs, this can happen
            return null;
        }
        if ($this->request->attributes->has($this->requestKey)
            && $this->request->attributes->get($this->requestKey) instanceof $this->class
            && strpos($item->getUri(), $this->url) !== false
        ) {
            return true;
        }

        return null;
    }
}
