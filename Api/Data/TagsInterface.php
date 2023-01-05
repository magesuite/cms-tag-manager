<?php

namespace MageSuite\CmsTagManager\Api\Data;

interface TagsInterface
{
    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return void
     */
    public function setEntityId($entityId);

    /**
     * @return string
     */
    public function getCmsPageId();

    /**
     * @param string $cmsPageID
     * @return void
     */
    public function setCmsPageId($cmsPageID);

    /**
     * @return mixed
     */
    public function getTagName();

    /**
     * @param $tagName
     * @return mixed
     */
    public function setTagName($tagName);
}
