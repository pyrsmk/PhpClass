<?php

declare(strict_types=1);

namespace PhpClass;

use PhpClass\Exception\InvalidClassException;
use PhpClass\Exception\PathNotFoundException;
use PhpClass\Exception\TokenNotFoundException;

/**
 * PHP class parser
 */
final class PhpClass implements PhpClassInterface
{
    /**
     * Class path
     *
     * @var string
     */
    private $path;

    /**
     * Class tokens
     *
     * @var array
     */
    private $tokens;

    /**
     * Constructor
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->tokens = $this->load($path);
    }

    /**
     * Load the file's tokens
     *
     * @param string $path
     * @return array
     */
    private function load(string $path) : array
    {
        return token_get_all(
            file_get_contents(
                $this->validatePath($path)
            )
        );
    }

    /**
     * Verify the class path
     *
     * @param string $path
     * @return string
     */
    private function validatePath(string $path) : string
    {
        if (!file_exists($path)) {
            throw new PathNotFoundException("'$path' path does not exist");
        }
        return $path;
    }

    /**
     * Return the file path
     *
     * @return string
     */
    public function path() : string
    {
        return $this->path;
    }

    /**
     * Return the namespace
     *
     * @return string
     */
    public function namespace() : string
    {
        try {
            $openingOffset = $this->findNextToken(0, T_NAMESPACE) + 2;
            $closingOffset = $this->findNextChar($openingOffset, ';') - 1;
            return array_reduce(
                array_slice(
                    $this->tokens,
                    $openingOffset,
                    $closingOffset - $openingOffset + 1
                ),
                function (string $namespace, array $token) {
                    return $namespace . $token[1];
                },
                ''
            );
        } catch (TokenNotFoundException $e) {
            return '';
        }
    }

    /**
     * Return the classname
     *
     * @return string
     */
    public function classname() : string
    {
        try {
            return $this->tokens[
                $this->findNextToken(0, T_CLASS) + 2
            ][1];
        } catch (TokenNotFoundException $e) {
            return '';
        }
    }

    /**
     * Instantiate the class and return the object
     *
     * @return object
     */
    public function instantiate() : object
    {
        require_once $this->path;
        $class = $this->validateClassName(
            $this->namespace() . '\\' . $this->classname()
        );
        return new $class;
    }

    /**
     * Find the next requested token and return its offset
     *
     * @param integer $offset
     * @param integer $type
     * @return integer
     */
    private function findNextToken(int $offset, int $type) : int
    {
        for ($i = $offset, $j = count($this->tokens); $i < $j; ++$i) {
            if (is_array($this->tokens[$i])
                && $this->tokens[$i][0] === $type
            ) {
                return $i;
            }
        }
        throw new TokenNotFoundException("'$type' token type has not been found from $offset position");
    }

    /**
     * Find the next requested character and return its offset
     *
     * @param integer $offset
     * @param string $char
     * @return integer
     */
    private function findNextChar(int $offset, string $char) : int
    {
        for ($i = $offset, $j = count($this->tokens); $i < $j; ++$i) {
            if (is_string($this->tokens[$i])
                && $this->tokens[$i] === $char
            ) {
                return $i;
            }
        }
        throw new TokenNotFoundException("'$char' token char has not been found from $offset position");
    }

    /**
     * Validate a class name
     *
     * @param string $class
     * @return string
     */
    private function validateClassName(string $class) : string
    {
        if ($class[-1] === '\\') {
            throw new InvalidClassException("'$this->path' PHP file cannot be instantiated");
        }
        return $class;
    }
}
