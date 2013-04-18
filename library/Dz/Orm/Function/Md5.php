<?php

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * Based on https://github.com/beberlei/DoctrineExtensions/blob/master/lib/DoctrineExtensions/Query/Mysql/Md5.php.
 *
 * "MD5" "(" StringPrimary ")"
 */
class Dz_Orm_Function_Md5 extends FunctionNode
{
    /**
     * @var \Doctrine\ORM\Query\AST\PathExpression
     */
    protected $_stringPrimary;

    /**
     * @override
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return $sqlWalker->getConnection()->getDatabasePlatform()->getMd5Expression(
            $sqlWalker->walkStringPrimary($this->_stringPrimary)
        );
    }

    /**
     * @override
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->_stringPrimary = $parser->StringPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}