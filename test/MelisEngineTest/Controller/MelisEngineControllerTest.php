<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngineTest\Controller;

use MelisCore\ServiceManagerGrabber;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
class MelisEngineControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = false;
    protected $sm;
    protected $method = 'save';

    public function setUp()
    {
        $this->sm  = new ServiceManagerGrabber();
    }

    

    public function getPayload($method)
    {
        return $this->sm->getPhpUnitTool()->getPayload('MelisEngine', $method);
    }

    /**
     * START ADDING YOUR TESTS HERE
     */

    public function testBasicMelisEngineTestSuccess()
    {
        $this->assertEquals("equalvalue", "equalvalue");
    }

    public function testBasicMelisEngineTestError()
    {
        $this->assertEquals("supposed-to", "display-an-error");
    }



}
