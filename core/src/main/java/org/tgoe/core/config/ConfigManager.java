package org.tgoe.core.config;

import java.net.URL;
import java.util.Enumeration;
import java.util.NoSuchElementException;

import org.ini4j.Ini;
import org.slf4j.LoggerFactory;
import org.slf4j.Logger;

public class ConfigManager {
	private static final String CONFIGFILE_NAME = "tgoe-core-config.ini";
	private static final Logger logger = LoggerFactory.getLogger(ConfigManager.class);
	
	private static Ini cfg;
	
	private static void init() {
		try {
			URL configFile = findConfigFile();
			logger.info("Using config file {}", configFile);
			cfg = new Ini(configFile);
		} catch (Exception e) {
			logger.error("Error while reading config file.", e);
		}
	}
	
	private static URL findConfigFile() throws Exception {
		//find config file in classpath
		Enumeration<URL> configFiles = ConfigManager.class.getClassLoader().getResources(CONFIGFILE_NAME);
		
		//use first file found
		URL configFile;
		try {
			configFile = configFiles.nextElement();
		}
		catch( NoSuchElementException e ) {
			throw new Exception(String.format("No config file with name %s found in classpath.", CONFIGFILE_NAME));
		}
		
		//warn in case there are more matching files
		if( configFiles.hasMoreElements() ) {
			logger.warn("Multiple matching config files with name {} found in classpath. Using {}", CONFIGFILE_NAME, configFile);
			return null;
		}
		
		return configFile;
	}
	
	/**
	 * Read value from configuration.
	 * 
	 * @param k Key for the configuration value.
	 * @return Config value. Null in case config file not found or value not contained in config file.
	 */
	public static String getValue( ConfigKey k) {
		if( cfg == null) {
			init();
			if( cfg == null) return null;
		}

		return cfg.get( k.getSectionName(), k.getValueName() );
	}
}
