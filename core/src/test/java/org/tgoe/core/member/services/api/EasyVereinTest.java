package org.tgoe.core.member.services.api;

import java.util.List;

import org.junit.jupiter.api.Test;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tgoe.core.member.beans.MemberGroup;

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
}
