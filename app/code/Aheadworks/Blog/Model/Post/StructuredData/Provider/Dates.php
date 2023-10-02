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
namespace Aheadworks\Blog\Model\Post\StructuredData\Provider;

use Aheadworks\Blog\Model\Post\StructuredData\ProviderInterface;
use Aheadworks\Blog\Model\DateTime\Formatter as DateTimeFormatter;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Dates
 *
 * @package Aheadworks\Blog\Model\Post\StructuredData\Provider
 */
class Dates implements ProviderInterface
{
    /**
     * @var DateTimeFormatter
     */
    private $dateTimeFormatter;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param DateTimeFormatter $dateTimeFormatter
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        DateTimeFormatter $dateTimeFormatter,
        StoreManagerInterface $storeManager
    ) {
        $this->dateTimeFormatter = $dateTimeFormatter;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($post)
    {
        $data = [];

        $datePublished = $post->getPublishDate();
        if (!empty($datePublished)) {
            $data["datePublished"] = $this->getDateInIsoFormat($datePublished);
        }

        $dateModified = $post->getUpdatedAt();
        if (!empty($dateModified)) {
            $data["dateModified"] = $this->getDateInIsoFormat($dateModified);
        }

        return $data;
    }

    /**
     * Retrieve date in the ISO 8601 format
     *
     * @param string $date
     * @return string
     */
    protected function getDateInIsoFormat($date)
    {
        $currentStoreId = $this->getCurrentStoreId();
        $dateInIsoFormat = $this->dateTimeFormatter->getLocalizedDateTime(
            $date,
            $currentStoreId,
            \DateTime::ISO8601
        );
        return $dateInIsoFormat;
    }

    /**
     * Retrieve current store id
     *
     * @return int|null
     */
    protected function getCurrentStoreId()
    {
        try {
            $currentStoreId = $this->storeManager->getStore(true)->getId();
        } catch (NoSuchEntityException $exception) {
            $currentStoreId = null;
        }
        return $currentStoreId;
    }
}
