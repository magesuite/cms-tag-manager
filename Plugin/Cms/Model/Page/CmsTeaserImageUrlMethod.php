<?php
namespace MageSuite\CmsTagManager\Plugin\Cms\Model\Page;

class CmsTeaserImageUrlMethod
{
    public function aroundGetData(\Magento\Cms\Model\Page $subject, callable $proceed, $key = '', $index = null)
    {
        if ($key != 'cms_teaser_image_url') {
            return $proceed($key, $index);
        }

        return \MageSuite\CmsTagManager\Model\ImageTeaser::CMS_IMAGE_TEASER_PATH . $subject->getCmsImageTeaser();
    }
}
