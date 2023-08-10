package org.tgoe.core.member.validation;

import java.util.List;

import org.tgoe.core.member.beans.Member;

public abstract class MemberListValidator extends Validator {
	public abstract void test(List<Member> members);
	
	protected void addMessage(Member member, ValidationSeverity severity, String messageText) {
		String objectName = member.getMembershipNumber() + " / " + member.getFullname();
		super.addMessage(objectName, severity, messageText);
	}	
	
	//TODO: implement logic to find all tests of a type and execute in a loop
}
