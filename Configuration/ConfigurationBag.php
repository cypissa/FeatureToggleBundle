<?php

namespace Cogi\FeatureToggleBundle\Configuration;

use Cogi\FeatureToggleBundle\Entity\Feature;
use Cogi\FeatureToggleBundle\Exception\FeatureDoesntExistException;
use Symfony\Component\DependencyInjection\Container;

/**
 * Feature toggle configuration
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
class ConfigurationBag
{
    protected $features;

    /**
     * Set features from configuration
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $configuration = $container->getParameter('cogi_feature_toggle');
        foreach ($configuration['features'] as $featureName => $attributes) {
            $feature = new Feature(
                $featureName,
                $attributes['likelihood'],
                $attributes['throughout_session'],
                $attributes['users']
            );

            $this->addFeature($feature);
        }
    }

    /**
     * Add feature
     *
     * @param Feature $feature
     */
    protected function addFeature(Feature $feature)
    {
        $this->features[$feature->getName()] = $feature;
    }

    /**
     * Get feature by name
     *
     * @param string $name - name
     *
     * @return Feature
     *
     * @throws FeatureDoesntExistException
     */
    public function getFeature($name)
    {
        if (empty($this->features[$name])) {
            throw new FeatureDoesntExistException(sprintf("There is no feature named '%s'", $name));
        }

        return $this->features[$name];
    }

    /**
     * Get features
     *
     * @return array[Feature]
     */
    public function getFeatures()
    {
        return $this->features;
    }
}