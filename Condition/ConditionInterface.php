<?php

namespace Cogi\FeatureToggleBundle\Condition;

use Cogi\FeatureToggleBundle\Entity\Feature;

/**
 * Condition interface
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
interface ConditionInterface
{
    /**
     * Is specified feature permitted for this condition
     *
     * @param Feature $feature - feature object
     *
     * @return boolean
     */
    public function isPermitted(Feature $feature);
}