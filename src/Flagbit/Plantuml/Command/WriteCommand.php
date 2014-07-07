<?php

namespace Flagbit\Plantuml\Command;

use Flagbit\Plantuml\TokenReflection\RelationWriter;
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
            ->addOption('with-relations', null, null, 'Enables rendering of relations');
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

        $classWriter = new \Flagbit\Plantuml\TokenReflection\ClassWriter();
        if (!$input->getOption('without-constants')) {
            $classWriter->setConstantWriter(new \Flagbit\Plantuml\TokenReflection\ConstantWriter());
        }
        if (!$input->getOption('without-properties')) {
            $classWriter->setPropertyWriter(new \Flagbit\Plantuml\TokenReflection\PropertyWriter());
        }
        if (!$input->getOption('without-methods')) {
            $classWriter->setMethodWriter(new \Flagbit\Plantuml\TokenReflection\MethodWriter());
        }
        if ($input->getOption('with-relations') || $input->getParameterOption('with-relations')) {
            $classWriter->setRelationWriter(new RelationWriter());
        }

        $output->write('@startuml', "\n");
        foreach ($broker->getClasses() as $class) {
            /** @var $class \TokenReflection\IReflectionClass */
            $output->write($classWriter->writeElement($class));
        }
        $output->write('@enduml', "\n");
    }
}
