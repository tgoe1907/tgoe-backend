package org.tgoe.core.member.validation.impl;

import java.util.HashMap;
import java.util.Map;

import org.tgoe.core.member.beans.Member;
import org.tgoe.core.member.enums.MemberDosbGender;
import org.tgoe.core.member.validation.SingleMemberValidator;
import org.tgoe.core.member.validation.ValidationSeverity;

public class MemberSalutation extends SingleMemberValidator {
	Map<String, MemberDosbGender> validSalutations;
	
	public MemberSalutation() {
		validSalutations = new HashMap<String, MemberDosbGender>();
		validSalutations.put("Frau", MemberDosbGender.FEMALE);
		validSalutations.put("Herr", MemberDosbGender.MALE);
	}
	
	@Override
	public void test(Member member) {
		boolean hasIssue = false;

		// check salutation
		if (member.getSalutation() == null || member.getSalutation().length() == 0) {
			hasIssue = true;
			addMessage(member, ValidationSeverity.ERROR, "Anrede darf nicht leer sein.");
		} else if (!validSalutations.keySet().contains(member.getSalutation())) {
			hasIssue = true;
			addMessage(member, ValidationSeverity.ERROR,
					"Anrede muss einer der Werte sein: " + String.join(", ", validSalutations.keySet()));
		}

		// check DOSB gender
		if (member.getDosbGender() == null) {
			hasIssue = true;
			addMessage(member, ValidationSeverity.ERROR, "DOSB Geschlecht leer oder ungültig.");
		} 
		
		// in case if no previous issue, check both are matching
		if (!hasIssue && !validSalutations.get(member.getSalutation()).equals(member.getDosbGender())) {
			addMessage(member, ValidationSeverity.ERROR, "DOSB Geschlecht passt nicht zur Anrede.");
		}
	}

	@Override
	protected String getValidationName() {
		return "Korrekte Anrede und DOSB Geschlecht";
	}

}
