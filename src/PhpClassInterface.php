<?php

declare(strict_types=1);

namespace PhpClass;

/**
 * PHP class parser interface
 */
interface PhpClassInterface
{
    /**
     * Return the file path
     *
     * @return string
     */
    public function path() : string;

    /**
     * Return the namespace
     *
     * @return string
     */
    public function namespace() : string;

    /**
     * Return the classname
     *
     * @return string
     */
    public function classname() : string;

    /**
     * Instantiate the class and return the object
     *
     * @return object
     */
    public function instantiate() : object;
}
