package org.tgoe.core.member.validation;

import java.util.ArrayList;
import java.util.List;

public abstract class Validator {
	protected List<ValidationMessage> messages = new ArrayList<ValidationMessage>();
	
	public List<ValidationMessage> getMessages() {
		return messages;
	}
	
}
