<?php

/**
 * Modified from code from Laravel Framework (https://github.com/laravel/framework)
 *
 * The MIT License (MIT)
 *
 * Copyright (c) Taylor Otwell
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Rareloop\Hatchet;

use Rareloop\Hatchet\Parser;
use PHPUnit\Framework\TestCase;

class ConsoleParserTest extends TestCase
{
    public function testBasicParameterParsing()
    {
        $results = Parser::parse('command:name');

        $this->assertEquals('command:name', $results[0]);

        $results = Parser::parse('command:name {argument} {--option}');

        $this->assertEquals('command:name', $results[0]);
        $this->assertEquals('argument', $results[1][0]->getName());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertFalse($results[2][0]->acceptValue());

        $results = Parser::parse('command:name {argument*} {--option=}');

        $this->assertEquals('command:name', $results[0]);
        $this->assertEquals('argument', $results[1][0]->getName());
        $this->assertTrue($results[1][0]->isArray());
        $this->assertTrue($results[1][0]->isRequired());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertTrue($results[2][0]->acceptValue());

        $results = Parser::parse('command:name {argument?*} {--option=*}');

        $this->assertEquals('command:name', $results[0]);
        $this->assertEquals('argument', $results[1][0]->getName());
        $this->assertTrue($results[1][0]->isArray());
        $this->assertFalse($results[1][0]->isRequired());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertTrue($results[2][0]->acceptValue());
        $this->assertTrue($results[2][0]->isArray());

        $results = Parser::parse('command:name {argument?* : The argument description.}    {--option=* : The option description.}');

        $this->assertEquals('command:name', $results[0]);
        $this->assertEquals('argument', $results[1][0]->getName());
        $this->assertEquals('The argument description.', $results[1][0]->getDescription());
        $this->assertTrue($results[1][0]->isArray());
        $this->assertFalse($results[1][0]->isRequired());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertEquals('The option description.', $results[2][0]->getDescription());
        $this->assertTrue($results[2][0]->acceptValue());
        $this->assertTrue($results[2][0]->isArray());

        $results = Parser::parse('command:name
            {argument?* : The argument description.}
            {--option=* : The option description.}');

        $this->assertEquals('command:name', $results[0]);
        $this->assertEquals('argument', $results[1][0]->getName());
        $this->assertEquals('The argument description.', $results[1][0]->getDescription());
        $this->assertTrue($results[1][0]->isArray());
        $this->assertFalse($results[1][0]->isRequired());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertEquals('The option description.', $results[2][0]->getDescription());
        $this->assertTrue($results[2][0]->acceptValue());
        $this->assertTrue($results[2][0]->isArray());
    }

    public function testShortcutNameParsing()
    {
        $results = Parser::parse('command:name {--o|option}');

        $this->assertEquals('o', $results[2][0]->getShortcut());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertFalse($results[2][0]->acceptValue());

        $results = Parser::parse('command:name {--o|option=}');

        $this->assertEquals('o', $results[2][0]->getShortcut());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertTrue($results[2][0]->acceptValue());

        $results = Parser::parse('command:name {--o|option=*}');

        $this->assertEquals('command:name', $results[0]);
        $this->assertEquals('o', $results[2][0]->getShortcut());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertTrue($results[2][0]->acceptValue());
        $this->assertTrue($results[2][0]->isArray());

        $results = Parser::parse('command:name {--o|option=* : The option description.}');

        $this->assertEquals('command:name', $results[0]);
        $this->assertEquals('o', $results[2][0]->getShortcut());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertEquals('The option description.', $results[2][0]->getDescription());
        $this->assertTrue($results[2][0]->acceptValue());
        $this->assertTrue($results[2][0]->isArray());

        $results = Parser::parse('command:name
            {--o|option=* : The option description.}');

        $this->assertEquals('command:name', $results[0]);
        $this->assertEquals('o', $results[2][0]->getShortcut());
        $this->assertEquals('option', $results[2][0]->getName());
        $this->assertEquals('The option description.', $results[2][0]->getDescription());
        $this->assertTrue($results[2][0]->acceptValue());
        $this->assertTrue($results[2][0]->isArray());
    }

    public function testDefaultValueParsing()
    {
        $results = Parser::parse('command:name {argument=defaultArgumentValue} {--option=defaultOptionValue}');

        $this->assertFalse($results[1][0]->isRequired());
        $this->assertEquals('defaultArgumentValue', $results[1][0]->getDefault());
        $this->assertTrue($results[2][0]->acceptValue());
        $this->assertEquals('defaultOptionValue', $results[2][0]->getDefault());

        $results = Parser::parse('command:name {argument=*defaultArgumentValue1,defaultArgumentValue2} {--option=*defaultOptionValue1,defaultOptionValue2}');

        $this->assertTrue($results[1][0]->isArray());
        $this->assertFalse($results[1][0]->isRequired());
        $this->assertEquals(['defaultArgumentValue1', 'defaultArgumentValue2'], $results[1][0]->getDefault());
        $this->assertTrue($results[2][0]->acceptValue());
        $this->assertTrue($results[2][0]->isArray());
        $this->assertEquals(['defaultOptionValue1', 'defaultOptionValue2'], $results[2][0]->getDefault());
    }

    public function testArgumentDefaultValue()
    {
        $results = Parser::parse('command:name {argument= : The argument description.}');
        $this->assertNull($results[1][0]->getDefault());

        $results = Parser::parse('command:name {argument=default : The argument description.}');
        $this->assertSame('default', $results[1][0]->getDefault());
    }

    public function testOptionDefaultValue()
    {
        $results = Parser::parse('command:name {--option= : The option description.}');
        $this->assertNull($results[2][0]->getDefault());

        $results = Parser::parse('command:name {--option=default : The option description.}');
        $this->assertSame('default', $results[2][0]->getDefault());
    }
}
