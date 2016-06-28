<?php

namespace Flagbit\Plantuml\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

class WriteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('write')
            ->setDescription('Generates PlantUML diagram from php source')
            ->addArgument(
                'files',
                InputArgument::IS_ARRAY
            )
            ->addOption('without-constants', null, null, 'Disables rendering of constants')
            ->addOption('without-methods', null, null, 'Disables rendering of methods')
            ->addOption('without-properties', null, null, 'Disables rendering of properties')
            ->addOption('without-doc-content', null, null, 'Disables parsing doc block for methods or properties')
            ->addOption('grouping', null, null, 'Enable deprecated and todo grouping for methods')
            ->addOption('without-function-params', null, null, 'Do not display function param, only count');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $broker = new \TokenReflection\Broker(new \TokenReflection\Broker\Backend\Memory());

        foreach ($input->getArgument('files') as $fileToProcess) {
            if (is_dir($fileToProcess)) {
                $broker->processDirectory($fileToProcess);
            }
            else {
                $broker->processFile($fileToProcess);
            }
        }

        $writerOptions = new \Flagbit\Plantuml\TokenReflection\WriterOptions();
        if ($input->getOption('without-function-params')) {
            $writerOptions->withoutFunctionParameter = true;
        }

        $classWriter = new \Flagbit\Plantuml\TokenReflection\ClassWriter();
        if (!$input->getOption('without-constants')) {
            $classWriter->setConstantWriter(new \Flagbit\Plantuml\TokenReflection\ConstantWriter());
        }
        if (!$input->getOption('without-properties')) {
            if ($input->getOption('grouping')) {
                $classWriter->setPropertyWriter(new \Flagbit\Plantuml\TokenReflection\PropertyGroupingWriter());
            } else {
                $classWriter->setPropertyWriter(new \Flagbit\Plantuml\TokenReflection\PropertyWriter());
            }
        }
        if (!$input->getOption('without-methods')) {
            if ($input->getOption('grouping')) {
                $classWriter->setMethodWriter(new \Flagbit\Plantuml\TokenReflection\MethodGroupingWriter($writerOptions));
            } else {
                $classWriter->setMethodWriter(new \Flagbit\Plantuml\TokenReflection\MethodWriter($writerOptions));
            }
        }
        if (!$input->getOption('without-doc-content')) {
            $classWriter->setDocContentWriter(new \Flagbit\Plantuml\TokenReflection\DocContentWriter());
        }

        $output->write('@startuml', "\n");
        foreach ($broker->getClasses() as $class) {
            /** @var $class \TokenReflection\IReflectionClass */
            $output->write($classWriter->writeElement($class));
        }
        $output->write('@enduml', "\n");
    }
}
