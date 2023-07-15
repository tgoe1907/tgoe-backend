package org.tgoe.core.member.services.api.response;

import java.util.List;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public record EasyvereinListResponse<T>(
		int count, 
		String next, 
		List<T> results) { }
