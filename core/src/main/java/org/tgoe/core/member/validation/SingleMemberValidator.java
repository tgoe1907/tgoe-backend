package org.tgoe.core.member.validation;

import java.util.List;

import org.tgoe.core.member.beans.Member;

public abstract class SingleMemberValidator extends MemberListValidator {
	public abstract void test(Member member);
	
	public void test(List<Member> members) {
		for(Member m : members) {
			test(m);
		}
	}
	
	//TODO: implement logic to find all tests of a type and execute in a loop
}
