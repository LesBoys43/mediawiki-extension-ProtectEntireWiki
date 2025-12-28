<?php
$files = glob("i18n/*.json");
$failcnt = 0;
$e255 = false;
$main = "i18n/zh.json";
$mainCont = file_get_contents($main);
$mainData = null;
if (!json_validate($mainCont)) {
	# Due the main file invalid, we cannot continue, because check integrity of other keys requires valid main.
	exit(255);
}
$mainData = json_decode($mainCont, true);
foreach ($files as $file) {
	if ($file == $main) {
		# We should skip main self
		continue;
	}
	$cont = file_get_contents($file);
	if (!json_validate($cont)) {
		# Due the json invalid, we can not continue validation, so skip and mark as exit 255
		$e255 = true;
		continue;
	}
	$data = json_decode($cont, true);
	$fail = false;
	foreach (array_keys($mainData) as $key) {
		if (!isset($data[$key])) {
			echo $file . " missed key " . $key . "\n";
			$fail = true;
		}
	}
	if ($fail) {
		echo $file . " missed some keys" . "\n";
		$failcnt++;
	} else {
		echo $file . " has all required keys" . "\n";
	}
}
exit($e255 ? 255 : $failcnt);
