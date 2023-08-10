package org.tgoe.core.member.services.api;

import java.util.List;

import org.junit.jupiter.api.BeforeAll;
import org.junit.jupiter.api.Test;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tgoe.core.member.beans.Member;
import org.tgoe.core.member.validation.impl.*;

public class MemberValidationTest {
	private static final Logger logger = LoggerFactory.getLogger(MemberValidationTest.class);
	private static List<Member> members;
	
	@BeforeAll
	public static void prepareTestData() throws EasyVereinException {
		members = EasyVerein.getInstance().getAllMembers();
	}
	
	@Test
	void testSalutationValidation() {
		MemberSalutation v = new MemberSalutation();
		v.test(members);
		
		for( Object m : v.getMessages()) {
			logger.info(m.toString());
		}		
	}
	
	@Test
	void testMembershipNumberValidation() {
		MembershipNumber v = new MembershipNumber();
		v.test(members);
		
		for( Object m : v.getMessages()) {
			logger.info(m.toString());
		}		
	}	
	
	@Test
	void testMembershipFeeAssignment() {
		MembershipFeeAssignment v = new MembershipFeeAssignment();
		v.test(members);
		
		for( Object m : v.getMessages()) {
			logger.info(m.toString());
		}		
	}
	
	@Test
	void testMembershipNumberUnique() {
		MembershipNumberUnique v = new MembershipNumberUnique();
		v.test(members);
		
		for( Object m : v.getMessages()) {
			logger.info(m.toString());
		}		
	}
	
	@Test
	void testMemberBasic() {
		MemberBasic v = new MemberBasic();
		v.test(members);
		
		for( Object m : v.getMessages()) {
			logger.info(m.toString());
		}		
	}	
	
	@Test
	void testMemberDosbSport() {
		MemberDosbSport v = new MemberDosbSport();
		v.test(members);
		
		for( Object m : v.getMessages()) {
			logger.info(m.toString());
		}		
	}	
}
