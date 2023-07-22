package org.tgoe.core.member.validation;

import java.util.List;

import org.tgoe.core.member.beans.Member;

public interface SingleMemberValidator {
	public void test(List<Member> m);
	public void test(Member m);

}
