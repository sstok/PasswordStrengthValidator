<?php

namespace Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordRequirementsValidator extends ConstraintValidator
{
    /**
     * @param string                          $value
     * @param PasswordRequirements|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            $value = '';
        }

        if ($constraint->minLength > 0 && (strlen($value) < $constraint->minLength)) {
            $this->context->addViolation($constraint->tooShortMessage, array('{{length}}' => $constraint->minLength), $value);
        }

        if ($constraint->requireLetters && !preg_match('/\pL/', $value)) {
            $this->context->addViolation($constraint->missingLettersMessage, array(), $value);
        }

        if ($constraint->requireCaseDiff && !preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/', $value)) {
            $this->context->addViolation($constraint->requireCaseDiffMessage);
        }

        if ($constraint->requireNumbers && !preg_match('/\pN/', $value)) {
            $this->context->addViolation($constraint->missingNumbersMessage, array(), $value);
        }

        if ($constraint->requireSpecialCharacter &&
            !preg_match('/[^p{Ll}\p{Lu}\pL]/', $value)
        ) {
            $this->context->addViolation($constraint->missingSpecialCharacterMessage, array(), $value);
        }
    }
}
