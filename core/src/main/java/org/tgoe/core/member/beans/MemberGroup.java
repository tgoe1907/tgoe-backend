package org.tgoe.core.member.beans;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class MemberGroup {
	public final static String easyvereinQueryString="{id,name,short,descriptionOnInvoice,orderSequence}";
	
	private long id;
	private String key;
	private String name;
	private String description;
	private int orderSequence;
	
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

	
}