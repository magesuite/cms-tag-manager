<?php

namespace MageSuite\CmsTagManager\Model;

class TagsRepository implements \MageSuite\CmsTagManager\Api\TagsRepositoryInterface
{
    protected ResourceModel\Tags\CollectionFactory $collectionFactory;
    protected ResourceModel\Tags $tagsResource;
    protected \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $cmsPageCollection;
    protected \Magento\Cms\Helper\Page $cmsPageHelper;
    protected \MageSuite\ContentConstructorFrontend\Service\MediaResolver $mediaResolver;
    protected \Magento\Store\Model\StoreManagerInterface $storeManager;

    public function __construct(
        \MageSuite\CmsTagManager\Model\ResourceModel\Tags\CollectionFactory $collectionFactory,
        \MageSuite\CmsTagManager\Model\ResourceModel\Tags $tagsResource,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $cmsPageCollection,
        \Magento\Cms\Helper\Page $cmsPageHelper,
        \MageSuite\ContentConstructorFrontend\Service\MediaResolver $mediaResolver,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->tagsResource = $tagsResource;
        $this->cmsPageCollection = $cmsPageCollection;
        $this->cmsPageHelper = $cmsPageHelper;
        $this->mediaResolver = $mediaResolver;
        $this->storeManager = $storeManager;
    }

    public function getTagsByCmsPageId(int $id): array
    {
        $tagsCollection = $this->collectionFactory->create();

        $tagsCollection->getSelect()->where('cms_page_id =?', $id);

        return $tagsCollection->getColumnValues('tag_name');
    }

    public function getCmsPagesByTagName(string $tagName): array
    {
        $tagsCollection = $this->collectionFactory->create();

        $tagsCollection->getSelect()->where('tag_name =?', $tagName);

        return $tagsCollection->getColumnValues('cms_page_id');
    }

    public function getTag($pageId, $tagName): ?\Magento\Framework\DataObject
    {
        $tagsCollection = $this->collectionFactory->create();

        $tagsCollection->getSelect()
            ->where('cms_page_id =?', $pageId)
            ->where('tag_name =?', $tagName);

        if ($tagsCollection->getSize()) {
            return $tagsCollection->getFirstItem();
        }

        return null;
    }

    public function save(\MageSuite\CmsTagManager\Api\Data\TagsInterface $tag): \MageSuite\CmsTagManager\Api\Data\TagsInterface
    {
        if (!$this->getTag($tag->getCmsPageId(), $tag->getTagName())) {
            $this->tagsResource->save($tag);
        }

        return $tag;
    }

    public function delete($tag): void
    {
        if ($this->getTag($tag->getCmsPageId(), $tag->getTagName())) {
            $this->tagsResource->delete($tag);
        }
    }

    public function getAllTags(): array
    {
        $tagsCollection = $this->collectionFactory->create();
        $tagsCollection->getSelect()->group('tag_name');
        return $tagsCollection->getColumnValues('tag_name');
    }

    public function getCmsPageCollectionByTags(array $tags): \Magento\Cms\Model\ResourceModel\Page\Collection
    {
        $tagsCollection = $this->collectionFactory->create();

        $tagsCollection->addFieldToFilter('tag_name', ['in' => $tags]);

        $pagesIds = $tagsCollection->getColumnValues('cms_page_id');

        $collection = $this->cmsPageCollection->create();

        $collection->addFieldToFilter('page_id', ['in' => $pagesIds]);

        return $collection;
    }

    public function getCmsPageIdsByTags(array $tags): array
    {
        $tagsCollection = $this->collectionFactory->create();
        $tagsCollection->addFieldToFilter('tag_name', ['in' => $tags]);
        return $tagsCollection->getColumnValues('cms_page_id');
    }
}
