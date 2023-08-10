package org.tgoe.core.member.enums;

public enum DosbSport {
	UNKNOWN("XX","unbekannt"),
	JUDO("B16", "Judo"),
	GYMNASTICS("B36", "Turnen"),
	ATHLETICS("B18", "Leichtathletik"),
	CYCLING("B20", "Radsport"),
	LAWN_SPORTS("C21", "Rasenkraftsport"),
	SKI("B29","Ski"),
	VOLLEYBALL("B37", "Volleyball"),
	DISABLED_SPORTS("C04","Behindertensport");
	
	String key;
	String name;
	
	private DosbSport( String key, String name ) {
		this.key = key;
		this.name = name;
	}
	
	
	public String getKey() {
		return key;
	}


	public String getName() {
		return name;
	}


	/**
	 * Find enum value matching the key. Uses ignore case string comparison.
	 * 
	 * @param key Key to search for.
	 * @return Enumeration value or NULL in case key does not exist.
	 */
	public static DosbSport findByKey(String key) {
		for( DosbSport p : DosbSport.values() ) {
			if( p.getKey().equalsIgnoreCase(key) ) return p;
		}
		
		return null;
	}		
}
