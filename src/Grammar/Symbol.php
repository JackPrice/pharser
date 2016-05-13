<?php namespace Pharser\Grammar;

/**
 * A lexical element in a formal grammar.
 *
 * @author Jack Price <jackprice@outlook.com>
 *
 * @see    https://en.wikipedia.org/wiki/Terminal_and_nonterminal_symbols
 */
abstract class Symbol
{
    const MATCH_TYPE_CHARACTER = 1;
    const MATCH_TYPE_STRING = 2;
    const MATCH_TYPE_REGEXP = 3;

    /**
     * A grammar-unique identifier for this symbol.
     *
     * @var string
     */
    protected $identifier;

    /**
     * The type of matcher for this symbol.
     *
     * @var int
     */
    protected $matchType;

    /**
     * The match for this symbol - either a character, a string, or a regexp.
     *
     * @see Symbol::matchType
     *
     * @var string
     */
    protected $match;

    /**
     * Symbol constructor.
     *
     * @param string $identifier
     * @param int    $matchType
     * @param string $match
     */
    public function __construct($identifier, $matchType, $match)
    {
        $this->identifier = $identifier;
        $this->matchType = $matchType;
        $this->match = $match;
    }

    /**
     * Get the unique identifier for this symbol.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Get the match type of this symbol.
     *
     * @return int
     */
    public function getMatchType()
    {
        return $this->matchType;
    }

    /**
     * Match this token against a string.
     *
     * @param $token
     *
     * @return string|null
     */
    public function doMatch($token)
    {
        if ($this->isCharacterMatch()) {
            if ($token[0] === $this->match) {
                return $token[0];
            } else {
                return null;
            }
        }

        if ($this->isStringMatch()) {
            if (strpos($token, $this->match) === 0) {
                return $this->match;
            } else {
                return null;
            }
        }

        if ($this->isRegexpMatch()) {
            if (preg_match($this->match, $token, $matches) === 1) {
                return $matches[0];
            } else {
                return null;
            }
        }
    }

    /**
     * Returns true if this symbol is a character matcher.
     *
     * @return bool
     */
    public function isCharacterMatch()
    {
        return $this->matchType === Symbol::MATCH_TYPE_CHARACTER;
    }

    /**
     * Returns true if this symbol is a string matcher.
     *
     * @return bool
     */
    public function isStringMatch()
    {
        return $this->matchType === Symbol::MATCH_TYPE_STRING;
    }

    /**
     * Returns true if this symbol is a regexp matcher.
     *
     * @return bool
     */
    public function isRegexpMatch()
    {
        return $this->matchType === Symbol::MATCH_TYPE_REGEXP;
    }
}
