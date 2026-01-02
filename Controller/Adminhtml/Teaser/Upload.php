<?php

declare(strict_types=1);

namespace MageSuite\CmsTagManager\Controller\Adminhtml\Teaser;

class Upload extends \Magento\Backend\App\Action
{
    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \MageSuite\Opengraph\Service\Processor\UploadImageFactory $uploadImage
    ) {
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultFactory
     */
    public function execute() //phpcs:ignore
    {
        try {
            $result = $this->uploadImage->create()->processUpload('cms_image_teaser', \MageSuite\CmsTagManager\Model\ImageTeaser::CMS_IMAGE_TEASER_PATH);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * @return bool
     */
    protected function _isAllowed() //phpcs:ignore
    {
        return true;
    }
}
