<?php

namespace Quellenform\LibTableparser;

/*
 * This file is part of the "lib_tableparser" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Quellenform\LibTableparser\Exception\ParserException;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * The main factory class, which acts as the entrypoint for generating an Parser object which
 * is responsible for rendering an parser. Checks for the correct parser provider through the ParserRegistry.
 *
 * USAGE:
 *   use Quellenform\LibTableparser\ParserFactory;
 *   $this->parserFactory = GeneralUtility::makeInstance(ParserFactory::class)->getData($absFileName);
 */
class ParserFactory
{
    /**
     * @var ParserRegistry
     */
    protected $parserRegistry;

    /**
     * @param ParserRegistry $parserRegistry
     */
    public function __construct(ParserRegistry $parserRegistry = null)
    {
        $this->parserRegistry = $parserRegistry ? $parserRegistry : GeneralUtility::makeInstance(ParserRegistry::class);
    }

    /**
     * @param string $filePath
     * @param string $identifier
     * @param array $options
     *
     * @return Parser
     * @throws ParserException
     */
    public function getData($filePath, $identifier = '', $options = []): Parser
    {
        if (!self::isAllowedAbsPath($filePath)) {
            throw new ParserException('The filepath "' . $filePath . '" is not allowed by TYPO3.');
        }

        // Set parser configuration default values
        $parserConfiguration['options'] = [
            'colLimit' => 0,
            'rowLimit' => 0,
            'header' => true
        ];

        // Autodetect file format
        if (!$identifier || empty($identifier)) {
            $identifier = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        }
        // Get parser configuration and merge it
        ArrayUtility::mergeRecursiveWithOverrule(
            $parserConfiguration,
            $this->parserRegistry->getParserConfigurationByIdentifier($identifier)
        );

        // Merge provided options into parserConfiguration
        if (is_array($options) && count($options)) {
            ArrayUtility::mergeRecursiveWithOverrule($parserConfiguration['options'], $options);
        }

        $parser = GeneralUtility::makeInstance(Parser::class);

        /** @var ParserProviderInterface $ParserProvider */
        $parserProvider = GeneralUtility::makeInstance((string) $parserConfiguration['provider']);
        $parserProvider->parseData($parser, $filePath, $parserConfiguration['options']);

        return $parser;
    }

    /**
     * Returns TRUE if the path is absolute, without backpath '..' and within 'upload_tmp_dir' OR within the lockRootPath
     *
     * @param string $path File path to evaluate
     *
     * @return bool
     */
    protected static function isAllowedAbsPath($path): bool
    {
        $lockRootPath = $GLOBALS['TYPO3_CONF_VARS']['BE']['lockRootPath'];
        return PathUtility::isAbsolutePath($path) && GeneralUtility::validPathStr($path) && (str_starts_with(
            $path,
            ini_get('upload_tmp_dir')
        ) || $lockRootPath && str_starts_with($path, $lockRootPath));
    }
}
