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
namespace Aheadworks\Blog\Model\Widget;

use Magento\Framework\App\RequestInterface;

/**
 * Class RequestDataProvider
 */
class RequestDataProvider
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Framework\Math\Random
     */
    private $mathRandom;

    /**
     * RequestDataProvider constructor.
     * @param RequestInterface $request
     * @param \Magento\Framework\Math\Random $mathRandom
     */
    public function __construct(
        RequestInterface $request,
        \Magento\Framework\Math\Random $mathRandom
    ) {
        $this->request = $request;
        $this->mathRandom = $mathRandom;
    }

    /**
     * Retrieve selected nodes
     *
     * @return array
     */
    public function getSelectedNodes()
    {
        $selected = $this->request->getParam('selected', '');
        $selected = !is_array($selected) ? explode(',', (string)$selected) : $selected;

        return $selected;
    }

    /**
     * Retrieve Unique Id
     *
     * @return string|null
     */
    public function getUniqId()
    {
        return $this->request->getParam('uniq_id');
    }

    /**
     * Generate Unique Id
     *
     * @param string $code
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generateUniqId($code)
    {
        return $this->getUniqId() ?? $this->mathRandom->getUniqueHash($code);
    }

    /**
     * Retrieve Display Type
     *
     * @return string|null
     */
    public function getDisplayType()
    {
        return $this->request->getParam('display_type', '');
    }

    /**
     * Retrieve Code
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->request->getParam('code', '');
    }

    /**
     * Retrieve default data
     *
     * @return array
     */
    public function getDefaultData()
    {
        return [
            'id' => $this->generateUniqId($this->getCode()),
            'selected_nodes' =>  $this->getSelectedNodes(),
            'display_type' => $this->getDisplayType()
        ];
    }
}
