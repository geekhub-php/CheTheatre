<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProducerConstraint extends Constraint
{
    public string $noProducer = 'performance_must_have_a_producer';
    public string $ambiguousProducer = 'ambiguous_producer';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return ProducerValidator::class;
    }
}
