<?php

namespace App\Doctrine\Type;

use DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class DoctrineDateTimeType extends DateTimeType
{
    /**
     * {@inheritdoc}
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }
        // $val = '2020-06-19 01:51:47.68+02';

        $val = \DateTime::createFromFormat('Y-m-d H:i:s.u+O', $value);
        if ( ! ($val instanceof \DateTime)) {
          $val = \DateTime::createFromFormat('Y-m-d H:i:s+O', $value);
          if ( ! ($val instanceof \DateTime)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
      }

        return $val->format('c');
    }
}