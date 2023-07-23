package org.tgoe.core.member.validation;

import java.util.List;

import org.tgoe.core.member.beans.MemberGroup;

public abstract class MemberGroupValidator extends Validator {
	public abstract void test(MemberGroup group);
	
	public void test(List<MemberGroup> groups) {
		for (MemberGroup group : groups) {
			test(group);
		}
	}

	protected void addMessage(MemberGroup group, ValidationSeverity severity, String messageText) {
		String objectName = group.getKey() + " / " + group.getName();
		super.addMessage(objectName, severity, messageText);
	}
	
	//TODO: implement logic to find all tests of a type and execute in a loop
}
