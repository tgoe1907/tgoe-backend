package org.tgoe.core.member.enums;

public enum MemberGroupCustomProperty {
	TRAINER("Trainer"), WEEKDAY("Tag"), TIME("Zeit"), LOCATION("Ort"), DOSB_SPORT("DOSB-Sportart");

	private String key;

	private MemberGroupCustomProperty(String key) {
		this.key = key;
	}

	public String getKey() {
		return key;
	}

	/**
	 * Find enum value matching the key. Uses ignore case string comparison.
	 * 
	 * @param key Key to search for.
	 * @return Enumeration value or NULL in case key does not exist.
	 */
	public static MemberGroupCustomProperty findByKey(String key) {
		for( MemberGroupCustomProperty p : MemberGroupCustomProperty.values() ) {
			if( p.getKey().equalsIgnoreCase(key) ) return p;
		}
		
		return null;
	}
}
