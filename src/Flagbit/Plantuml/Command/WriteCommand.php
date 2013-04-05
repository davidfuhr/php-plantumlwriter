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
            ->addOption('no-methods');
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
        $classWriter->setConstantWriter(new \Flagbit\Plantuml\TokenReflection\ConstantWriter());
        $classWriter->setPropertyWriter(new \Flagbit\Plantuml\TokenReflection\PropertyWriter());
        if (!$input->getOption('no-methods')) {
            $classWriter->setMethodWriter(new \Flagbit\Plantuml\TokenReflection\MethodWriter());
        }

        $output->write('@startuml', "\n");
        foreach ($broker->getClasses() as $class) {
            /** @var $class \TokenReflection\IReflectionClass */
            $output->write($classWriter->writeElement($class));
        }
        $output->write('@enduml', "\n");
    }
}
