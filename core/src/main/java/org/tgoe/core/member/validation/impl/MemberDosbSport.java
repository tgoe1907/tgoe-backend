package org.tgoe.core.member.validation.impl;

import java.util.List;

import org.tgoe.core.member.beans.*;
import org.tgoe.core.member.enums.DosbSport;
import org.tgoe.core.member.validation.SingleMemberValidator;
import org.tgoe.core.member.validation.ValidationSeverity;

public class MemberDosbSport extends SingleMemberValidator {
	@Override
	public void test(Member member) {
		List<DosbSport> sports = member.getDosbSport();

		// check number of assigned sports
		if (sports == null || sports.size() == 0) {
			addMessage(member, ValidationSeverity.ERROR, "Keine DOSB Sportart zugeordnet.");
		} else if (sports.size() > 1) {
			addMessage(member, ValidationSeverity.WARNING, "Mehrere DOSB Sportarten zugeordnet.");
		}

		// check match between group memberships and assigned sports
		if( member.getGroupMemberships() != null ) {
			List<DosbSport> sportsOfGroups = member.getGroupMemberships().stream().map(GroupMembership::getMemberGroup)
					.map(MemberGroup::getDosbSportCustomProperty).filter(m -> m != null && m.equals(DosbSport.UNKNOWN))
					.toList();
	
			// only check in case there are available sports determined by assigned groups
			if (sportsOfGroups.size() > 0) {
				boolean match = false;
				for (DosbSport s : sports) {
					match |= sportsOfGroups.contains(s);
				}
	
				if (!match)
					addMessage(member, ValidationSeverity.WARNING,
							"Zugeordnete DOSB Sportart passt nicht zu den aktiven Sportgruppen.");
			}
		}

	}

	@Override
	protected String getValidationName() {
		return "DOSB Sportart-Zuordnung";
	}

}
