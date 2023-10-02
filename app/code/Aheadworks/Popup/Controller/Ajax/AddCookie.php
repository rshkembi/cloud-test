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
 * @package    Popup
 * @version    1.2.9
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\Popup\Controller\Ajax;

use Aheadworks\Popup\Controller\Ajax;
use Aheadworks\Popup\Model\Source\Event;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Aheadworks\Popup\Model\PopupFactory;

class AddCookie extends Ajax
{
    const DEFAULT_COOKIE_LIFETIME = 86400;

    /**
     * Constructor
     *
     * @param Session $customerSession
     * @param Validator $formKeyValidator
     * @param Context $context
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param PopupFactory $popupModelFactory
     */
    public function __construct(
        Session $customerSession,
        Validator $formKeyValidator,
        Context $context,
        private readonly CookieManagerInterface $cookieManager,
        private readonly CookieMetadataFactory $cookieMetadataFactory,
        private readonly PopupFactory $popupModelFactory
    ) {
        parent::__construct(
            $customerSession,
            $formKeyValidator,
            $context
        );
    }

    /**
     * Add public cookie
     *
     * @return ResponseInterface|ResultInterface|void
     * @throws InputException
     * @throws CookieSizeLimitReachedException
     * @throws FailureToSendException
     */
    public function execute()
    {
        $lifetime = $this->getRequest()->getParam('cookie_lifetime', self::DEFAULT_COOKIE_LIFETIME);
        $type = $this->getRequest()->getParam('cookie_type');
        switch ($type) {
            case Event::VIEWED_POPUP_COUNT_COOKIE_NAME:
                $resultValue = $this->getRequest()->getParam('popup_id', null);
                $name = $type . '_' . $resultValue;
                $value = $resultValue;
                $this->addViewToPopup($resultValue);
                break;
            case Event::USED_POPUP_COUNT_COOKIE_NAME:
                $resultValue = $this->getRequest()->getParam('popup_id', null);
                $name = $type . '_' . $resultValue;
                $value = $resultValue;
                if (false !== $this->isUsedPopup($name)) {
                    $resultValue = null;
                } else {
                    $this->addClickToPopup($resultValue);
                }
                break;
            default:
                $resultValue = $this->getRequest()->getParam('current_url', '');
                $name = $type;
                $value = $this->getPageCountArrayValue();
                $value[] = hash('sha256', $resultValue);
                $value = json_encode(array_unique($value));
                break;
        }

        if ($resultValue) {
            $cookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
                ->setDuration($lifetime)
                ->setPath($this->customerSession->getCookiePath())
                ->setDomain($this->customerSession->getCookieDomain())
                ->setSecure(false)
                ->setHttpOnly(false);

            $this->cookieManager->setPublicCookie($name, $value, $cookieMetadata);
        }
    }

    /**
     * Get viewed pages (private)
     *
     * @return array|mixed
     */
    private function getPageCountArrayValue()
    {
        $cookieValue = $this->cookieManager->getCookie(
            Event::VIEWED_PAGE_COUNT_COOKIE_NAME
        );
        if (null !== $cookieValue) {
            $cookieArray = json_decode($cookieValue);
            if (strlen($cookieValue) > 2000) {
                array_shift($cookieArray);
            }
            return $cookieArray;
        } else {
            return [];
        }
    }

    /**
     * Check if used popup (private)
     *
     * @param string $cookieName
     * @return bool
     */
    private function isUsedPopup($cookieName)
    {
        $cookieValue = $this->cookieManager->getCookie($cookieName);
        if (null !== $cookieValue) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Increase popup clicks (private)
     *
     * @param int $popupId
     * @return $this
     * @throws \Exception
     */
    private function addClickToPopup($popupId)
    {
        $popupModel = $this->popupModelFactory->create();
        $popupModel->load($popupId);
        if ($popupModel->getId()) {
            $popupModel->setClickCount($popupModel->getClickCount() + 1);
            $popupModel->save();
        }
        return $this;
    }

    /**
     * Increase popup views (private)
     *
     * @param int $popupId
     * @return $this
     * @throws \Exception
     */
    private function addViewToPopup($popupId)
    {
        $popupModel = $this->popupModelFactory->create();
        $popupModel->load($popupId);
        if ($popupModel->getId()) {
            $popupModel->setViewCount($popupModel->getViewCount() + 1);
            $popupModel->save();
        }
        return $this;
    }
}
