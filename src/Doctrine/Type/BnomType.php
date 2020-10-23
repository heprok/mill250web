<?php

namespace App\Doctrine\Type;

use App\Entity\Bnom;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class BnomType extends Type
{
  const BNOM_TYPE = 'bnom';

  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    return sprintf('[%s, $s]', $value->thickness, $value->width);
  }

  public function convertToPHPValue($value, AbstractPlatform $platform) : Bnom
  {
    [$thickness, $width] = explode(',', str_replace(['[', ']'], '', $value));
    return new Bnom($thickness, $width);
  }

  public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
  {
    return self::BNOM_TYPE;
  }

  public function getName()
  {
    return self::BNOM_TYPE;
  }
}