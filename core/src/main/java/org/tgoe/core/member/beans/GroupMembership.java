package org.tgoe.core.member.beans;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class GroupMembership {
	private MemberGroup memberGroup;

	public MemberGroup getMemberGroup() {
		return memberGroup;
	}

	@JsonProperty("memberGroup")
	private void setMemberGroup(MemberGroup memberGroup) {
		this.memberGroup = memberGroup;
	}
}
