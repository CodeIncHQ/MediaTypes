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
// Time:     11:05
// Project:  MediaTypes
//
declare(strict_types=1);
namespace CodeInc\MediaTypes\Exceptions;
use Throwable;


/**
 * Class ReadOnlyException
 *
 * @package CodeInc\MediaTypes\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ReadOnlyException extends MediaTypesException
{
    /**
     * ReadOnlyException constructor.
     *
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct("The media types list is read only", $code, $previous);
    }
}