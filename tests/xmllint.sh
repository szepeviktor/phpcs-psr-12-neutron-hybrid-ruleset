#!/bin/bash
#
# Lint ruleset XML file.
#
# BASH-VERSION  :4.2+
# DEPENDS       :apt-get install wget libxml2-utils

RULESET="PSR12NeutronRuleset/ruleset.xml"

set -e

# Check dependency
hash xmllint

# Create temporary directory
mkdir -p build

# Current directory should be repository root
test -r "$RULESET"

# Download XML schema definition
wget -nv -N -P build/ "https://www.w3.org/2012/04/XMLSchema.xsd"

# Validate PHPCS schema
xmllint --noout --schema build/XMLSchema.xsd vendor/squizlabs/php_codesniffer/phpcs.xsd

# Validate ruleset
xmllint --noout --schema vendor/squizlabs/php_codesniffer/phpcs.xsd "$RULESET"
# Check ruleset XML formatting
diff --ignore-blank-lines "$RULESET" <(XMLLINT_INDENT="    " xmllint --format "$RULESET")
