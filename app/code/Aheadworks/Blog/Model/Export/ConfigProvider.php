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
namespace Aheadworks\Blog\Model\Export;

/**
 * Class ConfigEntity
 */
class ConfigProvider
{
    /**
     * @var array
     */
    private $config;

    /**
     * ConfigProvider constructor.
     * @param array $config
     */
    public function __construct(
        $config = []
    ) {
        $this->config = $config;
    }

    /**
     * Retrieve export configuration
     *
     * @return array
     */
    public function getExportConfig()
    {
        return $this->config;
    }
}