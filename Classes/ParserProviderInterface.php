<?php

namespace Quellenform\LibTableparser;

/*
 * This file is part of the "lib_tableparser" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Quellenform\LibTableparser\Parser;

/**
 * Interface ParserProviderInterface
 */
interface ParserProviderInterface
{
    /**
     * Prepare the parser
     *
     * @param Parser $parser
     * @param string $filePath
     * @param array $options
     *
     * @return void
     */
    public function parseData(Parser $parser, $filePath = '', array $options = []): void;
}
