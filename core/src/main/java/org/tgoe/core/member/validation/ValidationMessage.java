package org.tgoe.core.member.validation;

public record ValidationMessage(ValidationSeverity severity, String objectName, String validationName, String message) {

	@Override
	public String toString() {
		return String.format("ValidationMessage[severity=%s, objectName=%s, validationName=%s, message=%s]", severity,
				objectName, validationName, message);
	}

}
