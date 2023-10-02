<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Blog
 * @version    2.17.1
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Blog\Model\Import\Data\Processor\Post;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;

/**
 * Class FeaturedImageMobileFile
 */
class FeaturedImageMobileFile implements ProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process($data)
    {
        if (!isset($data[PostInterface::FEATURED_IMAGE_MOBILE_FILE])) {
            $data[PostInterface::FEATURED_IMAGE_MOBILE_FILE] = '';
        }

        return $data;
    }
}