<?php

$tz = $argv[1];
date_default_timezone_set( $tz );
$q = $argv[2];

$now = time();
$time = json_decode(file_get_contents( 'time.txt' ));
if ( empty($time) && $q != 'start' && $q != 's' && $q != 'reset' && $q != 'r')
	die('Must start timer');

switch($q) {
	case 'start' :
	case 's' :
		$time = array( 'start' => $now );
		$message = 'Timer started at ' . date('H:i:s');
		break;
	case 'stop' :
	case 'x' :
		$time->stop = $now;
		$message = 'Timer stopped at ' . date('H:i:s');
		$message .= "\n".get_diff( $time->stop, $time->start );
		break;
	case 'reset' :
	case 'r' :
		$time = '';
		$message = 'Timer reset';
		break;
	case 'time' :
	case 't' :
	default :
		if (isset($time->stop))
			$message = get_diff( $time->stop, $time->start );
		else
			$message = get_diff( $now, $time->start );
		break;
}

$message = (string) $message;
fwrite(fopen('time.txt','w'), json_encode($time));
die( $message );


function get_diff( $stop, $start ) {
	$diff = $stop - $start;

	$hrs = 0; $mns = 0; $scs = 0;

	if ($diff > 3600) {
		$hrs  = round( $diff / 3600, 0, PHP_ROUND_HALF_DOWN);
		$diff = $diff % 3600;
	}
	if ($diff > 60) {
		$mns  = round( $diff/60, 0, PHP_ROUND_HALF_DOWN);
		$diff = $diff % 60;
	}
	$scs = $diff;
	return "$hrs:$mns:$scs passed";
}