<?php

namespace Cogi\FeatureToggleBundle\Tests\Condition;

use Cogi\FeatureToggleBundle\Condition\UsersCondition;
use Cogi\FeatureToggleBundle\Entity\Feature;

/**
 * Users condition test
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
class UsersConditionTest extends \PHPUnit_Framework_TestCase
{
    protected $sut;

    protected $securityToken;

    protected $securityContext;

    /**
     * Set up
     *
     */
    public function setUp()
    {
        $this->securityToken = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken')
            ->setMethods(array('getUsername'))
            ->disableOriginalConstructor()
            ->getMock();

        $this->securityContext = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->setMethods(array('getToken'))
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new UsersCondition($this->securityContext);
    }

    /**
     * @covers Cogi\FeatureToggleBundle\Condition\UsersCondition::isPermitted
     */
    public function testIsPermitted_UsersIsNotSetForTheFeature()
    {
        $this->securityContext->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($this->securityToken));

        $feature = new Feature('test', 100, false, array());

        $this->assertTrue($this->sut->isPermitted($feature));
    }

    /**
     * @covers Cogi\FeatureToggleBundle\Condition\UsersCondition::isPermitted
     */
    public function testIsPermitted_AnonymousAndUsersIsNotSetForTheFeature()
    {
        $this->securityContext->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null));

        $feature = new Feature('test', 100, false, array());

        $this->assertTrue($this->sut->isPermitted($feature));
    }

    /**
     * @covers Cogi\FeatureToggleBundle\Condition\UsersCondition::isPermitted
     */
    public function testIsPermitted_Anonymous()
    {
        $this->securityContext->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null));

        $feature = new Feature('test', 100, false, array('someuser'));

        $this->assertFalse($this->sut->isPermitted($feature));
    }

    /**
     * @param string  $loggedUsername - username of logged user
     * @param boolean $expectedResult - expected result
     *
     * @covers Cogi\FeatureToggleBundle\Condition\UsersCondition::isPermitted
     *
     * @dataProvider dataProvider_testIsPermitted_Logged
     */
    public function testIsPermitted_Logged($loggedUsername, $expectedResult)
    {
        $this->securityToken->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue($loggedUsername));
        $this->securityContext->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($this->securityToken));

        $feature = new Feature('test', 100, false, array('user2'));

        $this->assertEquals($expectedResult, $this->sut->isPermitted($feature));
    }

    /**
     * Provides data for the test
     *
     * @return array
     */
    public static function dataProvider_testIsPermitted_Logged()
    {
        return array(
            array('user1', false),
            array('user2', true),
        );
    }
}