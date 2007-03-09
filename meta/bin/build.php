#!/usr/bin/php
<?php
	/* $Id$ */

	function help()
	{
?>
Usage: build.php [-noschema] project-configuration-file.inc.php metaconfiguration.xml

With -noschema flag no DB schema/schema changes will be generated.

Things not supported by design:

* composite identifiers;
* obscurantism.

<?php
		exit(1);
	}
	
	function init()
	{
		define('ONPHP_META_BUILDERS', ONPHP_META_PATH.'builders'.DIRECTORY_SEPARATOR);
		define('ONPHP_META_PATTERNS', ONPHP_META_PATH.'patterns'.DIRECTORY_SEPARATOR);
		define('ONPHP_META_TYPES', ONPHP_META_PATH.'types'.DIRECTORY_SEPARATOR);
		
		set_include_path(
			get_include_path().PATH_SEPARATOR
			.ONPHP_META_BUILDERS.PATH_SEPARATOR
			.ONPHP_META_PATTERNS.PATH_SEPARATOR
			.ONPHP_META_TYPES.PATH_SEPARATOR
		);
		
		if (!defined('ONPHP_META_DAO_DIR'))
			define(
				'ONPHP_META_DAO_DIR',
				PATH_CLASSES.'DAOs'.DIRECTORY_SEPARATOR
			);
		
		if (!defined('ONPHP_META_BUSINESS_DIR'))
			define(
				'ONPHP_META_BUSINESS_DIR',
				PATH_CLASSES.'Business'.DIRECTORY_SEPARATOR
			);
		
		if (!defined('ONPHP_META_PROTO_DIR'))
			define(
				'ONPHP_META_PROTO_DIR',
				PATH_CLASSES.'Proto'.DIRECTORY_SEPARATOR
			);

		define('ONPHP_META_AUTO_DIR', PATH_CLASSES.'Auto'.DIRECTORY_SEPARATOR);
		
		define(
			'ONPHP_META_AUTO_BUSINESS_DIR',
			ONPHP_META_AUTO_DIR
			.'Business'.DIRECTORY_SEPARATOR
		);
		define(
			'ONPHP_META_AUTO_PROTO_DIR',
			ONPHP_META_AUTO_DIR
			.'Proto'.DIRECTORY_SEPARATOR
		);
		if (!defined('ONPHP_META_AUTO_DAO_DIR'))
			define(
				'ONPHP_META_AUTO_DAO_DIR',
				ONPHP_META_AUTO_DIR
				.'DAOs'.DIRECTORY_SEPARATOR
			);
		
		if (!is_dir(ONPHP_META_DAO_DIR))
			mkdir(ONPHP_META_DAO_DIR, 0755, true);
		
		if (!is_dir(ONPHP_META_AUTO_DIR))
			mkdir(ONPHP_META_AUTO_DIR, 0755, true);
		
		if (!is_dir(ONPHP_META_AUTO_BUSINESS_DIR))
			mkdir(ONPHP_META_AUTO_BUSINESS_DIR, 0755);
			
		if (!is_dir(ONPHP_META_AUTO_PROTO_DIR))
			mkdir(ONPHP_META_AUTO_PROTO_DIR, 0755);
		
		if (!is_dir(ONPHP_META_AUTO_DAO_DIR))
			mkdir(ONPHP_META_AUTO_DAO_DIR, 0755);
		
		if (!is_dir(ONPHP_META_BUSINESS_DIR))
			mkdir(ONPHP_META_BUSINESS_DIR, 0755, true);
		
		if (!is_dir(ONPHP_META_PROTO_DIR))
			mkdir(ONPHP_META_PROTO_DIR, 0755, true);
	}

	if (!isset($_SERVER['argv'][1], $_SERVER['argv'][2])) {
		help();
	} else {
		
		$buildSchema = true;
		
		$metaFile = array_pop($_SERVER['argv']);
		$configFile = array_pop($_SERVER['argv']);
            	if ($metaFile[0] == '-' || $configFile[0] == '-')
                	help();
                	
            	for ($i = 1; $i < $_SERVER['argc']-2; $i++) {
                	switch ($_SERVER['argv'][$i]) {
                    		case '-noschema':
                        		$buildSchema = false;
                        		break;
                    		default:
                        		echo "There is no such option: ".$_SERVER['argv'][$i]."\n";
                	}
            	}
		
		if (is_readable($configFile))
			include $configFile;
		else {
			echo "Project's configuration file not found.\n";
			help();
		}
		
		if (is_readable($metaFile)) {
			
			init();
			
			if (
				isset($_SERVER['TERM'])
				&& (
					$_SERVER['TERM'] == 'xterm'
					|| $_SERVER['TERM'] == 'linux'
				)
			) {
				$out = new ColoredTextOutput();
			} else {
				$out = new TextOutput();
			}
			
			$out = new MetaOutput($out);
			
			$out->
				infoLine('onPHP-'.ONPHP_VERSION.': MetaConfiguration builder.', true)->
				newLine();
			
			try {
				$metaConfiguration = 
					MetaConfiguration::me()->
						load($metaFile)->
						setOutput($out)->
						buildClasses();
						
				if ($buildSchema)
					$metaConfiguration->
						buildSchema()->
						buildSchemaChanges();
						
				$metaConfiguration->checkForStaleFiles();
			} catch (BaseException $e) {
				$out->
					newLine()->
					errorLine($e->getMessage(), true)->
					newLine()->
					logLine(
						$e->getTraceAsString()
					);
			}
			
			$out->getOutput()->resetAll();
			$out->newLine();
			
		} else {
			echo "MetaConfiguration file not found.\n";
			help();
		}
	}
?>