package org.tgoe.core.member.validation;

import java.util.List;

import org.tgoe.core.member.beans.Member;

public abstract class SingleMemberValidator extends Validator {
	public abstract void test(Member member);
	
	public void test(List<Member> members) {
		for(Member m : members) {
			test(m);
		}
	}
	
	protected void addMessage(Member member, ValidationSeverity severity, String messageText) {
		String objectName = member.getMembershipNumber() + " / " + member.getFirstName() + " " + member.getFamilyName();
		super.addMessage(objectName, severity, messageText);
	}	
	
	//TODO: implement logic to find all tests of a type and execute in a loop
}
