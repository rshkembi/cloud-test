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

namespace Aheadworks\Blog\Ui\Component\Post\Listing\MassAction\Author;

use Aheadworks\Blog\Model\Source\Authors;
use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;

/**
 * Class Options
 * @package Aheadworks\Blog\Ui\Component\Post\Listing\MassAction\Author
 */
class Options implements JsonSerializable
{
    /**
     * @var array
     */
    private $options;

    /**
     * Additional options params
     *
     * @var array
     */
    private $data;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Base URL for subactions
     *
     * @var string
     */
    private $urlPath;

    /**
     * Param name for subactions
     *
     * @var string
     */
    private $paramName;

    /**
     * Additional params for subactions
     *
     * @var array
     */
    private $additionalData = [];

    /**
     * Agent source
     *
     * @var Authors
     */
    private $authorSource;

    /**
     * @param UrlInterface $urlBuilder
     * @param Authors $authorSource
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Authors $authorSource,
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->authorSource = $authorSource;
        $this->data = $data;
    }

    /**
     * Get action options
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        if ($this->options === null) {
            $options = $this->authorSource->getAvailableOptions();
            $this->prepareData();
            foreach ($options as $authorId => $name) {
                $this->options[$authorId] = [
                    'type' => 'author_' . $authorId,
                    'label' => $name,
                ];

                if ($this->urlPath && $this->paramName) {
                    $this->options[$authorId]['url'] = $this->urlBuilder->getUrl(
                        $this->urlPath,
                        [$this->paramName => $authorId]
                    );
                }

                $this->options[$authorId] = array_merge_recursive(
                    $this->options[$authorId],
                    $this->additionalData
                );
            }

            $this->options = array_values((array)$this->options);
        }

        return $this->options;
    }

    /**
     * Prepare addition data for subactions
     *
     * @return void
     */
    private function prepareData()
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->paramName = $value;
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}
