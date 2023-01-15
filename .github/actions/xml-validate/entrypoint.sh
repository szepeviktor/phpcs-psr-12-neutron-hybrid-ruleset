#!/bin/bash
#
# Validate XML files.
#

for INPUT in "${!INPUT_@}"; do
    echo "INPUT: ${INPUT} = '${!INPUT}'"
done

echo "OK."
