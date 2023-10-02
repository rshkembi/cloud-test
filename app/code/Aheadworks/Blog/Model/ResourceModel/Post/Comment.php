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

namespace Aheadworks\Blog\Model\ResourceModel\Post;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Magento\Framework\DataObject;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Comment extends AbstractDb
{
    /**#@+
     * Constants defined for tables
     * used by corresponding entity
     */
    public const MAIN_TABLE_NAME = 'aw_blog_comment';
    /**#@-*/

    public function __construct(
        Context $context,
        private readonly EntityManager $entityManager,
        $connectionName = null
    ){
        parent::__construct($context, $connectionName);
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE_NAME, CommentInterface::ID);
    }

    /**
     * Save object object data
     *
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object): self
    {
        $object->validateBeforeSave();
        $this->beforeSave($object);
        $entity = $this->entityManager->save($object);
        $this->afterSave($entity);

        return $this;
    }

    /**
     * Load an object
     *
     * @param AbstractModel $object
     * @param int $objectId
     * @param string $field field to load by (defaults to model id)
     * @return $this
     */
    public function load($object, $objectId, $field = null): self
    {
        if (!empty($objectId)) {
            $this->entityManager->load($object, $objectId, []);
            $object->afterLoad();
        }

        return $this;
    }

    /**
     * Delete the object
     *
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function delete(AbstractModel $object): self
    {
        $this->entityManager->delete($object);

        return $this;
    }

    /**
     * Before save action
     *
     * @param DataObject $object
     * @return $this|Comment
     */
    protected function _beforeSave(DataObject $object)
    {
        parent::_beforeSave($object);

        if ($object->isObjectNew() && $object->getReplyToCommentId()) {
            $object->setPath($object->getPath() . '/');
        } elseif ($object->isObjectNew()) {
            $object->setPath(null);
        }

        return $this;
    }

    /**
     * After save action
     *
     * @param AbstractModel $object
     * @return Comment
     * @throws LocalizedException
     */
    protected function _afterSave(AbstractModel $object)
    {
        if (substr((string)$object->getPath(), -1) === '/') {
            $object->setPath($object->getPath() . $object->getId());
            $this->_savePath($object);
        } elseif (!$object->getPath()) {
            $object->setPath((string)$object->getId());
            $this->_savePath($object);
        }

        return parent::_afterSave($object);
    }

    /**
     * Update path field
     *
     * @param  $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _savePath($object)
    {
        if ($object->getId()) {
            $this->getConnection()->update(
                $this->getMainTable(),
                ['path' => $object->getPath()],
                ['id = ?' => $object->getId()]
            );
        }

        return $this;
    }
}
