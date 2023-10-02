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

namespace Aheadworks\Blog\Plugin\Customer\Model\Customer;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class DataProviderPlugin
{
    /**
     * @param ModifierInterface $modifier
     */
    public function __construct(
        private readonly ModifierInterface $modifier
    ) {
    }

    /**
     * Add Blog additional data to the prepared data provider data
     *
     * @param AbstractDataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetData(
        AbstractDataProvider $subject,
        array $result
    ) {
        $result = $this->modifier->modifyData($result);

        return $result;
    }

    /**
     * Add Blog additional meta to the prepared data provider meta
     *
     * @param AbstractDataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetMeta(
        AbstractDataProvider $subject,
        array $result
    ) {
        $result = $this->modifier->modifyMeta($result);

        return $result;
    }
}
