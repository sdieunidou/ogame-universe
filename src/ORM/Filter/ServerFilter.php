<?php

namespace App\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\Common\Annotations\Reader;

class ServerFilter extends SQLFilter
{
    protected $reader;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (empty($this->reader)) {
            return '';
        }

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "server aware" (marked with an annotation)
        $serverAware = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            'App\\ORM\\Annotation\\ServerAware'
        );

        if (!$serverAware) {
            return '';
        }

        $fieldName = $serverAware->fieldName;

        try {
            $serverId = $this->getParameter('id');
        } catch (\InvalidArgumentException $e) {
            // No server id has been defined
            return '';
        }

        if (empty($fieldName) || empty($serverId)) {
            return '';
        }

        return sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $serverId);
    }

    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }
}