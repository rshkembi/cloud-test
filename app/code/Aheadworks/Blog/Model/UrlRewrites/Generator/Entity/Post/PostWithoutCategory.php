<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Blog
 * @version    2.17.1
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Blog\Model\UrlRewrites\Generator\Entity\Post;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\UrlRewrite\EntityType as UrlRewriteEntityType;
use Aheadworks\Blog\Model\UrlRewrites\Generator\AbstractGenerator;
use Aheadworks\Blog\Model\UrlRewrites\PathBuilder;
use Aheadworks\Blog\Model\UrlRewrites\RoutePathBuilder;
use Aheadworks\Blog\Model\StoreSetOperations;
use Aheadworks\Blog\Model\UrlRewrites\UrlConfigMetadata\StoreUrlConfigMetadataFactory;
use Aheadworks\Blog\Model\UrlRewrites\UrlConfigMetadata\UrlConfigMetadata as UrlConfigMetadataModel;
use Magento\UrlRewrite\Model\MergeDataProvider;
use Magento\UrlRewrite\Model\MergeDataProviderFactory;
use Magento\UrlRewrite\Model\OptionProvider as UrlRewriteOptionProvider;
use Magento\UrlRewrite\Model\StorageInterface as RewriteStorageInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;

/**
 * Class PostWithoutCategory
 * @package Aheadworks\Blog\Model\UrlRewrites\Generator\Entity\Post
 */
class PostWithoutCategory extends AbstractGenerator
{
    /**
     * @var UrlRewriteFactory
     */
    private $urlRewriteFactory;

    /**
     * @var PathBuilder
     */
    private $pathBuilder;

    /**
     * @var RoutePathBuilder
     */
    private $routePathBuilder;

    /**
     * @var StoreUrlConfigMetadataFactory
     */
    private $storeUrlConfigMetadataFactory;

    /**
     * @var StoreSetOperations
     */
    private $storeSetOperations;

    /**
     * @var RewriteStorageInterface
     */
    private $rewriteStorage;

    /**
     * PostWithoutCategory constructor.
     * @param MergeDataProviderFactory $mergeDataProviderFactory
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param PathBuilder $pathBuilder
     * @param StoreUrlConfigMetadataFactory $storeUrlConfigMetadataFactory
     * @param Config $config
     * @param RewriteStorageInterface $rewriteStorage
     * @param StoreSetOperations $storeSetOperations
     * @param RoutePathBuilder $routePathBuilder
     * @param array $subordinateEntitiesGenerators
     */
    public function __construct(
        MergeDataProviderFactory $mergeDataProviderFactory,
        UrlRewriteFactory $urlRewriteFactory,
        PathBuilder $pathBuilder,
        StoreUrlConfigMetadataFactory $storeUrlConfigMetadataFactory,
        Config $config,
        RewriteStorageInterface $rewriteStorage,
        StoreSetOperations $storeSetOperations,
        RoutePathBuilder $routePathBuilder,
        $subordinateEntitiesGenerators = []
    ) {
        parent::__construct($mergeDataProviderFactory, $config, $subordinateEntitiesGenerators);

        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->pathBuilder = $pathBuilder;
        $this->storeUrlConfigMetadataFactory = $storeUrlConfigMetadataFactory;
        $this->storeSetOperations = $storeSetOperations;
        $this->routePathBuilder = $routePathBuilder;
        $this->rewriteStorage = $rewriteStorage;
    }

    /**
     * @inheritdoc
     * @param PostInterface $newEntityState
     * @param PostInterface $oldEntityState
     */
    protected function getPermanentRedirects($storeId, $newEntityState, $oldEntityState)
    {
        /** @var UrlConfigMetadataModel $urlConfigMetadata */
        $urlConfigMetadata = $this->storeUrlConfigMetadataFactory->create($storeId);
        $permanentRedirectRequestPath = $this->pathBuilder->buildPostPath($urlConfigMetadata, $oldEntityState);
        $permanentRedirectTargetPath = $this->pathBuilder->buildPostPath($urlConfigMetadata, $newEntityState);

        $permanentRedirect = $this->urlRewriteFactory->create()
            ->setRequestPath($permanentRedirectRequestPath)
            ->setTargetPath($permanentRedirectTargetPath)
            ->setEntityType(UrlRewriteEntityType::TYPE_POST)
            ->setEntityId($newEntityState->getId())
            ->setStoreId($storeId)
            ->setRedirectType(UrlRewriteOptionProvider::PERMANENT);

        return [$permanentRedirect];
    }

    /**
     * @inheritdoc
     * @param PostInterface $newEntityState
     */
    protected function getControllerRewrites($storeId, $newEntityState)
    {
        /** @var UrlConfigMetadataModel $urlConfigMetadata */
        $urlConfigMetadata = $this->storeUrlConfigMetadataFactory->create($storeId);
        $controllerRewriteRequestPath = $this->pathBuilder->buildPostPath($urlConfigMetadata, $newEntityState);
        $controllerRewriteTargetPath = $this->routePathBuilder->buildPostPath($newEntityState);

        $controllerRewrite = $this->urlRewriteFactory->create()
            ->setRequestPath($controllerRewriteRequestPath)
            ->setTargetPath($controllerRewriteTargetPath)
            ->setEntityType(UrlRewriteEntityType::TYPE_POST)
            ->setEntityId($newEntityState->getId())
            ->setStoreId($storeId);

        return [$controllerRewrite];
    }

    /**
     * @inheritdoc
     * @param PostInterface $newEntityState
     * @param PostInterface $oldEntityState
     */
    protected function getExistingRewrites($storeId, $newEntityState, $oldEntityState)
    {
        /** @var MergeDataProvider $mergeDataProvider */
        $mergeDataProvider = $this->mergeDataProviderFactory->create();

        /** @var UrlConfigMetadataModel $urlConfigMetadata */
        $urlConfigMetadata = $this->storeUrlConfigMetadataFactory->create($storeId);
        $permanentRedirectRequestPath = $this->pathBuilder->buildPostPath($urlConfigMetadata, $oldEntityState);
        $permanentRedirectTargetPath = $this->pathBuilder->buildPostPath($urlConfigMetadata, $newEntityState);

        $existingPermanentRedirects = $this->rewriteStorage->findAllByData([
            UrlRewrite::REDIRECT_TYPE => UrlRewriteOptionProvider::PERMANENT,
            UrlRewrite::ENTITY_TYPE => UrlRewriteEntityType::TYPE_POST,
            UrlRewrite::ENTITY_ID => $newEntityState->getId(),
            UrlRewrite::STORE_ID => $storeId
        ]);

        /** @var UrlRewrite $existingRewrite */
        foreach ($existingPermanentRedirects as $existingRedirect) {
            $isOutdatedPermanentRedirect = $existingRedirect->getTargetPath() == $permanentRedirectRequestPath;

            if ($this->config->getSaveRewritesHistory($storeId) && $isOutdatedPermanentRedirect) {
                $existingRedirect->setTargetPath($permanentRedirectTargetPath);
                $mergeDataProvider->merge([$existingRedirect]);
            }
        }

        return $mergeDataProvider->getData();
    }

    /**
     * @inheritdoc
     * @param PostInterface $newEntityState
     * @param PostInterface $oldEntityState
     */
    protected function isNeedGeneratePermanentRedirects($storeId, $newEntityState, $oldEntityState)
    {
        $oldAndNewStoresIntersection = $this->storeSetOperations->getIntersection(
            $newEntityState->getStoreIds(),
            $oldEntityState->getStoreIds()
        );
        $isStoreWasAndRemained = in_array($storeId, $oldAndNewStoresIntersection);
        $isUrlKeyChanged = $oldEntityState->getUrlKey() !== $newEntityState->getUrlKey();

        return $isStoreWasAndRemained && $isUrlKeyChanged;
    }

    /**
     * @inheritdoc
     * @param PostInterface $newEntityState
     * @param PostInterface|null $oldEntityState
     */
    protected function isNeedGenerateControllerRewrites($storeId, $newEntityState, $oldEntityState)
    {
        $isStoresChanged = false;
        $isUrlKeyChanged = false;
        $categoriesChanged = false;

        if ($oldEntityState !== null) {
            if (!$this->storeSetOperations->isEqual($newEntityState->getStoreIds(), $oldEntityState->getStoreIds())) {
                $isStoresChanged = true;
            }
            if ($newEntityState->getUrlKey() !== $oldEntityState->getUrlKey()) {
                $isUrlKeyChanged = true;
            }

            $newCategories = $newEntityState->getCategoryIds();
            $oldCategories = $oldEntityState->getCategoryIds();
            $categoriesChanged = count($oldCategories) != count($newCategories)
                || array_diff($oldCategories, $newCategories) !== array_diff($newCategories, $oldCategories);
        }

        return $oldEntityState == null || $isStoresChanged || $isUrlKeyChanged || $categoriesChanged;
    }
}
