package org.tgoe.core.member.beans;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Map;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Member {
	public final static String easyvereinQueryString = "{id,membershipNumber,joinDate,resignationDate,emailOrUserName,_isChairman,"
			+ "contactDetails{firstName,familyName,dateOfBirth,street,city,zip,privateEmail},"
			+ "memberGroups{memberGroup" + MemberGroup.easyvereinQueryString + "}}";

	private final SimpleDateFormat dateOnlyFormat = new SimpleDateFormat("yyyy-MM-dd");
	private static final Logger logger = LoggerFactory.getLogger(Member.class);

	private String id;
	private String membershipNumber;
	private Date joinDate;
	private Date resignationDate;
	private String loginName;
	private boolean isAdmin;
	private String firstName;
	private String familyName;
	private Date dateOfBirth;
	private String street;
	private String city;
	private String zip;
	private String privateEmail;
	private List<GroupMembership> memberGroups;

	public String getId() {
		return id;
	}

	@JsonProperty("id")
	private void setId(String id) {
		this.id = id;
	}

	public String getMembershipNumber() {
		return membershipNumber;
	}

	@JsonProperty("membershipNumber")
	private void setMembershipNumber(String membershipNumber) {
		this.membershipNumber = membershipNumber;
	}

	public Date getJoinDate() {
		return joinDate;
	}

	@JsonProperty("joinDate")
	private void setJoinDate(Date joinDate) {
		this.joinDate = joinDate;
	}

	public Date getResignationDate() {
		return resignationDate;
	}

	@JsonProperty("resignationDate")
	private void setResignationDate(Date resignationDate) {
		this.resignationDate = resignationDate;
	}

	public String getLoginName() {
		return loginName;
	}

	@JsonProperty("emailOrUserName")
	private void setLoginName(String loginName) {
		this.loginName = loginName;
	}

	public boolean isAdmin() {
		return isAdmin;
	}

	@JsonProperty("_isChairman")
	private void setAdmin(boolean isAdmin) {
		this.isAdmin = isAdmin;
	}

	public String getFirstName() {
		return firstName;
	}

	public String getFamilyName() {
		return familyName;
	}

	public Date getDateOfBirth() {
		return dateOfBirth;
	}

	public String getStreet() {
		return street;
	}

	public String getCity() {
		return city;
	}

	public String getZip() {
		return zip;
	}

	public String getPrivateEmail() {
		return privateEmail;
	}

	public List<GroupMembership> getMemberGroups() {
		return memberGroups;
	}

	@JsonProperty("memberGroups")
	private void setMemberGroups(List<GroupMembership> memberGroups) {
		this.memberGroups = memberGroups;
	}

	@JsonProperty("contactDetails")
	private void flattenContactDetails(Map<String, Object> contactDetails) {
		firstName = (String) contactDetails.get("firstName");
		familyName = (String) contactDetails.get("familyName");
		street = (String) contactDetails.get("street");
		city = (String) contactDetails.get("city");
		zip = (String) contactDetails.get("zip");
		privateEmail = (String) contactDetails.get("privateEmail");

		String dob = (String) contactDetails.get("dateOfBirth");
		try {
			dateOfBirth = dob != null ? dateOnlyFormat.parse(dob) : null;
		} catch (ParseException e) {
			dateOfBirth = null;
			logger.warn("Cannot parse date of Member. Value=" + dob);
		}
	}
	
	@Override
	public String toString() {
		return String.format("Member[id=%s, membershipNumber=%s firstName=%s, familyName=%s]", id, membershipNumber,
				firstName, familyName);
	}

}
