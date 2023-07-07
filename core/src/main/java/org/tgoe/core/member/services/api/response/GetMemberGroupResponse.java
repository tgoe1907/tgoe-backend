package org.tgoe.core.member.services.api.response;

import java.util.List;

import org.tgoe.core.member.beans.MemberGroup;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public record GetMemberGroupResponse(
		int count, 
		String next, 
		List<MemberGroup> results) { }
