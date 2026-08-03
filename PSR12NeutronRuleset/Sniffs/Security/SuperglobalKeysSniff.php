<?php

/**
 * Restrict access to superglobal keys.
 *
 * @package PSR12NeutronRuleset
 */

declare(strict_types=1);

namespace PSR12NeutronRuleset\Sniffs\Security;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Checks superglobal keys against an allowlist.
 */
final class SuperglobalKeysSniff implements Sniff
{

    /**
     * Exact allowlist entries in the form `_GET[action]`.
     *
     * @var array<int|string, string>
     */
    public $allowedSuperglobalKeys = [];

    /**
     * Register the tokens processed by this sniff.
     *
     * @return array<int>
     */
    public function register(): array
    {
        return [T_VARIABLE];
    }

    /**
     * Process a variable token.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the token in the token stack.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens       = $phpcsFile->getTokens();
        $variableName = $tokens[$stackPtr]['content'];

        if ($this->isSupportedSuperglobal($variableName) === false) {
            return;
        }

        $nextPointer = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true);

        if ($nextPointer === false || $tokens[$nextPointer]['content'] !== '[') {
            return;
        }

        $closeBracketPointer = $this->findMatchingClosingBracket($phpcsFile, $nextPointer);

        if ($closeBracketPointer === null) {
            return;
        }

        $key = $this->normalizeKeyExpression($tokens, $nextPointer + 1, $closeBracketPointer - 1);

        if ($key === '') {
            return;
        }

        $entry          = substr($variableName, 1) . '[' . $key . ']';
        $allowedEntries = $this->normalizedAllowedEntries();

        if (isset($allowedEntries[$entry])) {
            return;
        }

        $phpcsFile->addError(
            'Superglobal key "%s" is not allowed. Add it to allowedSuperglobalKeys in the ruleset if intentional.',
            $stackPtr,
            'DisallowedKey',
            [$entry]
        );
    }

    /**
     * Check whether a variable is a supported superglobal.
     *
     * @param string $variableName Variable name.
     *
     * @return bool
     */
    private function isSupportedSuperglobal(string $variableName): bool
    {
        static $supported = [
            '$GLOBALS'  => true,
            '$_SERVER'  => true,
            '$_GET'     => true,
            '$_POST'    => true,
            '$_FILES'   => true,
            '$_COOKIE'  => true,
            '$_SESSION' => true,
            '$_REQUEST' => true,
            '$_ENV'     => true,
        ];

        return isset($supported[$variableName]);
    }

    /**
     * Find the closing bracket matching an opening bracket.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile          The file being scanned.
     * @param int                         $openBracketPointer Opening bracket position.
     *
     * @return int|null
     */
    private function findMatchingClosingBracket(File $phpcsFile, int $openBracketPointer): ?int
    {
        $tokens     = $phpcsFile->getTokens();
        $depth      = 0;
        $tokenCount = count($tokens);

        for ($pointer = $openBracketPointer; $pointer < $tokenCount; $pointer++) {
            $content = $tokens[$pointer]['content'];

            if ($content === '[') {
                ++$depth;
                continue;
            }

            if ($content !== ']') {
                continue;
            }

            --$depth;

            if ($depth === 0) {
                return $pointer;
            }
        }

        return null;
    }

    /**
     * Normalize a superglobal key expression.
     *
     * @param array<int, array<string, mixed>> $tokens       File tokens.
     * @param int                              $startPointer First key token position.
     * @param int                              $endPointer   Last key token position.
     *
     * @return string
     */
    private function normalizeKeyExpression(array $tokens, int $startPointer, int $endPointer): string
    {
        if ($startPointer > $endPointer) {
            return '';
        }

        $raw = '';

        for ($pointer = $startPointer; $pointer <= $endPointer; $pointer++) {
            $raw .= $tokens[$pointer]['content'];
        }

        $raw = preg_replace('/\s+/', '', $raw);

        if ($raw === null || $raw === '') {
            return '';
        }

        if (
            (substr($raw, 0, 1) === "'" && substr($raw, -1) === "'")
            || (substr($raw, 0, 1) === '"' && substr($raw, -1) === '"')
        ) {
            return substr($raw, 1, -1);
        }

        return $raw;
    }

    /**
     * Normalize configured allowlist entries.
     *
     * @return array<string, true>
     */
    private function normalizedAllowedEntries(): array
    {
        $allowedEntries = [];

        $entries = is_array($this->allowedSuperglobalKeys)
            ? $this->allowedSuperglobalKeys
            : explode(',', (string) $this->allowedSuperglobalKeys);

        foreach ($entries as $entry) {
            if (is_string($entry) === false) {
                continue;
            }

            $normalizedEntry = preg_replace('/\s+/', '', $entry);

            if ($normalizedEntry === null || $normalizedEntry === '') {
                continue;
            }

            $allowedEntries[$normalizedEntry] = true;
        }

        return $allowedEntries;
    }
}
