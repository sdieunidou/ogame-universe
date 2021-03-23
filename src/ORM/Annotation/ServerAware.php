<?php

namespace App\ORM\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class ServerAware
{
    public $fieldName;
}