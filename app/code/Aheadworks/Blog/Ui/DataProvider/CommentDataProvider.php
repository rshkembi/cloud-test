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
declare(strict_types=1);

namespace Aheadworks\Blog\Ui\DataProvider;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Post\Comment;
use Aheadworks\Blog\Model\ResourceModel\Post\Comment\Grid\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\Modifier\PoolInterface as ModifierPoolInterface;

class CommentDataProvider extends AbstractDataProvider
{
    /**
     * Data persistor key
     */
    public const DATA_PERSISTOR_KEY = 'aw_blog_comment_form';

    /**
     * @param string $name
     * @param ModifierPoolInterface $modifierPool
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param DataPersistorInterface $dataPersistor
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $addFilterStrategyList
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        ModifierPoolInterface $modifierPool,
        CollectionFactory $collectionFactory,
        private readonly RequestInterface $request,
        private readonly DataPersistorInterface $dataPersistor,
        $primaryFieldName = CommentInterface::ID,
        $requestFieldName = CommentInterface::ID,
        array $addFilterStrategyList = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $modifierPool,
            $addFilterStrategyList,
            $meta,
            $data
        );
        $this->collection = $collectionFactory->create();
    }

    /**
     * Get config data
     *
     * @return array
     */
    public function getConfigData()
    {
        if ($this->request->getActionName() === 'reply') {
            return ['submit_url' => $this->data['reply_url']];
        }
        if ($this->request->getActionName() === 'edit') {
            return ['submit_url' => $this->data['save_url']];
        }

        return parent::getConfigData();
    }

    /**
     * Get data
     *
     * @return array
     * @throws LocalizedException
     */
    public function getData(): array
    {
        $data = [];
        $dataFromForm = $this->dataPersistor->get(self::DATA_PERSISTOR_KEY);
        $commentId = $this->request->getParam($this->getRequestFieldName());
        if ($this->request->getActionName() === 'reply') {
            $data[$this->request->getParam('id')] = $this->prepareItemsData([]);

            return $data;
        }
        if (!empty($dataFromForm)) {
            $data = $dataFromForm;
            $this->dataPersistor->clear(self::DATA_PERSISTOR_KEY);
        } else {
            $commentList = $this->getCollection()
                ->addFieldToFilter(CommentInterface::ID, $commentId)
                ->getItems();
            /** @var Comment $comment */
            foreach ($commentList as $comment) {
                if ($commentId === $comment->getId()) {
                    $data[$commentId] = $comment->getData() ? $this->prepareItemsData($comment->getData()) : null;
                }
            }
        }

        return $data;
    }
}
