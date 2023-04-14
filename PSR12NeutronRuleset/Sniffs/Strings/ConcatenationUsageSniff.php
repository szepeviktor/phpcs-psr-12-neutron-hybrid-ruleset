<?php

/**
 * Prohibit usage of "." operator.
 *
 * @package PSR12NeutronRuleset
 */

declare(strict_types=1);

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
     * @param \PHP_CodeSniffer\Files\File $phpcsFile File name.
     * @param int                         $stackPtr  Stack pointer.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addError('Concat operator is prohibited', $stackPtr, 'NotAllowed');
    }
}
