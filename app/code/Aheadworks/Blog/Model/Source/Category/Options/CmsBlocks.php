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
namespace Aheadworks\Blog\Model\Source\Category\Options;

use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\BlockRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class CmsBlocks
 */
class CmsBlocks implements OptionSourceInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var BlockRepository
     */
    private $blockRepository;

    /**
     * @var DataObject
     */
    private $objectConverter;

    /**
     * @var array
     */
    private $options;

    /**
     * Construct
     *
     * @param BlockRepository $blockRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DataObject $objectConverter
     */
    public function __construct(
        BlockRepository $blockRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $objectConverter
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->blockRepository = $blockRepository;
        $this->objectConverter = $objectConverter;
    }

    /**
     * Return all block options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->searchCriteriaBuilder->addFilter(BlockInterface::IS_ACTIVE, true);
            $cmsBlocks = $this->blockRepository->getList($this->searchCriteriaBuilder->create())->getItems();
            $this->options = $this->objectConverter->toOptionArray(
                $cmsBlocks,
                BlockInterface::BLOCK_ID,
                BlockInterface::TITLE
            );
            array_unshift($this->options, ['value' => '', 'label' => __('Please select a static block.')]);
        }

        return $this->options;
    }
}