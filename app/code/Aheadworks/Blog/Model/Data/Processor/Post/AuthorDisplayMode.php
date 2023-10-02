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
namespace Aheadworks\Blog\Model\Data\Processor\Post;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;
use Aheadworks\Blog\Model\Source\Post\AuthorDisplayMode as SourceAuthorDisplayMode;

/**
 * Class AuthorDisplayMode
 */
class AuthorDisplayMode implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($data)
    {
        if (isset($data['use_default'])) {
            if (!empty($data['use_default'][PostInterface::AUTHOR_DISPLAY_MODE])) {
                $data[PostInterface::AUTHOR_DISPLAY_MODE] = SourceAuthorDisplayMode::USE_DEFAULT_OPTION;
            }
        }

        return $data;
    }
}