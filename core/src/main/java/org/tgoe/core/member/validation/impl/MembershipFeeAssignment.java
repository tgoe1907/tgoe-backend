package org.tgoe.core.member.validation.impl;

import java.time.LocalDate;
import java.time.ZoneId;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import org.tgoe.core.member.beans.GroupMembership;
import org.tgoe.core.member.beans.Member;
import org.tgoe.core.member.beans.MemberGroup;
import org.tgoe.core.member.validation.SingleMemberValidator;
import org.tgoe.core.member.validation.ValidationSeverity;

public class MembershipFeeAssignment extends SingleMemberValidator {
	List<String> deprecatedGroups;
	List<String> memberFeeGroups;
	LocalDate effectiveCancellationDeadline;

	public MembershipFeeAssignment() {
		deprecatedGroups = Arrays.asList("B-GF");
		memberFeeGroups = Arrays.asList("B-FA", "B-GB", "B-EM", "B-GS", "B-GF");

		// calculate cancellation date to ignore members for this validation
		if (LocalDate.now().getMonthValue() > 6) {
			effectiveCancellationDeadline = LocalDate.of(LocalDate.now().getYear() + 1, 1, 1);
		} else {
			effectiveCancellationDeadline = LocalDate.of(LocalDate.now().getYear(), 1, 1);
		}
	}

	@Override
	public void test(Member member) {
		List<String> memberGroupKeys;
		if( member.getGroupMemberships() == null ) {
			memberGroupKeys = new ArrayList<String>();
		} else {
			memberGroupKeys = member.getGroupMemberships().stream().map(GroupMembership::getMemberGroup)
					.map(MemberGroup::getKey).toList();
		}

		// check member has no deprecated groups
		List<String> dep = memberGroupKeys.stream().filter( g -> deprecatedGroups.contains(g)).toList();
		if( !dep.isEmpty() ) {
			addMessage(member, ValidationSeverity.ERROR, "Mitglied hat veraltete Gruppen: " + String.join(",", dep));
		}

		// exit for members who already cancelled
		if (member.getResignationDate() != null) {
			LocalDate localDate = member.getResignationDate().toInstant().atZone(ZoneId.systemDefault()).toLocalDate();
			if (localDate.isBefore(effectiveCancellationDeadline)) {
				// cancelled before next charging of member fee, additional checks can be
				// skipped
				return;
			}
		}

		// check member has at least one member fee group
		if( memberGroupKeys.stream().filter( g -> memberFeeGroups.contains(g)).count() == 0 ) {
			addMessage(member, ValidationSeverity.ERROR, "Mitglied hat keine Grundbeitrags-Gruppe");
		}
	}

	@Override
	protected String getValidationName() {
		return "Prüfung Beitragsgruppen";
	}

}
