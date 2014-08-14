<?php

namespace Cogi\FeatureToggleBundle\Entity;

/**
 * Represents a feature
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
class Feature
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $likelihood;

    /**
     * @var boolean
     */
    protected $throughoutSession;

    /**
     * @var array[string]
     */
    protected $users;

    /**
     * @param string  $name              - name of the feature
     * @param integer $likelihood        - likelihood (integer between 0 and 100)
     * @param boolean $throughoutSession - should be valid for whole session
     * @param array   $users             - list of uses to whom the feature should be available
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($name, $likelihood, $throughoutSession, $users)
    {
        if ($likelihood < 0 || $likelihood > 100) {
            throw new \InvalidArgumentException("The likelihood must be an integer between 0 and 100");
        }

        $this->name = $name;
        $this->likelihood = $likelihood;
        $this->throughoutSession = $throughoutSession;
        $this->users = $users;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get likelihood
     *
     * @return integer
     */
    public function getLikelihood()
    {
        return $this->likelihood;
    }

    /**
     * Get throughout session flag
     *
     * @return boolean
     */
    public function isThroughoutSession()
    {
        return $this->throughoutSession;
    }

    /**
     * Get users list
     *
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }
}