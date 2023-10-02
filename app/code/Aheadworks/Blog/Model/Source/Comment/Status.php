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

namespace Aheadworks\Blog\Model\Source\Comment;


use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    // Statuses to store in DB
    public const PENDING = 'pending';
    public const APPROVE = 'approve';
    public const REJECT = 'reject';

    /**
     * @var array
     */
    private $options;

    /**
     * Array of the view
     * ['value' => 'label', 'value' => 'label', 'value' => 'label']
     *
     * @return array
     */
    public function getOptionsForCommentForm()
    {
        return [
            self::PENDING => __('Pending'),
            self::APPROVE => __('Approve'),
            self::REJECT => __('Reject')
        ];
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];
            foreach ($this->getOptionsForCommentForm() as $value => $label) {
                $this->options[] = ['value' => $value, 'label' => $label];
            }
        }
        return $this->options;
    }

    /**
     * Array of the view
     * [['value' => '', 'label' = ''], ['value' => '', 'label' = ''], ['value' => '', 'label' = '']]
     *
     * @return array
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }

    /**
     * Retrieve display statuses array
     *
     * @return array
     */
    public function getDisplayStatuses(): array
    {
        return [self::APPROVE];
    }

    /**
     * Retrieve list of rejected statuses
     *
     * @return array
     */
    public function getRejectedStatusList(): array
    {
        return [self::REJECT];
    }
}
