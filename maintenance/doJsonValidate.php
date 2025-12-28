<?php
$files = ["extension.json"];
$files = array_merge($files, glob("i18n/*.json"));
$failcnt = 0;
foreach ($files as $file) {
	$cont = file_get_contents($file);
	echo "Validating: " . $file . "\n"’;
	$result = json_validate($cont);
	if ($result) {
		echo $file . " passed validation" . "\n";
	} else {
		$failcnt++;
		echo $file . " failed validation" . "\n";
	}
}