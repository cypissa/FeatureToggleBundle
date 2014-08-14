<?php

namespace Cogi\FeatureToggleBundle\Condition;
use Cogi\FeatureToggleBundle\Entity\Feature;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Check if the active user is permitted to see the feature
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
class UsersCondition implements ConditionInterface
{
    protected $securityContext;

    /**
     * @param SecurityContext $securityContext - security context
     */
    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
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
        $users = $feature->getUsers();
        if (empty($users)) {
            return true;
        }

        $token = $this->securityContext->getToken();
        if (!$token) {
            return false;
        }

        $username = $token->getUsername();
        if (in_array($username, $users)) {
            return true;
        }

        return false;
    }
}