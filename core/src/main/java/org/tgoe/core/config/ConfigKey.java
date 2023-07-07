package org.tgoe.core.config;

public enum ConfigKey {
	EASYVEREIN_APIKEY("easyverein", "apikey"),
	EASYVEREIN_SERVICEURL("easyverein", "serviceurl");
	
	
	private String sectionName;
	private String valueName;
	
	private ConfigKey(String s, String v) {
		sectionName = s;
		valueName = v;
	}
	
	public String getSectionName() {
		return sectionName;
	}
	
	public String getValueName() {
		return valueName;
	}
}
