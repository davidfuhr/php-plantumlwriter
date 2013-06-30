php-plantumlwriter
==================

Description
-----------

A tool to create [PlantUML](http://plantuml.sourceforge.net/) class diagrams from your PHP source.

Currently the following language features are supported:

- Property and method visibility
- Static properties and methods
- Method return types from doc comment
- Method parameter types from type hinting
- Class constants with value
- Property type froms doc comment
- Implemented interfaces and parent classes

Usage
-----

To generate the PlantUML code for `WriteCommand.php` run

    php bin/console.php write src/Flagbit/Plantuml/Command/WriteCommand.php

which will output

    @startuml
    class Flagbit.Plantuml.Command.WriteCommand {
        #configure()
        #execute(input: Symfony.Component.Console.Input.InputInterface, output: Symfony.Component.Console.Output.OutputInterface)
    }
    class Flagbit.Plantuml.Command.WriteCommand extends Symfony.Component.Console.Command.Command
    @enduml

If you have a large class with lots of methods you can suppress method printing using the `--no-methods` flag:

    php bin/console.php write --no-methods path/to/your/LargeClass.php

You can also generate a whole directory at once:

    php bin/console.php write path/to/directory

Or multiple files or directories:

    php bin/console.php write path/to/ClassOne.php path/to/ClassTwo.php path/to/directory