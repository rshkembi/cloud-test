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
 * @package    BlogGraphQl
 * @version    1.2.2
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\BlogGraphQl\Model\DataProcessor\Post;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Post\Comment\Provider as CommentProvider;
use Aheadworks\BlogGraphQl\Model\DataProcessor\DataProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Comments implements DataProcessorInterface
{
    /**
     * @param CommentProvider $commentProvider
     */
    public function __construct(private readonly CommentProvider $commentProvider)
    {
    }

    /**
     * Process data array before send
     *
     * @param array $data
     * @param array $args
     * @return array
     * @throws NoSuchEntityException
     */
    public function process(array $data, array $args): array
    {
        /** @var PostInterface $post */
        $post = $data['model'];
        $storeId = $args['store_id'];
        $data['builtin_count_comments'] = $this->commentProvider->getCountCommentsByPostId(
            [
                'post_id' => (int)$post->getId(),
                'store_id' => $storeId
            ]
        );

        return $data;
    }
}
