<?php

namespace Quellenform\LibTableparser;

/*
 * This file is part of the "lib_tableparser" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Parser Utility class
 */
class Parser
{
    protected $rows = [];

    /**
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @param string $rows
     *
     * @return void
     */
    public function setRows($rows): void
    {
        $this->rows = $rows;
    }
}
