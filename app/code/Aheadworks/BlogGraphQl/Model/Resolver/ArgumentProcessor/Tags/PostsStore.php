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
namespace Aheadworks\BlogGraphQl\Model\Resolver\ArgumentProcessor\Tags;

use Aheadworks\BlogGraphQl\Model\Resolver\ArgumentHelper;
use Aheadworks\BlogGraphQl\Model\Resolver\ArgumentProcessor\ProcessorInterface;
use Magento\Store\Model\Store;

/**
 * Class PostsStore
 * @package Aheadworks\BlogGraphQl\Model\Resolver\ArgumentProcessor\Tags
 */
class PostsStore implements ProcessorInterface
{
    /**
     * @var ArgumentHelper
     */
    private $argumentHelper;

    /**
     * @param ArgumentHelper $argumentHelper
     */
    public function __construct(
        ArgumentHelper $argumentHelper
    ) {
        $this->argumentHelper = $argumentHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function process($data, $context)
    {
        $data['filter']['posts_store']['in'] = [$this->argumentHelper->getStoreId($data), Store::DEFAULT_STORE_ID];
        return $data;
    }
}
