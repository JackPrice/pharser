<?php namespace Pharser\Parser\ClassBuilder;

/**
 * Class FunctionBuilder
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class FunctionBuilder
{
    const VISIBILITY_PRIVATE = 'private';
    const VISIBILITY_PROTECTED = 'protected';
    const VISIBILITY_PUBLIC = 'public';

    /**
     * @var ClassBuilder
     */
    private $classBuilder;

    /**
     * The name of this function.
     *
     * @var string
     */
    private $name;

    /**
     * The visibility of this function.
     *
     * @var string
     */
    private $visibility = FunctionBuilder::VISIBILITY_PUBLIC;

    /**
     * Arguments for this function.
     *
     * @var ArgumentBuilder[]
     */
    private $argumentBuilders = [];

    /**
     * The body of this function.
     *
     * @var string
     */
    private $body = '';

    /**
     * FunctionBuilder constructor.
     *
     * @param ClassBuilder $classBuilder
     * @param string       $name
     */
    public function __construct(ClassBuilder &$classBuilder, $name)
    {
        $this->classBuilder = $classBuilder;
        $this->name = $name;
    }

    /**
     * Finalise this builder.
     *
     * @return ClassBuilder
     */
    public function end()
    {
        return $this->classBuilder;
    }

    /**
     * Add an argument to this function definition.
     *
     * @param string $name
     *
     * @return ArgumentBuilder
     */
    public function withArgument($name)
    {
        $builder = new ArgumentBuilder($this, $name);

        $this->argumentBuilders[] = $builder;

        return $builder;
    }

    /**
     * Set the body of this function.
     *
     * @param $body
     *
     * @return $this
     */
    public function withBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Compile this function to a class definition.
     *
     * @return string
     */
    public function compile()
    {
        $arguments = array_map(function (ArgumentBuilder $builder) {
            return $builder->compile();
        }, $this->argumentBuilders);

        return Util\Renderer::render(
            __DIR__ . '/templates/function.php',
            [
                'functionName' => $this->name,
                'functionVisibility' => $this->visibility,
                'functionArguments' => implode(', ', $arguments),
                'functionBody' => $this->body,
            ]
        );
    }
}
