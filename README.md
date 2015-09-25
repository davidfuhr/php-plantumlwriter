php-plantumlwriter
==================

[![Build Status](https://travis-ci.org/davidfuhr/php-plantumlwriter.png?branch=master)](https://travis-ci.org/davidfuhr/php-plantumlwriter) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/davidfuhr/php-plantumlwriter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/davidfuhr/php-plantumlwriter/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/ec51fd8d-6505-45ec-af41-7cb70ce1d89c/mini.png)](https://insight.sensiolabs.com/projects/ec51fd8d-6505-45ec-af41-7cb70ce1d89c) [![License](https://poser.pugx.org/davidfuhr/php-plantumlwriter/license.svg)](https://packagist.org/packages/davidfuhr/php-plantumlwriter)

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

Requirements
------------

To generate the PlantUML code a decent version of PHP will suffice. And you need Composer to install the dependencies:

- Composer (http://getcomposer.org/)

But for image generation you need:

- Java Runtime
- `plantuml.jar` (http://plantuml.sourceforge.net/)

### Phar generation

php-plantumlwriter uses [Box 2 library](http://box-project.github.io/box2/) to generate a single .phar file containing everything required for the usage.

To create the .phar file from sources just run:
```
$ box build
```

Usage
-----

To generate the PlantUML code for `WriteCommand.php` run

    php bin/php-plantumlwriter write src/Flagbit/Plantuml/Command/WriteCommand.php > WriteCommand.puml

which will output

    @startuml
    class Flagbit.Plantuml.Command.WriteCommand {
        #configure()
        #execute(input: Symfony.Component.Console.Input.InputInterface, output: Symfony.Component.Console.Output.OutputInterface)
    }
    class Flagbit.Plantuml.Command.WriteCommand extends Symfony.Component.Console.Command.Command
    @enduml

Now you can convert your `puml` file to a `png` file.:

    java -jar plantuml.jar WriteCommand.puml

The resulting png should look like this:

![WriteCommand Class Diagram](http://davidfuhr.github.io/php-plantumlwriter/img/WriteCommand.png)

If you have a large class with lots of methods you can suppress method printing using the `--without-methods` flag:

    php bin/php-plantumlwriter write --without-methods path/to/your/LargeClass.php

Other available options are `--without-properties` and `--without-constants`.

You can also generate a whole directory at once:

    php bin/php-plantumlwriter write path/to/directory

Or multiple files or directories:

    php bin/php-plantumlwriter write path/to/ClassOne.php path/to/ClassTwo.php path/to/directory

Known Issues
------------

- Imported classes are currently not handled correctly if read from doc comment
  (use-Statement is not fully evaluated) which affects return values and property
  types. `use Namespace\B` will be evaluated and expanded but `use Namespace\B as C`
  is not yet de-aliased.
- The Namespace Seperator is "." and not "\".
- Traits are not yet supported. See [#4](https://github.com/davidfuhr/php-plantumlwriter/issues/4)

Future Plans
------------

- Add support for class relations, maybe with quantifiers. This could be parsed from
  the doc comments. We could also guess the foreign quantifier (`@var OtherClass` as
  "*..1" and `@var OtherClass[]` as "*..*"), but we can't determine our quantifier.
  If doctrine annotations are present we can use them.
- Evaluate Implementation of Visitor Pattern
- Implement own set of Interfaces

Alternatives
------------

- http://westhoffswelt.de/projects/phuml.html
