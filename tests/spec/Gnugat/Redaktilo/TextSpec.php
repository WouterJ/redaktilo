<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo;

use Gnugat\Redaktilo\Service\LineBreak;
use PhpSpec\ObjectBehavior;

class TextSpec extends ObjectBehavior
{
    private $lines;
    private $lineBreak;
    private $length;

    function let()
    {
        $rootPath = __DIR__.'/../../../../';
        $filename = '%s/tests/fixtures/sources/life-of-brian.txt';
        $content = file_get_contents(sprintf($filename, $rootPath));

        $lineBreak = new LineBreak();
        $this->lineBreak = $lineBreak->detect($content);
        $this->lines = explode($this->lineBreak, $content);
        $this->length = count($this->lines);

        $this->beConstructedWith($this->lines, $this->lineBreak);
    }

    function it_has_lines()
    {
        $newContent = array(
            'This',
            'is an EX parrot!'
        );

        $this->getLines()->shouldBe($this->lines);
        $this->setLines($newContent);
        $this->getLines()->shouldBe($newContent);
    }

    function it_has_a_length()
    {
        $newContent = array(
            'YOU',
            'SHOULD NOT',
            'PASS'
        );

        $this->getLength()->shouldBe(count($this->lines));
        $this->setLines($newContent);
        $this->getLength()->shouldBe(3);
    }

    function it_has_a_current_line_number()
    {
        $this->getCurrentLineNumber()->shouldBe(0);

        $middleLine = intval(count($this->lines) / 2);

        $this->setCurrentLineNumber($middleLine);
        $this->getCurrentLineNumber()->shouldBe($middleLine);
    }

    function it_fails_when_the_line_number_is_invalid()
    {
        $exception = '\Gnugat\Redaktilo\Exception\InvalidLineNumberException';

        $this->shouldThrow($exception)->duringSetCurrentLineNumber('toto');
        $this->shouldThrow($exception)->duringSetCurrentLineNumber(-1);
        $this->shouldThrow($exception)->duringSetCurrentLineNumber(9);
    }

    function it_has_a_line_break()
    {
        $newLineBreak = '\r\n';

        $this->getLineBreak()->shouldBe($this->lineBreak);
        $this->setLineBreak($newLineBreak);
        $this->getLineBreak()->shouldBe($newLineBreak);
    }

    function it_manipulates_the_current_line()
    {
        $lineNumber = 1;
        $line = '[A guard struggles not to snigger]';
        $this->setCurrentLineNumber(1);

        $this->getLine()->shouldBe('[A guard sniggers]');
        $this->setLine($line);
        $this->getLine()->shouldBe($line);
    }

    function it_manipulates_the_given_line()
    {
        $lineNumber = 5;
        $line = '[Even more sniggering]';

        $this->getLine($lineNumber)->shouldBe('[Sniggering]');
        $this->setLine($line, $lineNumber);
        $this->getLine($lineNumber)->shouldBe($line);
    }

    function it_cannot_manipulate_an_invalid_line()
    {
        $exception = '\Gnugat\Redaktilo\Exception\InvalidLineNumberException';
        $line = 'I came here to learn how to fly an aeroplane';

        $this->shouldThrow($exception)->duringSetLine($line, 'toto');
        $this->shouldThrow($exception)->duringSetLine($line, -1);
        $this->shouldThrow($exception)->duringSetLine($line, 9);

        $this->shouldThrow($exception)->duringGetLine('toto');
        $this->shouldThrow($exception)->duringGetLine(-1);
        $this->shouldThrow($exception)->duringGetLine(9);
    }

    function it_increments_current_line_number()
    {
        $this->setCurrentLineNumber(0);
        $this->incrementCurrentLineNumber(2);

        $this->getCurrentLineNumber()->shouldBe(2);
    }

    function it_cannot_increment_current_line_number_with_invalid_lines()
    {
        $exception = '\Gnugat\Redaktilo\Exception\InvalidLineNumberException';

        $this->setCurrentLineNumber(1);
        $lastLineNumber = $this->length - 1;

        $this->shouldThrow($exception)->duringIncrementCurrentLineNumber('toto');
        $this->shouldThrow($exception)->duringIncrementCurrentLineNumber(-1);
        $this->shouldThrow($exception)->duringIncrementCurrentLineNumber(4423);
        $this->shouldThrow($exception)->duringIncrementCurrentLineNumber($lastLineNumber);
    }

    function it_decrements_current_line_number()
    {
        $this->setCurrentLineNumber(3);
        $this->decrementCurrentLineNumber(2);

        $this->getCurrentLineNumber()->shouldBe(1);
    }

    function it_cannot_decrement_current_line_number_with_invalid_lines()
    {
        $exception = '\Gnugat\Redaktilo\Exception\InvalidLineNumberException';

        $lastLineNumber = $this->length - 1;
        $this->setCurrentLineNumber($lastLineNumber - 1);

        $this->shouldThrow($exception)->duringDecrementCurrentLineNumber('toto');
        $this->shouldThrow($exception)->duringDecrementCurrentLineNumber(-1);
        $this->shouldThrow($exception)->duringDecrementCurrentLineNumber(4423);
        $this->shouldThrow($exception)->duringDecrementCurrentLineNumber($lastLineNumber);
    }
}
