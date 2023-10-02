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
use Magento\Framework\Stdlib\DateTime\DateTime;
use Aheadworks\Blog\Api\Data\PostInterface;

/**
 * Class PublishDate
 * @package Aheadworks\Blog\Model\Data\Processor\Post
 */
class PublishDate implements ProcessorInterface
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @param DateTime $dateTime
     */
    public function __construct(
        DateTime $dateTime
    ) {
        $this->dateTime = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function process($data)
    {
        $saveAction = isset($data['action']) ? $data['action'] : null;

        if ($saveAction == 'publish' || $saveAction == 'schedule') {
            $data[PostInterface::PUBLISH_DATE] = $this->getPreparedPublishDate($data);
        }

        return $data;
    }

    /**
     * Get prepared publish date
     *
     * @param array $postData
     * @return string
     */
    private function getPreparedPublishDate(array $postData)
    {
        $publishDate = $this->dateTime->gmtDate(
            \Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT
        );
        if (!empty($postData['publish_date'])) {
            $publishDateTimestamp = strtotime($postData['publish_date']);
            $publishDate = $this->dateTime->gmtDate(
                \Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT,
                $publishDateTimestamp
            );
        }
        return $publishDate;
    }
}
