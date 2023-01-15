#!/bin/bash
#
# Validate PHPCS rulesets.
#

set -e

PHPCS_SCHEMA="${INPUT_XML_PHPCS_SCHEMA:-vendor/squizlabs/php_codesniffer/phpcs.xsd}"

if [ -z "${!INPUT_*}" ]; then
    echo "No ruleset files specified!"
    exit 10
fi

# Validate PHPCS schema
xmllint --noout --schema /usr/local/share/xml/XMLSchema.xsd "${PHPCS_SCHEMA}"

# Validate rulesets
for INPUT in "${!INPUT_@}"; do
    if [ "${INPUT}" == INPUT_XML_PHPCS_SCHEMA ] || [ -z "${!INPUT}" ]; then
        continue
    fi

    xmllint --noout --schema "${PHPCS_SCHEMA}" "${!INPUT}"
    # Indentation: 4 spaces
    diff --ignore-blank-lines --unified "${!INPUT}" <(XMLLINT_INDENT="    " xmllint --format "${!INPUT}")
done
