<?php

/**
 * Prohibit usage of "." operator.
 */

namespace PSR12NeutronRuleset\Sniffs\Strings;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ConcatenationUsageSniff implements Sniff
{
    /**
     * @return array<int>
     */
    public function register()
    {
        return [\T_STRING_CONCAT];
    }

    /**
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addError('Concat operator is prohibited', $stackPtr, 'NotAllowed');
    }
}
