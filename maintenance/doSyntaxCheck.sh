#!/bin/bash

FAIL_CNT=0

for file in includes/*.php; do
	echo "Checking $file"
	php -l "$file" > nul 2>&1 && {
		echo "$file passed the check" 
		echo "$file passed the check" >> $GITHUB_STEP_SUMMARY
	} || {
		FAIL_CNT=$(expr $FAIL_CNT + 1)
		echo "$file failed the check"
		echo "$file failed the check" >> $GITHUB_STEP_SUMMARY
	}
done

exit $FAIL_CNT