<?php

namespace App\Identifier;

use DateTime;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tlc\ReportBundle\Entity\BaseEntity;

final class DrecNormalizer implements DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return date(BaseEntity::DATE_FORMAT_DB, $data);
        // try {
        // return Uuid::fromString($data);
        // } catch (InvalidUuidStringException $e) {
        // throw new InvalidIdentifierException($e->getMessage());
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        
        if ( $type === 'int' ) {
            $date = DateTime::createFromFormat('U', $data);
            if ($date->format('Y') <= '2005')
                return false;
            return is_a($date, DateTime::class, true);
        }else false;
    }
}
