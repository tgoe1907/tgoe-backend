<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator;

use TgoeSrv\Member\Enums\ValidationSeverity;

class ValidationMessage implements \Stringable
{

    private ValidationSeverity $severity;

    private object $targetObject;

    private string $message;
    private string $validatorName;
    
    public function __construct(ValidationSeverity $severity, string $validatorName, object $targetObject, string $message) {
        $this->validatorName = $validatorName;
        $this->severity = $severity;
        $this->targetObject = $targetObject;
        $this->message = $message;
    }
    

    public function __toString(): string
    {
        return "ValidationMessage[severity=\"{$this->severity->name}\", validatorName=\"{$this->validatorName}\", message=\"{$this->message}\", targetObject=\"{$this->targetObject}\"";
    }
}
