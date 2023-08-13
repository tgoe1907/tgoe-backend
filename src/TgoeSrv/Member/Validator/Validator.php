<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator;

use TgoeSrv\Member\Enums\ValidationSeverity;

abstract class Validator
{

    protected array $validatorMessages = array();

    protected abstract function getValidatorName(): string;

    protected function addMessage(ValidationSeverity $severity, object $targetObject, string $message): void
    {
        $this->validatorMessages[] = new ValidationMessage($severity, $this->getValidatorName(), $targetObject, $message);
    }

    public function getMessages(): array
    {
        return $this->validatorMessages;
    }
}
?>