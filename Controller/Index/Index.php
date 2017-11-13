<?php
/**
 * Adds product directly to cart through custom created link.
 * Copyright (C) 2017  
 * 
 * This file included in EnvoyTest/DirectToCart is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace EnvoyTest\DirectToCart\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $productRepository;
    protected $cart;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $params = $this->getRequest()->getParams();
            
            $this->_addProductsToCart($params);
            
            $this->getResponse()->setRedirect('checkout/cart/index');
            //return $this->jsonResponse($params);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addSuccess(__('There appears to have been an error. Please contact customer support.'));
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addSuccess(__('There appears to have been an error. Please contact customer support.'));
        }
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }

    /**
     * Add products to cart
     */
    private function _addProductsToCart($params)
    {
        $productIds = explode(',', $params['products']);

        foreach ($productIds as $prodId) {
            $details = ['qty' => 1];
            $_product = $this->productRepository->getById($prodId);
            if($_product){
                $this->cart->addProduct($_product, $details);
                $this->cart->save();
            }
        }

        if(count($productIds) > 1){
            $this->messageManager->addSuccess(__('Products added to cart successfully.'));
        } else {
            $this->messageManager->addSuccess(__('Product added to cart successfully.'));
        }
    }
}
