<?php
/**
 * @author Alhayat MagentDev
 * @copyriht Copyright (c) 2019 Eguana {http://alhayatmagentdev.com}
 * Created by PhpStorm
 * User: mudasser
 * Date: 16/10/19
 * Time: 9:38 PM
 */

namespace Alhayat\CustomerRedirecting\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;
use Zend\Validator\Uri;

class CustomerLogin implements ObserverInterface
{
    const XML_PATH_TO_DASHBOARD_REDIRECT = 'customer/startup/redirect_dashboard';
    const XML_PATH_TO_REDIRECT_CUSTOM_PAGE = 'customer/startup/custom_page_for_redirecting';
    /**
     * @var ResponseFactory $responseFactory
     */
    private $responseFactory;

    /**
     * @var Uri
     */
    protected $uri;

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    private $scopeConfig;

    /**
     * CustomerLogin constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Uri $uri
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Uri $uri,
        ResponseFactory $responseFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->uri = $uri;
        $this->responseFactory = $responseFactory;
    }

    /**
     * SHORT DESCRIPTION
     * LONG DESCRIPTION LINE BY LINE
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $redirectDashboard = $this->scopeConfig->isSetFlag(self::XML_PATH_TO_DASHBOARD_REDIRECT, ScopeInterface::SCOPE_WEBSITES);

        // if the Redirect Customer to Account Dashboard after Logging in set to "No"
        if (!$redirectDashboard) {
            $customPage = $this->scopeConfig->getValue(self::XML_PATH_TO_REDIRECT_CUSTOM_PAGE, ScopeInterface::SCOPE_WEBSITES);
            if (!empty($customPage) && $this->uri->isValid($customPage)) {
                $resultRedirect = $this->responseFactory->create();
                $resultRedirect->setRedirect($customPage);
                $resultRedirect->sendResponse();
                exit();
            }
        }
    }
}
