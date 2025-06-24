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
 * Class XlsxParserProvider
 */
class XlsxParserProvider implements ParserProviderInterface
{
    /**
     * Parse XLSX
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
        $data = [];
        // Load the shared strings from the ZIP-archive into XML-object
        $strings = new SimpleXMLElement(ParserHelper::readCompressedFile($filePath, 'xl/sharedStrings.xml'));
        // Load the first worksheet from the ZIP-archive into XML-object
        $sheet = new SimpleXMLElement(ParserHelper::readCompressedFile($filePath, 'xl/worksheets/sheet1.xml'));
        // Parse the rows
        $xlRows = $sheet->sheetData->row;
        // Iterate through rows
        foreach ($xlRows as $xlRow) {
            $arr = [];
            // Get values from rows
            foreach ($xlRow->c as $cell) {
                $v = (string) $cell->v;
                // If it has a "t" (type?) of "s" (string?), use the value to look up string value
                if (isset($cell['t']) && $cell['t'] == 's') {
                    $s = [];
                    $si = $strings->si[(int) $v];
                    // Register the default namespace for xpath query
                    $si->registerXPathNamespace('n', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                    // Concatenate all t-nodes together
                    foreach ($si->xpath('.//n:t') as $t) {
                        $s[] = (string) $t;
                    }
                    $v = implode($s);
                }
                $arr[] = $v;
            }
            // Add a new row if all cells are non empty
            if (count($arr)) {
                //$values = array_pad($arr, $this->fieldLimit, '');
                $values = array_pad($arr, count($arr), '');
                $data[] = $values;
            }
        }
        $parser->setRows($data);
    }
}
