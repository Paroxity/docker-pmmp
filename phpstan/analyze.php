#!/usr/bin/env php
<?php

declare(strict_types=1);

echo "Running pmmp/phpstan analyzer\n";

$source = getcwd();
if(isset($argv[1])) {
	$source = $argv[1];
	echo "Source directory is defined as '{$source}'.\n";

	if(!is_dir($source)) {
		echo "$source is not a directory.\n";
		exit(1);
	}

	if(!is_readable($source)) {
		echo "$source is not readable by user 1000.\n";
		exit(1);
	}

	chdir($source);
}

if(is_file("plugin.yml")) {
	echo "plugin.yml found in '{$source}'.\n";

	if(!is_dir("src")) {
		echo "src not found in '{$source}'. Are the paths set correctly?\n";
		exit(1);
	}

	// $manifest = yaml_parse(file_get_contents("plugin.yml"));
	// $deps = [];
	// foreach(["depend", "softdepend", "loadbefore"] as $attr) {
	// 	if(isset($manifest[$attr])) {
	// 		array_push($deps, ...$manifest[$attr]);
	// 	}
	// }

	// foreach($deps as $dep) {
	// 	if(empty($dep)) {
	// 		continue;
	// 	}

	// 	echo "Attempting to download dependency $dep from Poggit...\n";
	// 	$code = pclose(popen("wget -O /deps/$dep.phar https://poggit.pmmp.io/get/$dep", "r"));
	// 	if($code !== 0) {
	// 		echo "Warning: Failed to download dependency $dep\n";
	// 		// still continue executing
	// 	}
	// }
}

if(is_file("composer.json")) {
	echo "composer.json found in '{$source}'.\n";
	passthru("composer install --no-suggest --no-progress -n -o", $result);
	if($result !== 0) {
		echo "Failed to install composer dependencies.";
		exit(1);
	}
}

$proc = proc_open("phpstan analyze --no-progress --memory-limit=2G -c {$_ENV["PHPSTAN_CONFIG"]}", [["file", "/dev/null", "r"], STDOUT, STDERR], $pipes);
if(is_resource($proc)) {
	$code = proc_close($proc);
	exit($code);
}
