package org.tgoe.core.member.validation;

import java.util.ArrayList;
import java.util.List;

public abstract class Validator {
	protected List<ValidationMessage> messages = new ArrayList<ValidationMessage>();
	protected abstract String getValidationName();
	
	public List<ValidationMessage> getMessages() {
		return messages;
	}
	
	protected void addMessage(String objectName, ValidationSeverity severity, String messageText) {
		messages.add(new ValidationMessage(severity, objectName, getValidationName(), messageText));
	}	
	
}
