package org.tgoe.core.member.validation;

import java.util.List;

import org.tgoe.core.member.beans.MemberGroup;

public interface MemberGroupValidator {
	public void test(List<MemberGroup> m);
	public void test(MemberGroup m);
}
