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

use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Filesystem;
use PhpSpec\ObjectBehavior;

class EditorSpec extends ObjectBehavior
{
    const FILENAME = '/tmp/file-to-edit.txt';

    function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_inserts_lines_before_cursor(Filesystem $filesystem, File $file)
    {
        // Fixtures
        $beforeLines = array('We', 'are', 'knights', 'who', 'say', 'ni');
        $afterLines = array('We', 'are', 'the', 'knights', 'who', 'say', 'ni');

        // Looking for the line "knights"
        $file->readlines()->willReturn($beforeLines);
        $file->getFilename()->willReturn('/monthy/python.txt');
        $file->getCurrentLineNumber()->willReturn(0);
        $file->setCurrentLineNumber(2)->shouldBeCalled();
        $this->jumpDownTo($file, 'knights');
    }

    function it_opens_existing_files(Filesystem $filesystem, File $file)
    {
        $filename = '/monty.py';

        $filesystem->exists($filename)->willReturn(true);
        $filesystem->open($filename)->willReturn($file);

        $this->open($filename);
    }

    function it_cannot_open_new_files(Filesystem $filesystem, File $file)
    {
        $filename = '/monty.py';
        $exception = 'Symfony\Component\Filesystem\Exception\FileNotFoundException';

        $filesystem->exists($filename)->willReturn(false);
        $filesystem->open($filename)->willThrow($exception);

        $this->shouldThrow($exception)->duringOpen($filename);
    }

    function it_creates_new_files(Filesystem $filesystem, File $file)
    {
        $filename = '/monty.py';

        $filesystem->exists($filename)->willReturn(false);
        $filesystem->create($filename)->willReturn($file);

        $this->open($filename, true);
    }

    function it_inserts_lines_before_current_one(File $file)
    {
        $line = 'We are the knights who say Ni!';
        $lineNumber = 42;

        $file->getCurrentLineNumber()->willReturn($lineNumber);
        $file->insertLineAt($line, $lineNumber)->shouldBeCalled();

        $this->addBefore($file, $line);
    }

    function it_inserts_lines_after_current_one(File $file)
    {
        $line = 'We are the knights who say Ni!';
        $currentLineNumber = 42;
        $lineNumber = $currentLineNumber + 1;

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $file->setCurrentLineNumber($lineNumber)->shouldBeCalled();
        $file->insertLineAt($line, $lineNumber)->shouldBeCalled();

        $this->addAfter($file, $line);
    }

    function it_changes_the_current_line(File $file)
    {
        $line = 'We are the knights who say Ni!';
        $newLine = 'We are knights who say Ni!';
        $lineNumber = 42;

        $file->getCurrentLineNumber()->willReturn($lineNumber);
        $file->changeLineTo($newLine, $lineNumber)->shouldBeCalled();

        $this->changeTo($file, $newLine);
    }

    function it_replaces_the_current_line(File $file)
    {
        $line = 'We are the knights who say Ni!';
        $newLine = 'We are the knights who say Peng!';
        $lineNumber = 42;

        $file->getCurrentLineNumber()->willReturn($lineNumber);
        $file->readlines()->willReturn(array($lineNumber => $line));
        $file->changeLineTo($newLine, $lineNumber)->shouldBeCalled();

        $this->replaceWith($file, '/Ni/', 'Peng');
    }

    function it_removes_the_current_line(File $file)
    {
        $lineNumber = 42;

        $file->getCurrentLineNumber()->willReturn($lineNumber);
        $file->removeLine($lineNumber)->shouldBeCalled();

        $this->remove($file);
    }

    function it_saves_files(Filesystem $filesystem, File $file)
    {
        $filesystem->write($file)->shouldBeCalled();

        $this->save($file);
    }
}
