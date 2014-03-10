php-plantumlwriter [![Build Status](https://travis-ci.org/davidfuhr/php-plantumlwriter.png?branch=master)](https://travis-ci.org/davidfuhr/php-plantumlwriter) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/ec51fd8d-6505-45ec-af41-7cb70ce1d89c/mini.png)](https://insight.sensiolabs.com/projects/ec51fd8d-6505-45ec-af41-7cb70ce1d89c)
==================

Description
-----------

A tool to create [PlantUML](http://plantuml.sourceforge.net/) class diagrams from your PHP source.

Currently the following language features are supported:

- Property and method visibility
- Static properties and methods
- Method return types from doc comment
- Parameter types from type hinting and doc comment
- Parameter default values
- Class constants with value
- Property types from doc comment
- Property default values
- Implemented interfaces and parent classes
- Abstract classes

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

If you have a large class with lots of methods you can suppress method printing using the `--without-methods` flag:

    php bin/console.php write --without-methods path/to/your/LargeClass.php

Other available options are `--without-properties` and `--without-constants`.

You can also generate a whole directory at once:

    php bin/console.php write path/to/directory

Or multiple files or directories:

    php bin/console.php write path/to/ClassOne.php path/to/ClassTwo.php path/to/directory

Known Issues
------------

- Imported classes are currently not handled correctly if read from doc comment
  (use-Statement is not fully evaluated) which affects return values and property
  types. `use Namespace\B` will be evaluated and expanded but `use Namespace\B as C`
  is not yet de-aliased.
- Constants appear twice if the are defined in the parent class and in the current
  class.
- Inherited methods and properties appear on each extending class. This is an
  issue on large diagrams.
- Indirectly inherited interfaces appear twice. If `A implements B` and `B implements C`
  `C` is shown as a direct relation to `A`.

Future Plans
------------

- Add support for class relations, maybe with quantifiers. This could be parsed from
  the doc comments. We could also guess the foreign quantifier (`@var OtherClass` as
  "*..1" and `@var OtherClass[]` as "*..*"), but we can't determine our quantifier.
- Evaluate Implementation of Visitor Pattern
- Implement own set of Interfaces

Alternatives
------------

- http://westhoffswelt.de/projects/phuml.html
