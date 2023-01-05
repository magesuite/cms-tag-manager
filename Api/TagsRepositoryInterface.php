<?php

namespace MageSuite\CmsTagManager\Api;

interface TagsRepositoryInterface
{
    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTagsByCmsPageId(int $id): array;

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCmsPagesByTagName(string $tagName): array;

    public function getTag($pageId, $tagName): ?\Magento\Framework\DataObject;
    public function save(\MageSuite\CmsTagManager\Api\Data\TagsInterface $tag): \MageSuite\CmsTagManager\Api\Data\TagsInterface;

    /**
     * @param \MageSuite\CmsTagManager\Api\Data\TagsInterface $tag
     */
    public function delete($tag): void;
    public function getAllTags(): array;
    public function getCmsPageCollectionByTags(array $tags): \Magento\Cms\Model\ResourceModel\Page\Collection;
    public function getCmsPageIdsByTags(array $tags): array;
}
