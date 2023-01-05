<?php

namespace MageSuite\CmsTagManager\DataProviders;

class ImageTeaser extends \MageSuite\Opengraph\DataProviders\TagProvider implements \MageSuite\Opengraph\DataProviders\TagProviderInterface
{
    protected \Magento\Cms\Api\Data\PageInterface $page;
    protected \MageSuite\Opengraph\Service\CmsImageUrlProvider $cmsImageUrlProvider;
    protected \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory;
    protected \MageSuite\Opengraph\Helper\Mime $mimeHelper;

    protected $tags = [];

    public function __construct(
        \Magento\Cms\Api\Data\PageInterface $page,
        \MageSuite\Opengraph\Service\CmsImageUrlProvider $cmsImageUrlProvider,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory,
        \MageSuite\Opengraph\Helper\Mime $mimeHelper
    ) {
        $this->page = $page;
        $this->cmsImageUrlProvider = $cmsImageUrlProvider;
        $this->tagFactory = $tagFactory;
        $this->mimeHelper = $mimeHelper;
    }

    public function getTags()
    {
        if (!$this->page->getIdentifier()) {
            return [];
        }

        $this->addImageTag();

        return $this->tags;
    }

    private function addImageTag()
    {
        $pageData = array_filter($this->page->getData());
        $cmsImageTeaser = $this->page->getCmsImageTeaser();

        if (!$cmsImageTeaser) {
            return;
        }

        $imageUrl = $this->cmsImageUrlProvider->getImageUrl($cmsImageTeaser, \MageSuite\CmsTagManager\Model\ImageTeaser::CMS_IMAGE_TEASER_PATH);

        if (!$imageUrl) {
            return;
        }

        $tag = $this->tagFactory->getTag('image', $imageUrl);
        $this->addTag($tag);

        $mimeType = $this->mimeHelper->getMimeType($imageUrl);

        if ($mimeType) {
            $tag = $this->tagFactory->getTag('image:type', $mimeType);
            $this->addTag($tag);
        }

        $title = $pageData['og_title'] ?? $pageData['meta_title'] ?? $pageData['title'] ?? null;

        if ($title) {
            $tag = $this->tagFactory->getTag('image:alt', $title);
            $this->addTag($tag);
        }
    }
}
