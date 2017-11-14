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
            $expires = $this->getRequest()->getParam('expires', 0);
            $products = $this->getRequest()->getParam('products', false);

            if($expires > time() && $products != false){
                $this->_addProductsToCart($products);
                $this->getResponse()->setRedirect('checkout/cart/index');
            } else {
                $this->messageManager->addWarning(__('This link seems to be expired or broken. Please contact customer support for assistance.'));
                $this->getResponse()->setRedirect('checkout/cart/index');
            }
            
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError(__('There appears to have been an error. Please contact customer support for assistance.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__('There appears to have been an error. Please contact customer support for assistance.'));
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
    private function _addProductsToCart($products)
    {
        $productIds = explode(',', $products);

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
