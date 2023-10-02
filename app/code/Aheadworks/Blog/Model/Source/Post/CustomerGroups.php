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
namespace Aheadworks\Blog\Model\Source\Post;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magento\Customer\Api\Data\GroupInterface;

/**
 * Class CustomerGroups
 * @package Aheadworks\Blog\Model\Source\Post
 */
class CustomerGroups implements OptionSourceInterface
{
    /**
     * Constant for 'All Groups' option
     */
    const ALL_GROUPS = 'all';

    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var DataObject
     */
    private $objectConverter;

    /**
     * @var array
     */
    private $options;

    /**
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DataObject $objectConverter
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $objectConverter
    ) {
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->objectConverter = $objectConverter;
    }

    /**
     * Prepare 'All Groups' option
     *
     * @return array
     */
    public function getAllGroupsOption()
    {
        return [
            'value' => self::ALL_GROUPS,
            'label' =>__('All Groups')
        ];
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = [];
            $customerGroups = $this->groupRepository->getList($this->searchCriteriaBuilder->create())->getItems();
            $this->options = $this->objectConverter->toOptionArray(
                $customerGroups,
                GroupInterface::ID,
                GroupInterface::CODE
            );
            array_unshift($this->options, $this->getAllGroupsOption());
        }
        return $this->options;
    }
}
