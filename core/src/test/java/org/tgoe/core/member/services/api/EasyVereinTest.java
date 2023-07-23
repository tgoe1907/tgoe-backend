package org.tgoe.core.member.services.api;

import static org.junit.jupiter.api.Assertions.assertNotNull;

import java.util.List;

import org.junit.jupiter.api.Test;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tgoe.core.member.beans.GroupMembership;
import org.tgoe.core.member.beans.Member;
import org.tgoe.core.member.beans.MemberGroup;
import org.tgoe.core.member.enums.MemberGroupCustomProperty;

public class EasyVereinTest {
	private static final Logger logger = LoggerFactory.getLogger(EasyVereinTest.class);
	
	@Test
	void testGetMemberGroups() throws EasyVereinException {
		List<MemberGroup> memberGroups = EasyVerein.getInstance().getMemberGroups();

		for( MemberGroup m : memberGroups ) {
			logger.info(m.toString());
		}
		
		assert(memberGroups.size() > 30);		
	}
	
	@Test
	void testMemberGroupCustomProperties() throws EasyVereinException {
		int found = 0;
		List<MemberGroup> memberGroups = EasyVerein.getInstance().getMemberGroups();

		for( MemberGroup m : memberGroups ) {
			logger.info(m.toString());
			
			for( MemberGroupCustomProperty p : MemberGroupCustomProperty.values() ) {
				String v =  m.getCustomProperty(p);
				if( v == null ) {
					v = "<does not exist>";
				}
				else if( v.length() == 0 ) {
					v = "<exists and has no value>";
				} else {
					found++;
				}
				logger.info(" --> {} '{}' = {} ", p.toString(), p.getKey(), v );
			}
		}
		
		assert(found > 20);		
	}
	
	
	@Test
	void testGetMembersForGroup() throws EasyVereinException {
		long groupId = 119583079; //B-AK
		List<Member> members = EasyVerein.getInstance().findMembersOfGroup(groupId);
		
		assert(members.size() > 10);
		
		for( Member m : members ) {
			logger.info(m.toString());
			
			GroupMembership test = m.getGroupMemberships().stream()
			.filter( gm -> gm.getMemberGroup().getId() == groupId )
			.findFirst()
			.orElse(null);
			
			assertNotNull(test);
		}		
	}
}
