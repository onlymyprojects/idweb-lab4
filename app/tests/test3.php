<?php

require __DIR__ . '/../index.php';

use PHPUnit\Framework\TestCase;

class test3 extends TestCase
{
    // instance of class
    private passwordGenerator $myExample;
    // test values
    private $password = 'qwerty';
    // expected result
    private $expectedResult = array(
        "&Qwe&rt&y",
        "&Qwe&rt%y",
        "&Qwe&rt~y",

        "&Qwe%rt&y",
        "&Qwe%rt%y",
        "&Qwe%rt~y",

        "&Qwe~rt&y",
        "&Qwe~rt%y",
        "&Qwe~rt~y",

        // ----

        "%Qwe&rt&y",
        "%Qwe&rt%y",
        "%Qwe&rt~y",

        "%Qwe%rt&y",
        "%Qwe%rt%y",
        "%Qwe%rt~y",

        "%Qwe~rt&y",
        "%Qwe~rt%y",
        "%Qwe~rt~y",

        // ----

        "~Qwe&rt&y",
        "~Qwe&rt%y",
        "~Qwe&rt~y",

        "~Qwe%rt&y",
        "~Qwe%rt%y",
        "~Qwe%rt~y",

        "~Qwe~rt&y",
        "~Qwe~rt%y",
        "~Qwe~rt~y"
    );

    protected function setUp(): void
    {
        parent::setUp();
        $this->myExample = new passwordGenerator($this->password);
    }

    public function test_3()
    {
        $this->assertContains($this->myExample->password(), $this->expectedResult);
    }
}