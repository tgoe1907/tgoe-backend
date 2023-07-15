package org.tgoe.core.member.services.api;

import java.util.Arrays;
import java.util.Collections;
import java.util.List;

import javax.ws.rs.ProcessingException;
import javax.ws.rs.core.GenericType;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;

import org.apache.cxf.ext.logging.LoggingFeature;
import org.apache.cxf.jaxrs.client.WebClient;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tgoe.core.config.ConfigKey;
import org.tgoe.core.config.ConfigManager;
import org.tgoe.core.member.beans.Member;
import org.tgoe.core.member.beans.MemberGroup;
import org.tgoe.core.member.services.api.response.EasyvereinListResponse;

import com.fasterxml.jackson.jaxrs.json.JacksonJsonProvider;


public class EasyVerein {
	private static final EasyVerein obj = new EasyVerein();
	private static final int MAX_PAGE_QUERIES = 10;
	private static final int PAGE_SIZE = 100;
	
	private static final Logger logger = LoggerFactory.getLogger(EasyVerein.class);
	
	private EasyVerein() {
		
	}
	
	public static EasyVerein getInstance() {
		return obj;
	}
	
	private WebClient prepareWebClient() {
		String url = ConfigManager.getValue(ConfigKey.EASYVEREIN_SERVICEURL);
		String auth = ConfigManager.getValue(ConfigKey.EASYVEREIN_APIKEY);
		
		
		
		WebClient client = WebClient
				.create(url, Collections.singletonList(new JacksonJsonProvider()), Arrays.asList(new LoggingFeature()), null)
				.header("Authorization", "Token " + auth)
				.accept(MediaType.APPLICATION_JSON_TYPE);	
		
		return client;
	}	
	
	/**
	 * Fetch all member groups.
	 * 
	 * @return List of member groups
	 * @throws EasyVereinException
	 */
	public List<MemberGroup> getMemberGroups() throws EasyVereinException {
		List<MemberGroup> list = null;
		int page = 0;
		
		EasyvereinListResponse<MemberGroup> resObj;
		
		do {
			page++;
			
			logger.debug("getMemberGroups - reading page {}", page);
			
			WebClient client = prepareWebClient();
			Response res = client
					.path("member-group")
					.query("limit", PAGE_SIZE)
					.query("query", MemberGroup.easyvereinQueryString)
					.query("ordering", "orderSequence")
					.query("page", page)
					.get();
			
			
			if( res.getStatus() != 200) {
				throw new EasyVereinException("getMemberGroups - service returned unexpected status - " + res.getStatus());
			}
	
			try {
				resObj = res.readEntity(new GenericType<EasyvereinListResponse<MemberGroup>>() {});
			} catch (ProcessingException e) {
				throw new EasyVereinException("getMemberGroups - cannot map response data structure", e);
			}
			
			//add result records 
			if( list == null ) {
				list = resObj.results();
			} else {
				list.addAll(resObj.results());
			}
		}
		while(resObj.next() != null && page < MAX_PAGE_QUERIES);
		
		//log warning in case we did not query all pages
		if( resObj.next() != null ) {
			logger.warn("getMemberGroups - reached max pages, not all records will be returned");
		}
		
		return list;
	}

	
	/**
	 * Fetch all members of a member group
	 * 
	 * @param groupId ID of member group.
	 * @return List of members.
	 * @throws EasyVereinException 
	 */
	public List<Member> findMembersOfGroup(long groupId) throws EasyVereinException {
		List<Member> list = null;
		int page = 0;
		
		EasyvereinListResponse<Member> resObj;
		
		do {
			page++;
			
			logger.debug("getMemberGroups - reading page {}", page);
			
			WebClient client = prepareWebClient();
			Response res = client
					.path("member")
					.query("limit", PAGE_SIZE)
					.query("query", Member.easyvereinQueryString)
					.query("memberGroups", groupId)
					.query("page", page)
					.get();
			
			
			if( res.getStatus() != 200) {
				throw new EasyVereinException("findMembersOfGroup - service returned unexpected status - " + res.getStatus());
			}
	
			try {
				resObj = res.readEntity(new GenericType<EasyvereinListResponse<Member>>() {});
			} catch (ProcessingException e) {
				throw new EasyVereinException("findMembersOfGroup - cannot map response data structure", e);
			}
			
			//add result records 
			if( list == null ) {
				list = resObj.results();
			} else {
				list.addAll(resObj.results());
			}
		}
		while(resObj.next() != null && page < MAX_PAGE_QUERIES);
		
		//log warning in case we did not query all pages
		if( resObj.next() != null ) {
			logger.warn("getMemberGroups - reached max pages, not all records will be returned");
		}
		
		return list;
	}
}
