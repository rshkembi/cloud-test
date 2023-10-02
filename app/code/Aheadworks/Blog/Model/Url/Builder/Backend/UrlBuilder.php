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

namespace Aheadworks\Blog\Model\Url\Builder\Backend;

use Magento\Backend\Model\Url as BackendUrlBuilder;
use Magento\Store\Model\Store;

class UrlBuilder extends BackendUrlBuilder
{
    /**
     * Disable store in urls, regardless of the scope
     *
     * @return Store
     */
    protected function _getScope()
    {
        return parent::_getScope()
            ->setData('disable_store_in_url', true);
    }
}
