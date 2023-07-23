package org.tgoe.core.member.validation.impl;

import java.util.Arrays;
import java.util.List;

import org.tgoe.core.member.beans.MemberGroup;
import org.tgoe.core.member.validation.MemberGroupValidator;
import org.tgoe.core.member.validation.ValidationMessage;
import org.tgoe.core.member.validation.ValidationSeverity;
import org.tgoe.core.member.validation.Validator;
import org.tgoe.core.member.enums.MemberGroupCustomProperty;

public class SportsClassGroupCustomProperties extends MemberGroupValidator  {
	private static final List<MemberGroupCustomProperty> mandatoryProperties = Arrays.asList(
			MemberGroupCustomProperty.TRAINER, MemberGroupCustomProperty.WEEKDAY, MemberGroupCustomProperty.TIME,
			MemberGroupCustomProperty.LOCATION);

	public void test(MemberGroup group) {
		// this test only applies to sport course groups
		if (!group.isClassGroup()) {
			return;
		}

		for (MemberGroupCustomProperty p : mandatoryProperties) {
			String v = group.getCustomProperty(p);
			if (v == null) {
				addMessage(group, ValidationSeverity.ERROR, p.getKey() + " existiert nicht");
			} else if (v.length() == 0) {
				addMessage(group, ValidationSeverity.WARNING, p.getKey() + " existiert, jedoch ohne Wert");
			}
		}
	}

	public void test(List<MemberGroup> groups) {
		for (MemberGroup group : groups) {
			test(group);
		}
	}

	@Override
	protected String getValidationName() {
		return "Freitext-Eigenschaften der Gruppe prüfen";
	}
}
