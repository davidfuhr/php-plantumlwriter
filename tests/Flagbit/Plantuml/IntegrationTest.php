<?php

namespace Flagbit\Test\Plantuml;

use Flagbit\Plantuml\Command\WriteCommand;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function getFixturesDir()
    {
        return dirname(__FILE__) . '/Fixtures/';
    }

    /**
     * @dataProvider provideTestCases
     */
    public function testIntegration($phpFile, $pumlCode)
    {
        $command = new WriteCommand();

        $input = new ArrayInput(
            array(
                'with-relations',
                'files' => array($phpFile),
            ),
            $command->getDefinition()
        );

        $output = new BufferedOutput();

        $command->run($input, $output);

        $this->assertEquals($pumlCode, $output->fetch());
    }

    public function provideTestCases()
    {
        $fixturesDir = realpath($this->getFixturesDir());
        $tests = array();

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fixturesDir), RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
            /* @var $file \SplFileInfo */
            if (!preg_match('/\.php$/', $file)) {
                continue;
            }

            $phpFile = $file->getRealpath();
            $pumlCode = file_get_contents(preg_replace('/\.php$/', '.puml', $file->getRealPath()));

            $tests[] = array(
                'phpFile' => $phpFile,
                'pumlCode' => $pumlCode,
            );
        }

        return $tests;
    }
}
