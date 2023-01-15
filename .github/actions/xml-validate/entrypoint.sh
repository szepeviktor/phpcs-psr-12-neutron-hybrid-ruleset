#!/bin/bash
#
# Validate XML files.
#

set -e

PHPCS_SCHEMA="${INPUT_XML_PHPCS_SCHEMA:-vendor/squizlabs/php_codesniffer/phpcs.xsd}"

# Validate PHPCS schema
xmllint --noout --schema /tmp/XMLSchema.xsd "${PHPCS_SCHEMA}"

# Validate rulesets
for INPUT in "${!INPUT_@}"; do
    if [ "${INPUT}" == INPUT_XML_PHPCS_SCHEMA ]; then
        continue
    fi

    test -r "${!INPUT}"
    xmllint --noout --schema "${PHPCS_SCHEMA}" "${!INPUT}"
    diff --ignore-blank-lines "${!INPUT}" <(XMLLINT_INDENT="    " xmllint --format "${!INPUT}")
done
