#!/bin/bash

FAIL_CNT=0

for file in includes/*.php; do
	php -l "$file" || {FAIL_CNT=$(expr $FAIL_CNT + 1)}
done

exit $FAIL_CNT