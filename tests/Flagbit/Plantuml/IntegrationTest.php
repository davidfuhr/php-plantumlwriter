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
     * @dataProvider provideTestCasesNoParam
     */
    public function testIntegration($phpFile, $pumlCode)
    {
        $input = new ArrayInput(array(
            'files' => array($phpFile),
        ));
        $output = new BufferedOutput();

        $command = new WriteCommand();
        $command->run($input, $output);

        $this->assertEquals($pumlCode, $output->fetch());
    }

    /**
     * @dataProvider provideTestCasesWithGroup
     */
    public function testIntegrationWithGroup($phpFile, $pumlCode)
    {
        $input = new ArrayInput(array(
            'files' => array($phpFile),
            '--grouping' => true
        ));
        $output = new BufferedOutput();

        $command = new WriteCommand();
        $command->run($input, $output);

        $this->assertEquals($pumlCode, $output->fetch());
    }

    /**
     * @dataProvider provideTestCasesWithoutFunctionParams
     */
    public function testIntegrationWithoutFunctionParams($phpFile, $pumlCode)
    {
        $input = new ArrayInput(array(
            'files' => array($phpFile),
            '--without-function-params' => true
        ));
        $output = new BufferedOutput();

        $command = new WriteCommand();
        $command->run($input, $output);

        $this->assertEquals($pumlCode, $output->fetch());        
    }

    public function provideTestCasesNoParam()
    {
        return $this->provideTestCase('/test[a-zA-Z]*\.php$/');
    }

    public function provideTestCasesWithGroup()
    {
        return $this->provideTestCase('/test[a-zA-Z]*_with-group\.php$/');
    }

    public function provideTestCasesWithoutFunctionParams()
    {
        return $this->provideTestCase('/test[a-zA-Z]*_without-fct-params\.php$/');
    }

    private function provideTestCase($filter)
    {
        print($filter);
        $fixturesDir = realpath($this->getFixturesDir());
        $tests = array();

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fixturesDir), RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
            /* @var $file \SplFileInfo */
            if (!preg_match($filter, $file)) {
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
