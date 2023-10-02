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
namespace Aheadworks\Blog\Block\Adminhtml\Widget\Instance\Edit\Tab\Main;

use Magento\Backend\Block\Template\Context;
use Aheadworks\Blog\ViewModel\Admin\Widget\Instance\Edit\Tab\Main\WidgetInstance;

/**
 * Class WidgetInstanceScript
 */
class WidgetInstanceScript extends \Magento\Backend\Block\Template
{
    protected $_template = 'Aheadworks_Blog::widget/instance/edit/script_init.phtml';

    /**
     * WidgetInstanceScript constructor.
     * @param Context $context
     * @param array $data
     * @param WidgetInstance $viewModel
     */
    public function __construct(
        Context $context,
        WidgetInstance $viewModel,
        array $data = []
    ) {
        $data['view_model'] = $viewModel;
        parent::__construct($context, $data);
    }
}