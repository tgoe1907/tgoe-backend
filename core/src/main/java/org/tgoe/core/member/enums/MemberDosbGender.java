package org.tgoe.core.member.enums;

public enum MemberDosbGender {
	MALE("m"),
	FEMALE("w"),
	DIVERSE("d");
	
	private String key;
	
	private MemberDosbGender(String key) {
		this.key=key;
	}
	
	public String getKey() {
		return key;
	}
	
	/**
	 * Find enum value matching the key. Uses case-sensitive string comparison.
	 * 
	 * @param key Key to search for.
	 * @return Enumeration value or NULL in case key does not exist.
	 */
	public static MemberDosbGender findByKey(String key) {
		for( MemberDosbGender p : MemberDosbGender.values() ) {
			if( p.getKey().equals(key) ) return p;
		}
		
		return null;
	}	
}
