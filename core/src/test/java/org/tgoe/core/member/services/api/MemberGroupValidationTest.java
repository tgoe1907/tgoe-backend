package org.tgoe.core.member.services.api;

import java.util.List;

import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.BeforeAll;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tgoe.core.member.beans.MemberGroup;
import org.tgoe.core.member.validation.impl.SportsClassGroupCustomProperties;

public class MemberGroupValidationTest {
	private static final Logger logger = LoggerFactory.getLogger(MemberGroupValidationTest.class);
	private static List<MemberGroup> memberGroups;
	
	@BeforeAll
	public static void prepareTestData() throws EasyVereinException {
		memberGroups = EasyVerein.getInstance().getMemberGroups();
	}
	
	@Test
	void testValidationSportsClassGroupCustomProperties() {
		SportsClassGroupCustomProperties v = new SportsClassGroupCustomProperties();
		v.test(memberGroups);
		
		for( Object m : v.getMessages()) {
			logger.info(m.toString());
		}
	}
}
