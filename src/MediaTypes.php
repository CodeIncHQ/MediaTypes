<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     17/04/2018
// Time:     09:55
// Project:  MimeTypeLookup
//
declare(strict_types=1);
namespace CodeInc\MediaTypes;
use CodeInc\MediaTypes\Exceptions\MediaTypesException;
use CodeInc\MediaTypes\Exceptions\ReadOnlyException;


/**
 * Class MediaTypes
 *
 * @link https://github.com/CodeIncHQ/MediaTypes
 * @license MIT (https://github.com/CodeIncHQ/MediaTypes/blob/master/LICENSE)
 * @package CodeInc\MediaTypes
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MediaTypes implements \IteratorAggregate, \Countable, \ArrayAccess
{
    public const MEDIA_TYPES_LIST_PATH = __DIR__.'/../assets/media-types.json';

    /**
     * @var string[]|null
     */
    private static $mediaTypes;

    /**
     * @return array
     * @throws MediaTypesException
     */
    public static function &getMediaTypes():array
    {
        if (!self::$mediaTypes) {
            if (($list = file_get_contents(self::MEDIA_TYPES_LIST_PATH)) === false) {
                throw new MediaTypesException(
                    sprintf("Unable to read the media types list (%s)", self::MEDIA_TYPES_LIST_PATH)
                );
            }
            if (!(self::$mediaTypes = json_decode($list, true))) {
                throw new MediaTypesException(
                    sprintf("Unable to parse the JSON media types list (%s)", self::MEDIA_TYPES_LIST_PATH)
                );
            }
        }
        return self::$mediaTypes;
    }

    /**
     * Returns the media type for a given extension.
     *
     * @param string $extension
     * @return null|string
     * @throws MediaTypesException
     */
    public static function getExtensionMediaType(string $extension):?string
    {
        return self::getMediaTypes()[strtolower($extension)] ?? null;
    }

    /**
     * Returns the media type for a given file name.
     *
     * @param string $filename
     * @return null|string
     * @throws MediaTypesException
     */
    public static function getFilenameMediaType(string $filename):?string
    {
        if (preg_match('|\\.([^\\s]+$|u', $filename, $matches)) {
            return self::getExtensionMediaType($matches[1]);
        }
        return null;
    }

    /**
     * Returnes all the known extensions for a given media type.
     *
     * @param string $mediaType
     * @return array
     * @throws MediaTypesException
     */
    public static function getMediaTypeExtensions(string $mediaType):array
    {
        $mediaType = strtolower($mediaType);
        $extensions = [];
        foreach (self::getMediaTypes() as $extension => $listMediaType) {
            if ($listMediaType == $mediaType) {
                $extensions[] = $extension;
            }
        }
        return $extensions;
    }

    /**
     * Searches media types using a shell pattern (like image/*).
     *
     * @param string $pattern
     * @return array
     * @throws MediaTypesException
     */
    public static function searchMediaTypes(string $pattern):array
    {
        $pattern = strtolower($pattern);
        $results = [];
        foreach (self::getMediaTypes() as $extension => $mediaType) {
            if (fnmatch($pattern, $mediaType)) {
                $results[$extension] = $mediaType;
            }
        }
        return $results;
    }

    /**
     * @inheritdoc
     * @return \ArrayIterator
     * @throws MediaTypesException
     */
    public function getIterator():\ArrayIterator
    {
        return new \ArrayIterator(self::getMediaTypes());
    }

    /**
     * @inheritdoc
     * @return int
     * @throws MediaTypesException
     */
    public function count():int
    {
        return count(self::getMediaTypes());
    }

    /**
     * @inheritdoc
     * @param string $offset
     * @return bool
     * @throws MediaTypesException
     */
    public function offsetExists($offset):bool
    {
        return array_key_exists((string)$offset, self::getMediaTypes())
            || in_array((string)$offset, self::getMediaTypes());
    }

    /**
     * @inheritdoc
     * @param string $offset
     * @return null|string|array
     * @throws MediaTypesException
     */
    public function offsetGet($offset)
    {
        if (($mediaType = self::getExtensionMediaType((string)$offset)) !== null) {
            return $mediaType;
        }

        if (($extensions = self::getMediaTypeExtensions((string)$offset)) !== null) {
            return $extensions;
        }
        return null;
    }

    /**
     * This method should not be used. The media types list is read only.
     *
     * @param string $offset
     * @param mixed $value
     * @throws MediaTypesException
     */
    public function offsetSet($offset, $value):void
    {
        throw new ReadOnlyException();
    }

    /**
     * This method should not be used. The media types list is read only.
     *
     * @inheritdoc
     * @param string $offset
     * @throws MediaTypesException
     */
    public function offsetUnset($offset):void
    {
        throw new ReadOnlyException();
    }
}