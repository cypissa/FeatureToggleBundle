<?php

namespace Cogi\FeatureToggleBundle\Tests\Service;

use Cogi\FeatureToggleBundle\Condition\LikelihoodCondition;
use Cogi\FeatureToggleBundle\Entity\Feature;
use Cogi\FeatureToggleBundle\Service\Toggler;

/**
 * Test for the toggler
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
class TogglerTest extends \PHPUnit_Framework_TestCase
{
    protected $configurationBag;

    protected $session;

    protected $sut;

    /**
     * Set up
     *
     */
    public function setUp()
    {
        $this->configurationBag = $this->getMockBuilder('Cogi\FeatureToggleBundle\Configuration\ConfigurationBag')
            ->setMethods(array())
            ->disableOriginalConstructor()
            ->getMock();

        $this->session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
            ->setMethods(array('has', 'get', 'set'))
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new Toggler($this->configurationBag, $this->session);
    }

    /**
     * @param boolean $throughoutSession  - is throughout session
     * @param boolean $isEnabledInSession - is feature enabled in session
     * @param boolean $expectedResult     - expected result
     *
     * @covers Cogi\FeatureToggleBundle\Service\Toggler::isEnabled
     *
     * @dataProvider dataProvider_testIsEnabled_WithSession
     */
    public function testIsEnabled_WithSession($throughoutSession, $isEnabledInSession, $expectedResult)
    {
        $featureName = 'test';
        $feature = new Feature($featureName, 12, $throughoutSession, array());

        $this->configurationBag->expects($this->any())
            ->method('getFeature')
            ->with($this->equalTo($featureName))
            ->will($this->returnValue($feature));

        $this->session->expects($this->any())
            ->method('has')
            ->with($this->equalTo('feature_toggle_test'))
            ->will($this->returnValue(true));

        $this->session->expects($this->any())
            ->method('get')
            ->with($this->equalTo('feature_toggle_test'))
            ->will($this->returnValue($isEnabledInSession));

        $this->session->expects($this->never())
            ->method('set');

        $this->assertEquals($expectedResult, $this->sut->isEnabled($featureName));
    }

    /**
     * Provides data for the test
     *
     * @return array
     */
    public static function dataProvider_testIsEnabled_WithSession()
    {
        return array(
            array(true, true, true),
            array(true, false, false),
            array(false, true, true),
            array(false, false, true),
        );
    }

    /**
     * @param boolean $condition1isPermitted - is permitted for the first condition
     * @param boolean $condition2isPermitted - is permitted for the second condition
     * @param boolean $expectedResult        - expected result
     *
     * @covers Cogi\FeatureToggleBundle\Service\Toggler::isEnabled
     * @covers Cogi\FeatureToggleBundle\Service\Toggler::registerCondition
     *
     * @dataProvider dataProvider_testIsEnabled_WithCondition_IsEnabled
     */
    public function testIsEnabled_WithCondition_IsEnabled($condition1isPermitted, $condition2isPermitted, $expectedResult)
    {
        $condition1 = $this->getMock('Cogi\FeatureToggleBundle\Condition\ConditionInterface', array('isPermitted'));
        $condition2 = $this->getMock('Cogi\FeatureToggleBundle\Condition\ConditionInterface', array('isPermitted'));

        $this->sut->registerCondition($condition1);
        $this->sut->registerCondition($condition2);

        $feature = new Feature('test', 23, true, array());
        $this->configurationBag->expects($this->any())
            ->method('getFeature')
            ->with($this->equalTo('test'))
            ->will($this->returnValue($feature));

        $condition1->expects($this->any())
            ->method('isPermitted')
            ->with($this->equalTo($feature))
            ->will($this->returnValue($condition1isPermitted));
        $condition2->expects($this->any())
            ->method('isPermitted')
            ->with($this->equalTo($feature))
            ->will($this->returnValue($condition2isPermitted));

        $this->session->expects($this->any())
            ->method('has')
            ->with($this->equalTo('feature_toggle_test'))
            ->will($this->returnValue(false));

        $this->session->expects($this->once())
            ->method('set')
            ->with($this->equalTo('feature_toggle_test'), $this->equalTo($expectedResult));

        $this->assertEquals($expectedResult, $this->sut->isEnabled('test'));
    }

    /**
     * Provides data for the test
     *
     * @return array
     */
    public static function dataProvider_testIsEnabled_WithCondition_IsEnabled()
    {
       return array(
           array(true, true, true),
           array(true, false, false),
           array(false, true, false),
           array(false, false, false),
       );
    }
}