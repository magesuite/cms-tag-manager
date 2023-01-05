<?php
namespace MageSuite\CmsTagManager\Test\Integration\Service\Mapper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CmsPageDataMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\CmsTagManager\Service\Mapper\CmsPageDataMapper
     */
    protected $dataMapper;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\Collection
     */
    protected $cmsPageCollection;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->dataMapper = $this->objectManager->create(\MageSuite\CmsTagManager\Service\Mapper\CmsPageDataMapper::class);

        $this->cmsPageCollection = $this->objectManager->create(\Magento\Cms\Model\ResourceModel\Page\Collection::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadPages
     */
    public function testItMapsPageCorrectly()
    {
        $this->prepareImages();

        $cmsCollection = $this->cmsPageCollection;

        $cmsCollection->addFieldToFilter('identifier', 'page_test_tag1');

        $result = $this->dataMapper->mapPage($cmsCollection->getFirstItem());

        $this->assertArrayHasKey('headline', $result);
        $this->assertArrayHasKey('href', $result);
        $this->assertArrayHasKey('image', $result);
        $this->assertArrayHasKey('src', $result['image']);
        $this->assertEquals('Cms Test Tag Page1', $result['headline']);
        $this->assertEquals('http://localhost/index.php/page_test_tag1', $result['href']);

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->$assertContains('image1.jpg', $result['image']['src']);
        $this->$assertContains('image1.jpg', $result['image']['srcSet']);
        $this->$assertContains('image1.jpg 2x', $result['image']['srcSet']);
    }

    public static function loadPages()
    {
        include __DIR__ . '/../../../_files/pages.php';
    }

    protected function prepareImages()
    {
        if (!file_exists(BP . '/pub/media/cmsteaser')) {
            mkdir(BP . '/pub/media/cmsteaser');
        }
        copy(__DIR__ . '/../../../_files/image1.jpg', BP . '/pub/media/cmsteaser/image1.jpg');
        copy(__DIR__ . '/../../../_files/image2.jpg', BP . '/pub/media/cmsteaser/image2.jpg');
    }
}
