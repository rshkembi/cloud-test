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
namespace Aheadworks\Blog\Ui\Component\Post\Form\Element;

use Magento\Ui\Component\Form\Element\Input;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class StatusLabel
 * @package Aheadworks\Blog\Ui\Component\Post\Form\Element
 */
class StatusLabel extends Input
{
    /**
     * @var \Aheadworks\Blog\Model\Source\Post\Status
     */
    private $statusSource;

    /**
     * @param ContextInterface $context
     * @param \Aheadworks\Blog\Model\Source\Post\Status $statusSource
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        \Aheadworks\Blog\Model\Source\Post\Status $statusSource,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
        $this->statusSource = $statusSource;
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        $config = $this->getData('config');
        if (!isset($config['statusOptions'])) {
            $config['statusOptions'] = $this->statusSource->getOptionsForPostForm();
            $this->setData('config', $config);
        }
        parent::prepare();
    }
}
