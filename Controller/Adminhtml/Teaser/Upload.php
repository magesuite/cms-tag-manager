<?php

declare(strict_types=1);

namespace MageSuite\CmsTagManager\Controller\Adminhtml\Teaser;

class Upload extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_Cms::page';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \MageSuite\Opengraph\Service\Processor\UploadImageFactory $uploadImage
    ) {
        parent::__construct($context);
    }

    public function execute() //phpcs:ignore
    {
        try {
            $result = $this->uploadImage->create()->processUpload('cms_image_teaser', \MageSuite\CmsTagManager\Model\ImageTeaser::CMS_IMAGE_TEASER_PATH);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)->setData($result);
    }
}
