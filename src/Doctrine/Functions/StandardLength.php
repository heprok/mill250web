<?php

namespace App\Doctrine\Functions;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

final class StandardLength extends FunctionNode
{

  public $length;


  function getSql(SqlWalker $sqlWalker)
  {
    // dd($this->length, $sqlWalker);
    return 'mill.standard_length(' .
        $this->length->dispatch($sqlWalker) . ')'; // (7)
  }

  /**
   * StandardLength ::=
   *     "standard_length" "(" ArithmeticPrimary ")"
   */
  function parse(Parser $parser)
  {
    $parser->match(Lexer::T_IDENTIFIER); // (2)
    $parser->match(Lexer::T_OPEN_PARENTHESIS); // (3)
    $this->length = $parser->ArithmeticPrimary(); // (4)
    $parser->match(Lexer::T_CLOSE_PARENTHESIS); // (3)
  }
}
