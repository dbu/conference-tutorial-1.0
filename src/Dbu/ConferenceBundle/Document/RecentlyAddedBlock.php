<?php

namespace Dbu\ConferenceBundle\Document;

use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\AbstractBlock;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class RecentlyAddedBlock extends AbstractBlock
{
    /**
     * @PHPCR\String(nullable=true)
     */
    private $class;

    /**
     * @PHPCR\Int(nullable=true)
     */
    private $limit;

    public function getType()
    {
        return 'dbu_conference.block.recently_added';
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = array();
        if ($this->class) $options['class'] = $this->class;
        if ($this->limit) $options['limit'] = $this->limit;

        return $options;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }
}
