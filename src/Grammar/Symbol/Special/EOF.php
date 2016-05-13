<?php namespace Pharser\Grammar\Symbol\Special;

use Pharser\Grammar\Symbol\Terminal;

/**
 * An EOF symbol.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class EOF extends Terminal
{
    /**
     * @inheritdoc
     */
    protected $identifier = '$';

    /**
     * Override the default constructor and take no arguments here.
     */
    public function __construct()
    {
    }
}
