<?php

namespace Cogi\FeatureToggleBundle\Twig;

use Cogi\FeatureToggleBundle\Service\Toggler;

/**
 * Toggle twig extension
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
class TogglerExtension extends \Twig_Extension
{
    protected $toggler;

    /**
     * @param Toggler $toggler - toggler
     */
    public function __construct(Toggler $toggler)
    {
        $this->toggler = $toggler;
    }

    /**
     * Get available functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'is_feature_enabled' => new \Twig_SimpleFunction(
                'is_feature_enabled',
                array($this->toggler, 'isEnabled')
            ),
        );
    }

    /**
     * Get extension name
     *
     * @return string
     */
    public function getName()
    {
        return 'toggler_extension';
    }
}