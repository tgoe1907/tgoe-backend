package org.tgoe.core.member.services.api;

public class EasyVereinException extends Exception {
	private static final long serialVersionUID = 1L;
	
	public EasyVereinException(String message) {
		super(message);
	}
	
	public EasyVereinException(String message, Throwable cause) {
		super(message, cause);
	}
}
