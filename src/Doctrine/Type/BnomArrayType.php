<?php

namespace App\Doctrine\Type;

use App\Entity\Bnom;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class BnomArrayType extends Type
{
  const BNOM_TYPE = 'bnom array';
  //{"(27,90)","(27,150)","(27,150)","(27,90)"}
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    settype($value, 'array');
    $result = [];
    foreach( $value as $bnom )
    {
      if ($bnom instanceof Bnom)
        $result[] = $bnom->__toString(); // преобразует (thickness, width)
    }
    return '{"' . implode('","', $result) . '"}';
  }

  public function convertToPHPValue($value, AbstractPlatform $platform) : array
  {
    $result = [];
    $arrayBnom = explode('","', trim($value, '{""}'));
    foreach($arrayBnom as $bnom)
    {
      [$thickness, $width] = explode(',', str_replace(['(', ')'], '', $bnom));

      // dd($thickness, $width, $bnom);
      $result[] = new Bnom($thickness, $width);
    }
    return $result;
  }

  public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
  {
    return 'bnom[]';
  }

  public function getName()
  {
    return self::BNOM_TYPE;
  }
}