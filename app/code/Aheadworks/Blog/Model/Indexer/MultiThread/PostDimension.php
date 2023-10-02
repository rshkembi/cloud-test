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
namespace Aheadworks\Blog\Model\Indexer\MultiThread;

/**
 * Class PostDimension
 *
 * @package Aheadworks\Blog\Model\Indexer\MultiThread
 */
class PostDimension
{
    /**
     * Name for post dimension for multidimensional indexer
     * _pp means post part
     */
    const DIMENSION_NAME = '_pp';

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $value;

    /**
     * @param string $name
     * @param array $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Get dimension name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get value
     *
     * @return array
     */
    public function getValue()
    {
        return $this->value;
    }
}
