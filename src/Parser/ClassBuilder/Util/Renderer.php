<?php namespace Pharser\Parser\ClassBuilder\Util;

/**
 * Class Renderer
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class Renderer
{
    /**
     * Render the given template.
     *
     * @param string $template
     * @param array  $variables
     *
     * @return string
     */
    public static function render($template, array $variables)
    {
        extract($variables, EXTR_SKIP);

        ob_start();
        include $template;

        return ob_get_clean();
    }
}
