<?php

declare(strict_types=1);

use PhpClass\PhpClass;
use PhpClass\Exception\InvalidClassException;
use PhpClass\Exception\PathNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

############################################################ Non-existing file

try {
    new PhpClass('');
    throw new Exception('Should have failed');
} catch (PathNotFoundException $e) {}

echo "· Non-existing file\n";

############################################################ Fully-qualified class name

$A = new PhpClass(__DIR__ . '/A.php');

assert($A->namespace() === 'NS');
assert($A->classname() === 'A');
assert($A->instantiate() instanceof NS\A);

echo "· Fully-qualified class name\n";

############################################################ Class without namespace

$B = new PhpClass(__DIR__ . '/B.php');

assert($B->namespace() === '');
assert($B->classname() === 'B');
assert($B->instantiate() instanceof B);

echo "· Class without namespace\n";

############################################################ Invalid file

$C = new PhpClass(__DIR__ . '/C.php');

assert($C->namespace() === 'NS');
assert($C->classname() === '');

try {
    $C->instantiate();
    throw new Exception('Should have failed');
} catch (InvalidClassException $e) {}

echo "· Invalid file\n";

############################################################ Empty file

$D = new PhpClass(__DIR__ . '/D.php');

assert($D->namespace() === '');
assert($D->classname() === '');

try {
    $D->instantiate();
    throw new Exception('Should have failed');
} catch (InvalidClassException $e) {}

echo "· Empty file\n";
