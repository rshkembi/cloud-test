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

use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;
use Aheadworks\Blog\Model\Source\Post\Status as PostStatus;
use Aheadworks\Blog\Api\Data\PostInterface;

/**
 * Class Status
 * @package Aheadworks\Blog\Model\Data\Processor\Post
 */
class Status implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($data)
    {
        $saveAction = isset($data['action']) ? $data['action'] : null;

        if (in_array($saveAction, ['save_as_draft', 'save_as_draft_and_duplicate'] )) {
            $data[PostInterface::STATUS] = PostStatus::DRAFT;
        }
        if ($saveAction == 'publish') {
            $data[PostInterface::STATUS] = PostStatus::PUBLICATION;
        }
        if ($saveAction == 'schedule') {
            $data[PostInterface::STATUS] = PostStatus::SCHEDULED;
        }

        return $data;
    }
}
