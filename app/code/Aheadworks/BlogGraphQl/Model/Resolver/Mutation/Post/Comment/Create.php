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

namespace Aheadworks\BlogGraphQl\Model\Resolver\Mutation\Post\Comment;

use Aheadworks\Blog\Api\CommentManagementInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Api\Data\CommentInterfaceFactory;
use Aheadworks\Blog\Model\BuiltinConfig;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;
use Aheadworks\Blog\Model\Source\Config\Comments\Service;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Reflection\DataObjectProcessor;

class Create implements ResolverInterface
{
    /**
     * @param CommentInterfaceFactory $commentFactory
     * @param CommentManagementInterface $commentService
     * @param DataObjectHelper $dataObjectHelper
     * @param ProcessorInterface $postDataProcessor
     * @param Config $config
     * @param BuiltinConfig $builtinConfig
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        private readonly CommentInterfaceFactory $commentFactory,
        private readonly CommentManagementInterface $commentService,
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly ProcessorInterface $postDataProcessor,
        private readonly Config $config,
        private readonly BuiltinConfig $builtinConfig,
        private readonly DataObjectProcessor $dataObjectProcessor
    ) {
    }

    /**
     * Perform resolve method after validate customer authorization
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @throws \Exception
     * @return array
     */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!$this->config->isBlogEnabled() ||
            ($this->config->isCommentsEnabled() && !$this->config->getCommentType() === Service::BUILT_IN)) {
            throw new GraphQlInputException(__('Cannot add post comment'));
        }
        if (!$context->getExtensionAttributes()->getIsCustomer() && !$this->builtinConfig->isAllowGuestComments()) {
            throw new GraphQlInputException(__('To add a comment, please log in'));
        }
        $commentData = $this->postDataProcessor->process($args);
        $comment = $this->commentFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $comment,
            $commentData,
            CommentInterface::class
        );

        return $this->dataObjectProcessor->buildOutputDataArray(
            $this->commentService->createComment($comment),
            CommentInterface::class
        );
    }
}
