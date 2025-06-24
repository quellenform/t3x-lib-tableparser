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
 * Class OdsParserProvider
 */
class OdsParserProvider implements ParserProviderInterface
{
    /**
     * Parse ODS
     *
     * @param Parser $parser
     * @param string $filePath
     * @param array $options
     *
     * @return void
     */
    public function parseData(Parser $parser, $filePath = '', array $options = []): void
    {
        ParserHelper::checkZip();
        // Load a specific file from the ZIP-archive into XML-object
        $xml = new SimpleXMLElement(ParserHelper::readCompressedFile($filePath, 'content.xml'));
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
