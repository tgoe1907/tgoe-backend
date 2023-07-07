package org.tgoe.core.member.beans;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public record MemberGroup (
		String id, 
		@JsonProperty("short")
		String key, 
		String name) {

}