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
// Time:     11:00
// Project:  MediaTypes
//
declare(strict_types=1);
namespace CodeInc\MediaTypes\Tests;
use CodeInc\MediaTypes\MediaTypes;
use PHPUnit\Framework\TestCase;


/**
 * Class MediaTypes
 *
 * @package CodeInc\MediaTypes\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MediaTypesTest extends TestCase
{
    private const JPEG_TYPE = 'image/jpeg';
    private const JPEG_EXT = 'jpg';

    /**
     * Tests loading the media types list.
     *
     * @throws \CodeInc\MediaTypes\Exceptions\MediaTypesException
     */
    public function testMediaTypesListLoading():void
    {
        // checking the file
        self::assertFileExists(MediaTypes::MEDIA_TYPES_LIST_PATH);
        self::assertIsReadable(MediaTypes::MEDIA_TYPES_LIST_PATH);

        // loading the list
        $mediaTypes = MediaTypes::getMediaTypes();

        // checking the list content
        self::assertInternalType('array', $mediaTypes);
        self::assertNotEmpty($mediaTypes);
    }

    /**
     * @throws \CodeInc\MediaTypes\Exceptions\MediaTypesException
     */
    public function testMediaTypesLookup():void
    {
        self::assertNotNull(MediaTypes::getExtensionMediaType(self::JPEG_EXT));
        self::assertContains(self::JPEG_EXT, MediaTypes::getMediaTypeExtensions(self::JPEG_TYPE));
    }

    /**
     * Tests the media type search.
     *
     * @throws \CodeInc\MediaTypes\Exceptions\MediaTypesException
     */
    public function testMediaTypeSearch():void
    {
        $results = MediaTypes::searchMediaTypes('image/*');
        self::assertInternalType('array', $results);
        self::assertNotEmpty($results);
        self::assertArrayHasKey(self::JPEG_EXT, $results);
        self::assertContains(self::JPEG_TYPE, $results);
    }

    /**
     * Tests the \IteratorAggregate interface.
     */
    public function testIterator():void
    {
        $foundExtJpg = $foundTypeImageJpeg = false;
        $i = 0;
        foreach (new MediaTypes() as $extension => $mediaType) {
            if ($mediaType == self::JPEG_TYPE) {
                $foundTypeImageJpeg = true;
            }
            if ($extension == self::JPEG_EXT) {
                $foundExtJpg = true;
            }
            $i++;
        }
        self::assertGreaterThan(0, $i);
        self::assertTrue($foundTypeImageJpeg);
        self::assertTrue($foundExtJpg);
    }

    /**
     * Test the \ArrayAccess interface.
     */
    public function testArrayAccess():void
    {
        $mediaTypes = new MediaTypes();
        self::assertArrayHasKey(self::JPEG_EXT, $mediaTypes);
        self::assertEquals(self::JPEG_TYPE, $mediaTypes[self::JPEG_EXT]);
    }

    /**
     * Tests the Countable interface.
     *
     * @throws \CodeInc\MediaTypes\Exceptions\MediaTypesException
     */
    public function testCountable():void
    {
        $mediaTypes = new MediaTypes();
        self::assertCount($mediaTypes->count(), MediaTypes::getMediaTypes());
    }
}