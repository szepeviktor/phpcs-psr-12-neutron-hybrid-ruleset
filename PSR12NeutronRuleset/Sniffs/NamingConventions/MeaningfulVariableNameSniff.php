<?php

/**
 * Prohibit usage of bad variable names.
 *
 * @package PSR12NeutronRuleset
 */

declare(strict_types=1);

namespace PSR12NeutronRuleset\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;

/**
 * Checks the meaningful naming of variables and member variables.
 *
 * Modified copy from https://github.com/InteractionDesignFoundation/coding-standard
 */
final class MeaningfulVariableNameSniff extends AbstractVariableSniff
{

    /**
     * Forbidden variable names.
     *
     * @var array<string, string>
     */
    public $forbiddenNames = [];

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int|string                  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    protected function processVariable(File $phpcsFile, $stackPtr): void
    {
        $tokens  = $phpcsFile->getTokens();
        $varName = ltrim($tokens[$stackPtr]['content'], '$');

        if ($this->checkForProhibitedVariableNames($varName)) {
            $error = 'Variable "%s" has not very meaningful, searchable or pronounceable name';
            $phpcsFile->addError($error, $stackPtr, 'NotMeaningfulVariableName', [$varName]);
            return;
        }
    }

    /**
     * Processes class member variables.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int|string                  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    protected function processMemberVar(File $phpcsFile, $stackPtr): void
    {
        $tokens  = $phpcsFile->getTokens();
        $varName = ltrim($tokens[$stackPtr]['content'], '$');

        if ($this->checkForProhibitedVariableNames($varName)) {
            $error = 'Variable "%s" has not very meaningful, searchable or pronounceable name';
            $phpcsFile->addError($error, $stackPtr, 'NotMeaningfulVariableName', [$varName]);
            return;
        }
    }

    /**
     * Processes the variable found within a double-quoted string.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int|string                  $stackPtr  The position of the double-quoted string.
     *
     * @return void
     */
    protected function processVariableInString(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        if (
            preg_match_all(
                '|[^\\\]\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)|',
                $tokens[$stackPtr]['content'],
                $matches
            ) !== 0
        ) {
            foreach ($matches[1] as $varName) {
                // If it’s a php reserved var, then it's ok.
                if (isset($this->phpReservedVars[$varName])) {
                    continue;
                }

                if ($this->checkForProhibitedVariableNames($varName)) {
                    $error = 'Variable "%s" has not very meaningful or searchable name';
                    $phpcsFile->addError($error, $stackPtr, 'NotMeaningfulVariableName', [$varName]);
                    return;
                }
            }
        }
    }

    /**
     * Check variable name.
     *
     * @param string $varName Variable name to check.
     *
     * @return bool
     */
    private function checkForProhibitedVariableNames(string $varName): bool
    {
        return isset($this->forbiddenNames[$varName]);
    }
}
