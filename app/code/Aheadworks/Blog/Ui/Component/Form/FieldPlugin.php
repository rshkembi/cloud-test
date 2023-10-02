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
namespace Aheadworks\Blog\Ui\Component\Form;

use Magento\Ui\Component\Form\Field;
use Magento\PageBuilder\Model\Config;
use Aheadworks\Blog\Model\PageBuilderConfigFactory;

/**
 * Class FieldPlugin
 * @package Aheadworks\Blog\Ui\Component\Form
 */
class FieldPlugin
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Fields name
     *
     * @var array
     */
    private $fields = ['short_content', 'content'];

    /**
     * @param PageBuilderConfigFactory $configFactory
     */
    public function __construct(PageBuilderConfigFactory $configFactory)
    {
        $this->config = $configFactory->create();
    }

    /**
     * @param Field $subject
     * @return Field
     */
    public function beforePrepare(Field $subject)
    {
        if (in_array($subject->getData('name'), $this->fields)
            && is_object($this->config)
            && $this->config->isEnabled()
            && $subject->getData('config/formElement') == 'wysiwyg'
            && isset($subject->getData('config')['source'])
            && $subject->getData('config')['source'] == 'post'
        ) {
            $config = $subject->getData('config');
            $config['component'] = 'Aheadworks_Blog/js/ui/form/element/wysiwyg';
            $subject->setData('config', $config);
        }

        return $subject;
    }
}
