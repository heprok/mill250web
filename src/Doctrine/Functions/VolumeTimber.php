<?php

namespace App\Doctrine\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

final class VolumeTimber extends FunctionNode
{

  public $length = null;
  public $diam = null;


  function getSql(SqlWalker $sqlWalker)
  {
    // dd($this->length, $sqlWalker);
    return 'mill.volume_timber(' .
        $this->length->dispatch($sqlWalker) . ',' .
        $this->diam->dispatch($sqlWalker) . ')';
  }

  /**
   * VolumeTimber ::=
   *     "volume_timber" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
   */
  function parse(Parser $parser)
  {
    $parser->match(Lexer::T_IDENTIFIER); // (2)
    $parser->match(Lexer::T_OPEN_PARENTHESIS); // (3)
    $this->length = $parser->ArithmeticPrimary(); // (4)
    $parser->match(Lexer::T_COMMA); // (3)
    $this->diam = $parser->ArithmeticPrimary(); // (4)
    $parser->match(Lexer::T_CLOSE_PARENTHESIS); // (3)
  }
}
