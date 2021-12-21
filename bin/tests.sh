#!/bin/bash -e

cd $(dirname $0)

./../vendor/nette/tester/src/tester -C ../tests $@
