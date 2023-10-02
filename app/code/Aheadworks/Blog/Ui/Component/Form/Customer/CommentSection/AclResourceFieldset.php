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

namespace Aheadworks\Blog\Ui\Component\Form\Customer\CommentSection;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\ComponentVisibilityInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\Form\Fieldset as UiFieldset;

class AclResourceFieldset extends UiFieldset implements ComponentVisibilityInterface
{
    /**
     * @param ContextInterface $context
     * @param AuthorizationInterface $authorization
     * @param UiComponentInterface[] $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        protected AuthorizationInterface $authorization,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
    }

    /**
     * Retrieve acl resource for the fieldset
     *
     * @return string
     */
    public function getAclResource(): string
    {
        return (string)$this->getData('acl_resource');
    }

    /**
     * Defines if the component can be shown
     *
     * @return bool
     */
    public function isComponentVisible(): bool
    {
        return $this->authorization->isAllowed($this->getAclResource());
    }

    /**
     * Prepare component configuration
     *
     * @return void
     */
    public function prepare()
    {
        parent::prepare();

        $config = (array)$this->getData('config');
        $config['componentDisabled'] = !$this->isComponentVisible();
        $this->setData('config', $config);
    }
}
