<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Orm
 * @subpackage Function
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see \Doctrine\ORM\Query\AST\Functions\FunctionNode
 */
require_once 'Doctrine/ORM/Query/AST/Functions/FunctionNode.php';

/**
 * @see \Doctrine\ORM\Query\SqlWalker
 */
require_once 'Doctrine/ORM/Query/SqlWalker.php';

/**
 * Based on https://github.com/beberlei/DoctrineExtensions/blob/master/lib/DoctrineExtensions/Query/Mysql/Md5.php.
 *
 * "MD5" "(" StringPrimary ")"
 *
 * @category   Dz
 * @package    Dz_Orm
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Orm_Function_Md5 extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
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
        /**
         * @see \Doctrine\ORM\Query\Lexer
         */
        require_once 'Doctrine/ORM/Query/Lexer.php';

        $lexer = $parser->getLexer();

        $parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
        $parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);

        $this->_stringPrimary = $parser->StringPrimary();

        $parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
    }
}