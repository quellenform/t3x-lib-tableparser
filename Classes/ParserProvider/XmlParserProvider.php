<?php

namespace Quellenform\LibTableparser\ParserProvider;

/*
 * This file is part of the "lib_tableparser" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use SimpleXMLElement;
use Quellenform\LibTableparser\Parser;
use Quellenform\LibTableparser\ParserHelper;
use Quellenform\LibTableparser\ParserProviderInterface;

/**
 * Class XmlParserProvider
 */
class XmlParserProvider implements ParserProviderInterface
{
    /**
     * Parse MS-XML
     *
     * @param Parser $parser
     * @param string $filePath
     * @param array $options
     *
     * @return void
     */
    public function parseData(Parser $parser, $filePath = '', array $options = []): void
    {
        // Load file into XML-object
        $xml = new SimpleXMLElement($filePath, 0, true);
        $parser->setRows(
            ParserHelper::getRowsFromXml(
                $xml,
                $options['nodes']['table'],
                $options['nodes']['row'],
                $options['nodes']['cell'],
                $options['nodes']['ns']
            )
        );
    }
}
