package org.tgoe.core.member.validation.impl;

import org.tgoe.core.member.beans.Member;
import org.tgoe.core.member.validation.SingleMemberValidator;
import org.tgoe.core.member.validation.ValidationSeverity;

public class MembershipNumber extends SingleMemberValidator {
	private static final String VALIDATION_REGEX = "[0-9]+";

	@Override
	public void test(Member member) {
		// check for empty membership number
		if (member.getMembershipNumber() == null || member.getMembershipNumber().length() == 0) {
			addMessage(member, ValidationSeverity.ERROR, "Mitgliedsnummer darf nicht leer sein.");
		} else if (!member.getMembershipNumber().matches(VALIDATION_REGEX)) {
			addMessage(member, ValidationSeverity.ERROR, "Mitgliedsnummer darf nur Ziffern enthalten.");
		}

		// membership number should match login name (except admins)
		if (!member.isAdmin() && !member.getLoginName().equals(member.getMembershipNumber())) {
			addMessage(member, ValidationSeverity.WARNING,
					"Benutzername und Mitgliedsnummer sollten übereinstimmen (außer bei Admins).");
		}
	}

	@Override
	protected String getValidationName() {
		return "Prüfung Mitgliedsnummer";
	}

}
