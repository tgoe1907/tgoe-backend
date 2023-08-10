package org.tgoe.core.member.validation.impl;

import org.tgoe.core.member.beans.Member;
import org.tgoe.core.member.validation.SingleMemberValidator;
import org.tgoe.core.member.validation.ValidationSeverity;

public class MemberBasic extends SingleMemberValidator {
	@Override
	public void test(Member member) {
		if (member.getJoinDate() == null) {
			addMessage(member, ValidationSeverity.WARNING, "Beitrittsdatum sollte nicht leer sein.");
		}

		if (member.getDateOfBirth() == null) {
			addMessage(member, ValidationSeverity.WARNING, "Geburtsdatum sollte nicht leer sein.");
		}

		if (member.getDateOfBirth() != null && member.getJoinDate() != null
				&& member.getJoinDate().before(member.getDateOfBirth())) {
			addMessage(member, ValidationSeverity.WARNING, "Beitrittsdatum kann nicht vor dem Geburtsdatum liegen.");
		}
	}

	@Override
	protected String getValidationName() {
		return "Grundlegende Prüfungen des Mitglieds";
	}

}
