<?php

namespace Cogi\FeatureToggleBundle\Service;

use Cogi\FeatureToggleBundle\Condition\ConditionInterface;
use Cogi\FeatureToggleBundle\Configuration\ConfigurationBag;
use Cogi\FeatureToggleBundle\Exception\FeatureDoesntExistException;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Toggles features
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
class Toggler
{
    const SESSION_KEY_PREFIX = 'feature_toggle';

    protected $configurationBag;

    protected $session;

    protected $conditions = array();

    /**
     * Init
     *
     * @param ConfigurationBag $configurationBag - configuration bag
     * @param Session          $session          - session container
     */
    public function __construct(ConfigurationBag $configurationBag, Session $session)
    {
        $this->configurationBag = $configurationBag;
        $this->session = $session;
    }

    /**
     * Register new condition
     *
     * @param ConditionInterface $condition - condition
     */
    public function registerCondition(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
    }

    /**
     * Check if the feature is enabled
     *
     * @param string $featureName - feature name
     *
     * @return bool
     */
    public function isEnabled($featureName)
    {
        try {
            $feature = $this->configurationBag->getFeature($featureName);
        } catch (FeatureDoesntExistException $exception) {

            /**
             * If there is no feature on the list, treat as normal code
             */

            return true;
        }

        $sessionKey = sprintf("%s_%s", self::SESSION_KEY_PREFIX, $feature->getName());
        if ($feature->isThroughoutSession()) {
            if ($this->session->has($sessionKey)) {
                return $this->session->get($sessionKey);
            }
        }

        $isPermitted = true;
        foreach ($this->conditions as $condition) {
            $isPermitted = $isPermitted && $condition->isPermitted($feature);
        }

        if ($feature->isThroughoutSession()) {
            $this->session->set($sessionKey, $isPermitted);
        }

        return $isPermitted;
    }
}