<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator;

use TgoeSrv\Member\Enums\ValidationSeverity;

class ValidationMessage implements \Stringable
{

    private ValidationSeverity $severity;

    private object $targetObject;

    private string $message;
    
    public function __construct(ValidationSeverity $severity, object $targetObject, string $message) {
        $this->severity = $severity;
        $this->targetObject = $targetObject;
        $this->message = $message;
    }
    

    public function __toString(): string
    {
        return "ValidationMessage[severity=\"{$this->severity->name}\", message=\"{$this->message}\", targetObject=\"{$this->targetObject}\"";
    }
}
?>