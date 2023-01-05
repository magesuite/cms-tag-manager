<?php

namespace MageSuite\CmsTagManager\Service;

class Resize
{
    const PLACEHOLDER_DIRECTORY = 'catalog/product/placeholder/';

    protected \Magento\Framework\View\ConfigInterface $viewConfig;
    protected \Magento\Framework\Config\View $configView;
    protected \Magento\Framework\Filesystem $filesystem;
    protected \MageSuite\ImageResize\Helper\Configuration $configuration;
    protected \MageSuite\ImageResize\Service\Image\Resize $imageResize;
    protected \Magento\Framework\Filesystem\Io\File $file;

    public function __construct(
        \Magento\Framework\View\ConfigInterface $viewConfig,
        \Magento\Framework\Config\View $configView,
        \Magento\Framework\Filesystem $filesystem,
        \MageSuite\ImageResize\Helper\Configuration $configuration,
        \MageSuite\ImageResize\Service\Image\Resize $imageResize,
        \Magento\Framework\Filesystem\Io\File $file
    ) {
        $this->viewConfig = $viewConfig;
        $this->configView = $configView;
        $this->filesystem = $filesystem;
        $this->configuration = $configuration;
        $this->imageResize = $imageResize;
        $this->file = $file;
    }

    public function getUrl($imageUrl, $module, $imageId)
    {
        if ($imageUrl === null) {
            return $this->getPlaceholderUrl();
        }

        $configuration = $this->getConfigView()->getMediaAttributes($module, \Magento\Catalog\Helper\Image::MEDIA_TYPE_CONFIG_NODE, $imageId);

        if (!isset($configuration['width']) || !isset($configuration['height'])) {
            return $this->configuration->getMediaBaseUrl() . $imageUrl;
        }

        return $this->getResizedImageUrl($imageUrl, $configuration);
    }

    protected function getPlaceholderUrl()
    {
        $placeholderPathFromConfig = $this->configuration->getPlaceholderPathFromConfig();

        if (!$placeholderPathFromConfig) {
            return $this->configuration->getDefaultPlaceholderUrl();
        }

        return $this->configuration->getMediaBaseUrl() . self::PLACEHOLDER_DIRECTORY . $placeholderPathFromConfig;
    }

    protected function getConfigView(): \Magento\Framework\Config\View
    {
        if (!$this->configView) {
            $this->configView = $this->viewConfig->getViewConfig();
        }
        return $this->configView;
    }

    protected function getResizedImageUrl($imageUrl, $configuration): string
    {
        $imagePath = $this->prepareResizedImagePath($imageUrl, $configuration);

        $path = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath($imagePath);

        if (!$this->file->fileExists($path)) {
            $this->resizeImage($imageUrl, $imagePath, $configuration);
        }

        return $this->configuration->getMediaBaseUrl() . $imagePath;
    }

    protected function prepareResizedImagePath($imageUrl, $configuration)
    {
        $pathInfo = $this->file->getPathInfo($imageUrl);

        if (!isset($pathInfo['dirname']) || !isset($pathInfo['basename'])) {
            return $imageUrl;
        }

        return sprintf('%s/%sx%s/%s', $pathInfo['dirname'], $configuration['width'], $configuration['height'], $pathInfo['basename']);
    }

    protected function resizeImage($sourcePath, $destinationPath, $configuration)
    {
        $configuration['image_file'] = $sourcePath;
        $configuration['dest_path'] = $destinationPath;

        $this->imageResize->setIsFullImagePath(true);
        $this->imageResize->resize($configuration, true);
    }

    public function resolveSrcSet($imageUrl, $module, $imageIds)
    {
        $srcSet = [];

        foreach ($imageIds as $imageId) {
            $configuration = $this->getConfigView()->getMediaAttributes($module, \Magento\Catalog\Helper\Image::MEDIA_TYPE_CONFIG_NODE, $imageId);

            if (!isset($configuration['width']) || !isset($configuration['height'])) {
                continue;
            }

            $srcSet[] =  $this->getResizedImageUrl($imageUrl, $configuration);
        }

        if (count($srcSet) != 2) {
            return '';
        }

        return vsprintf('%s, %s 2x', $srcSet);
    }
}
