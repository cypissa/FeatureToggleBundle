<?php

namespace Cogi\FeatureToggleBundle\Condition;

use Cogi\FeatureToggleBundle\Entity\Feature;

/**
 * Likelihood condition
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
class LikelihoodCondition implements ConditionInterface
{
    /**
     * Init
     *
     */
    public function __construct()
    {
        mt_srand();
    }

    /**
     * Check if the feature should be enabled in the request by likelihood
     *
     * @param Feature $feature - feature
     *
     * @return boolean
     */
    public function isPermitted(Feature $feature)
    {
        $likelihood = $feature->getLikelihood();

        $randomNumber = $this->getRandomNumber();

        if ($randomNumber <= $likelihood) {
            return true;
        }

        return false;
    }

    /**
     * Get random number between 0 and 100
     *
     * @return integer
     */
    protected function getRandomNumber()
    {
        return mt_rand(0, 100);
    }
}
