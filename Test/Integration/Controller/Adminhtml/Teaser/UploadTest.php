<?php
namespace MageSuite\CmsTagManager\Test\Integration\Controller\Adminhtml\Teaser;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class UploadTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\Opengraph\Service\Processor\UploadImage
     */
    protected $uploadProcessor;

    /**
     * @var \MageSuite\CmsTagManager\Api\TagsRepositoryInterface
     */
    protected $tagsRepository;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    public function setUp(): void
    {
        parent::setUp();
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->uploadProcessor = $this->objectManager->create(\MageSuite\Opengraph\Service\Processor\UploadImage::class);

        $this->tagsRepository = $this->objectManager->create(\MageSuite\CmsTagManager\Api\TagsRepositoryInterface::class);

        $this->filesystem = $this->objectManager->create(\Magento\Framework\Filesystem::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture moveCmsImageToTmp
     */
    public function testItUploadFileCorrectly()
    {
        $_FILES = [ // phpcs:ignore
            'cms_image_teaser' => [
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__.'/../../../../_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864
            ]
        ];
        $fileParameters = new \Laminas\Stdlib\Parameters();
        $fileParameters->set('cms_image_teaser', $_FILES['cms_image_teaser']); // phpcs:ignore
        $this->getRequest()->setFiles($fileParameters);
        $this->dispatch('backend/cmstags/teaser/upload');

        $response = json_decode($this->getResponse()->getBody(), true);
        $this->assertTrue(isset($response['name']));

        $this->assertTrue(isset($response['name']));
        $path = $this->filesystem
                ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
                ->getAbsolutePath() . \MageSuite\CmsTagManager\Model\ImageTeaser::CMS_IMAGE_TEASER_PATH . $response['name'];
        $fileExist = file_exists($path);
        $this->assertTrue($fileExist);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture moveCmsImageToTmp
     */
    public function testUploadWithWrongData()
    {
        $_FILES = [ // phpcs:ignore
            'brand_icon' => [
                'name' => 'magento_image.jpg',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__.'/../../../d/_files/tmp/magento_image.jpg',
                'error' => 0,
                'size' => 13864
            ]
        ];

        $this->dispatch('backend/cmstags/teaser/upload');

        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertTrue(isset($response['error']));
    }

    public static function moveCmsImageToTmp()
    {
        include __DIR__.'/../../../../_files/cms_image.php';
    }
}
