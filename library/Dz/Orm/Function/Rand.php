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
 * Based on https://gist.github.com/919465.
 *
 * @category   Dz
 * @package    Dz_Orm
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Orm_Function_Rand extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'RAND()';
    }

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        /**
         * @see \Doctrine\ORM\Query\Lexer
         */
        require_once 'Doctrine/ORM/Query/Lexer.php';

        $parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
        $parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);
        $parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
    }
}