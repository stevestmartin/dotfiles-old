<?php

	$user = $argv[1];
	$path = "/Users/$user/Downloads";
	$files = scandir($path);
	date_default_timezone_set("America/Chicago");

	foreach($files as $f):
		if (is_file($path."/".$f)) {
			if ($f == ".DS_Store" || $f == ".localized") { continue; }
			$date = exec("stat -x \"$path/$f\" | grep Change:");
			$date = explode(" ", $date);
			unset($date[0]);
			$date = implode($date, " ");
			$date = strtotime($date);
			
			if (isset($recent) && $recent['date'] < $date) {
				$recent['file'] = $f;
				$recent['date'] = $date;
			}
			else if (isset($recent) && $recent['date'] > $date) {
				continue;
			}
			else {
				$recent['file'] = $f;
				$recent['date'] = $date;
			}
		}
	endforeach;
	
	exec("osascript -e 'tell application \"Finder\" to open posix file \"/Users/$user/Downloads/".$recent['file']."\"' ");
	echo $recent['file'];

?>