<?php

require __DIR__ . "/../index.php";

use PHPUnit\Framework\TestCase;

class test1 extends TestCase
{
    // instance of class
    private stackingCalc $myExample;
    // test values
    private $amount = 100;
    private $apy = 20;
    private $period = 3;
    // expected result
    private $expectedResult = 172.8;

    protected function setUp(): void
    {
        parent::setUp();
        $this->myExample = new stackingCalc($this->amount, $this->apy, $this->period);
    }

    public function test_1()
    {
        $this->assertEquals($this->expectedResult, $this->myExample->fixed());
    }
}