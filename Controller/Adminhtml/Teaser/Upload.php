<?php

namespace MageSuite\CmsTagManager\Controller\Adminhtml\Teaser;

class Upload extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \MageSuite\Opengraph\Service\Processor\UploadImageFactory
     */
    protected $uploadImage;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MageSuite\Opengraph\Service\Processor\UploadImageFactory $uploadImage
    ) {
        parent::__construct($context);
        $this->uploadImage = $uploadImage;
    }

    /**
     * @return \Magento\Framework\Controller\ResultFactory
     */
    public function execute()
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
    protected function _isAllowed()
    {
        return true;
    }
}
