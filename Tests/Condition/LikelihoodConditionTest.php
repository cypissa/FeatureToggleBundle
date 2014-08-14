<?php

namespace Cogi\FeatureToggleBundle\Tests\Condition;

use Cogi\FeatureToggleBundle\Entity\Feature;

/**
 * Test for likelihood condition
 *
 * @author Cyprian Nosek <cypiszzz@gmail.com>
 */
class LikelihoodConditionTest extends \PHPUnit_Framework_TestCase
{
    protected $sut;

    /**
     * Set up
     */
    public function setUp()
    {
       $this->sut = $this->getMock('Cogi\FeatureToggleBundle\Condition\LikelihoodCondition', array('getRandomNumber'));
    }

    /**
     * @param integer $randomNumber   - random number
     * @param boolean $expectedResult - expected result
     *
     * @covers Cogi\FeatureToggleBundle\Condition\LikelihoodCondition::isPermitted
     *
     * @dataProvider dataProvider_testIsPermitted
     */
    public function testIsPermitted($randomNumber, $expectedResult)
    {
        $this->sut->expects($this->any())
            ->method('getRandomNumber')
            ->will($this->returnValue($randomNumber));

        $feature = new Feature('test', 40, false, array());

        $this->assertEquals($expectedResult, $this->sut->isPermitted($feature));
    }

    /**
     * Provides data for the test
     *
     * @return array
     */
    public static function dataProvider_testIsPermitted()
    {
        return array(
            array(20, true),
            array(40, true),
            array(66, false),
        );
    }
}