<?php namespace Pharser\Parser\ClassBuilder;

/**
 * Class ArgumentBuilder
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class ArgumentBuilder
{
    /**
     * @var FunctionBuilder
     */
    private $functionBuilder;

    /**
     * The name of this function.
     *
     * @var string
     */
    private $name;

    /**
     * If this argument is to be passed by reference.
     *
     * @var string
     */
    private $byReference = '';

    /**
     * A type-hint for this parameter.
     *
     * @var string
     */
    private $hint = null;

    /**
     * A default value for this parameter.
     *
     * @var string
     */
    private $default = null;

    /**
     * FunctionBuilder constructor.
     *
     * @param FunctionBuilder $functionBuilder
     * @param string       $name
     */
    public function __construct(FunctionBuilder &$functionBuilder, $name)
    {
        $this->functionBuilder = $functionBuilder;
        $this->name = $name;
    }

    /**
     * Mark this argument as being passed by reference.
     *
     * @return $this
     */
    public function byReference()
    {
        $this->byReference = '&';

        return $this;
    }

    /**
     * Give this argument a type hint.
     *
     * @param string $hint
     *
     * @return $this
     */
    public function withTypeHint($hint)
    {
        $this->hint = $hint;

        return $this;
    }

    /**
     * Give this argument a default value.
     *
     * @param string $default
     *
     * @return $this
     */
    public function withDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Finalise this builder.
     *
     * @return FunctionBuilder
     */
    public function end()
    {
        return $this->functionBuilder;
    }

    /**
     * Compile this function to a class definition.
     *
     * @return string
     */
    public function compile()
    {
        return Util\Renderer::render(
            __DIR__ . '/templates/argument.php',
            [
                'name' => $this->name,
                'byReference' => $this->byReference,
                'hint' => $this->hint,
                'default' => $this->default,
            ]
        );
    }
}
