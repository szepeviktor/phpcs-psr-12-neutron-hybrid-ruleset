<?php

/**
 * Prohibit usage of "." operator.
 *
 * @package PSR12NeutronRuleset
 */

namespace PSR12NeutronRuleset\Sniffs\Strings;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * ConcatenationUsage sniff.
 */
class ConcatenationUsageSniff implements Sniff
{

    /**
     * Register sniff.
     *
     * @return array<int, int>
     */
    public function register()
    {
        return [\T_STRING_CONCAT, \T_CONCAT_EQUAL];
    }

    /**
     * Process sniff.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile 1
     * @param int                         $stackPtr  2
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addError('Concat operator is prohibited', $stackPtr, 'NotAllowed');
    }
}
