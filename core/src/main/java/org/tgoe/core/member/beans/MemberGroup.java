package org.tgoe.core.member.beans;

import java.util.HashMap;
import java.util.Map;

import org.tgoe.core.member.enums.MemberGroupCustomProperty;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class MemberGroup {
	public final static String easyvereinQueryString="{id,name,short,descriptionOnInvoice,orderSequence}";

	private static final String FEE_GROUP_PREFIX = "B-";
	
	private long id;
	private String key;
	private String name;
	private String description;
	private int orderSequence;
	
	private Map<MemberGroupCustomProperty,String> customPropertyCache;
	
	public long getId() {
		return id;
	}
	
	@JsonProperty("id")
	private void setId(long id) {
		this.id = id;
	}
	
	public String getKey() {
		return key;
	}
	
	@JsonProperty("short")
	private void setKey(String key) {
		this.key = key;
	}
	
	public String getName() {
		return name;
	}
	
	@JsonProperty("name")
	private void setName(String name) {
		this.name = name;
	}

	public String getDescription() {
		return description;
	}

	@JsonProperty("descriptionOnInvoice")
	private void setDescription(String description) {
		this.description = description;
		
		//clear cache when description value changes		
		customPropertyCache = null;
	}

	public int getOrderSequence() {
		return orderSequence;
	}

	@JsonProperty("orderSequence")
	private void setOrderSequence(int orderSequence) {
		this.orderSequence = orderSequence;
	}

	@Override
	public String toString() {
		return String.format("MemberGroup[id=%s, key=%s, name=%s]", id, key, name);
	}
	
	/**
	 * In description, we use key/value format to specify custom properties.
	 * Use this method to get a value.
	 * 
	 * @param p
	 * @return Value of custom property. Empty string in case it exists but has no value. NULL in case it does not exist.
	 */
	public String getCustomProperty(MemberGroupCustomProperty p) {
		if( customPropertyCache == null ) {
			//parse description and initialize customPropertyCache
			customPropertyCache = new HashMap<MemberGroupCustomProperty,String>();
			if( description != null) {
				String[] lines = description.split("\n");
				for( String line : lines) {
					String[] parts = line.split("=", 2);
					MemberGroupCustomProperty prop = MemberGroupCustomProperty.findByKey(parts[0].trim());
					if( prop != null ) {
						String v = parts.length > 1 ? parts[1] : "";
						customPropertyCache.put(prop, v);
					}
				}
			}
		}
		
		return customPropertyCache.get(p);
	}
	
	/**
	 * Check if this group is used for charging membership fee.
	 * @return
	 */
	public boolean isMemberFeeGroup() {
		return key != null && key.startsWith(FEE_GROUP_PREFIX);
	}

	/**
	 * Check if this group represents a sports class.
	 * @return
	 */	
	public boolean isClassGroup() {
		return !isMemberFeeGroup();
	}
}