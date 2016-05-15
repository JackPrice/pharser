<?php namespace Pharser\Parser\ClassBuilder;

/**
 * A builder for building PHP classes.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class ClassBuilder
{
    /**
     * The name of this class.
     *
     * @var string
     */
    private $name;

    /**
     * The parent class of this class.
     *
     * @var string
     */
    private $extend;

    /**
     * Function builders for this class.
     *
     * @var FunctionBuilder[]
     */
    private $functionBuilders = [];

    /**
     * Constants for this class.
     *
     * @var array
     */
    private $constants = [];

    /**
     * Give this class a random name.
     *
     * @return $this
     */
    public function withRandomName()
    {
        return $this->withName(Util\Random::string(12));
    }

    /**
     * Add a constant to this class.
     *
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function withConstant($name, $value)
    {
        $this->constants[$name] = $value;

        return $this;
    }

    /**
     * Give this class a name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Inherit from a parent class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function extend($class)
    {
        $this->extend = $class;

        return $this;
    }

    /**
     * Add a constructor to this class.
     *
     * @return $this
     */
    public function withConstructor()
    {
        return $this->withFunction('__construct');
    }

    /**
     * Add a function to this class.
     *
     * @param string $name
     *
     * @return FunctionBuilder
     */
    public function withFunction($name)
    {
        $builder = new FunctionBuilder($this, $name);

        $this->functionBuilders[] = $builder;

        return $builder;
    }

    /**
     * Compile this class to a class definition.
     *
     * @return string
     */
    public function compile()
    {
        $functions = array_map(function (FunctionBuilder $builder) {
            return $builder->compile();
        }, $this->functionBuilders);

        return Util\Renderer::render(
            __DIR__ . '/templates/class.php',
            [
                'className' => $this->name,
                'classExtend' => $this->extend,
                'functions' => implode("\n", $functions),
                'constants' => $this->constants,
            ]
        );
    }

    /**
     *
     * @return mixed
     */
    public function createClass()
    {
        eval($this->compile());

        return new $this->name;
    }

    /**
     * Get the name of this class.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
