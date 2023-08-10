package org.tgoe.core.member.validation.impl;

import java.util.HashMap;
import java.util.List;

import org.tgoe.core.member.beans.Member;
import org.tgoe.core.member.validation.MemberListValidator;
import org.tgoe.core.member.validation.ValidationSeverity;

public class MembershipNumberUnique extends MemberListValidator {

	@Override
	public void test(List<Member> members) {
		HashMap<String, Member> usedNumbers = new HashMap<String, Member>();

		for (Member m : members) {
			if (usedNumbers.containsKey(m.getMembershipNumber())) {
				String msg = "Mitgliedsnummer " + m.getMembershipNumber() + " bereits verwendet bei "
						+ usedNumbers.get(m.getMembershipNumber()).getFullname();
				addMessage(m, ValidationSeverity.ERROR, msg);
			} else {
				usedNumbers.put(m.getMembershipNumber(), m);
			}
		}
	}

	@Override
	protected String getValidationName() {
		return "Einmalige Mitgliedsnummern";
	}

}
