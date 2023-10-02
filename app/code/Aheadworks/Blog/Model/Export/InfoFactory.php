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
namespace Aheadworks\Blog\Model\Export;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\LocalizedException as LocalizedExceptionAlias;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\ImportExport\Model\Export\AbstractEntity;
use Magento\ImportExport\Model\Export\Adapter\AbstractAdapter as AbstractAdapterAlias;
use Magento\ImportExport\Model\Export\Adapter\Factory as AdapterFactory;
use Magento\ImportExport\Model\Export\ConfigInterface;
use Magento\ImportExport\Model\Export\Entity\Factory as EntityFactory;
use Psr\Log\LoggerInterface;

class InfoFactory
{
    /**
     * @param ObjectManagerInterface $objectManager
     * @param ConfigInterface $exportConfig
     * @param EntityFactory $entityFactory
     * @param AdapterFactory $exportAdapterFac
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly ConfigInterface $exportConfig,
        private readonly EntityFactory $entityFactory,
        private readonly AdapterFactory $exportAdapterFac,
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger,
        private readonly ConfigProvider $configProvider
    ) {
    }

    /**
     * Create ExportInfo object.
     *
     * @param string $fileFormat
     * @param string $entity
     * @param array $exportFilter
     * @param array $skipAttr
     * @param string|null $locale
     * @return ExportInfoInterface
     * @throws LocalizedException
     */
    public function create(
        string $fileFormat,
        string $entity,
        array $exportFilter,
        array $skipAttr,
        ?string $locale = null
    ) {
        $this->exportConfig->merge($this->configProvider->getExportConfig());

        $writer = $this->getWriter($fileFormat);
        $entityAdapter = $this->getEntityAdapter(
            $entity,
            $fileFormat,
            $exportFilter,
            $skipAttr,
            $writer->getContentType()
        );
        $fileName = $this->generateFileName($entity, $entityAdapter, $writer->getFileExtension());
        /** @var ExportInfoInterface $exportInfo */
        $exportInfo = $this->objectManager->create(ExportInfoInterface::class);
        $exportInfo->setExportFilter($this->serializer->serialize($exportFilter));
        $exportInfo->setSkipAttr($skipAttr);
        $exportInfo->setFileName($fileName);
        $exportInfo->setEntity($entity);
        $exportInfo->setFileFormat($fileFormat);
        $exportInfo->setContentType($writer->getContentType());
        if ($locale) {
            $exportInfo->setLocale($locale);
        }

        return $exportInfo;
    }

    /**
     * Generate file name
     *
     * @param string $entity
     * @param AbstractEntity $entityAdapter
     * @param string $fileExtensions
     * @return string
     */
    private function generateFileName(string $entity, AbstractEntity $entityAdapter, string $fileExtensions): string
    {
        $fileName = null;
        if ($entityAdapter instanceof AbstractEntity) {
            $fileName = $entityAdapter->getFileName();
        }
        if (!$fileName) {
            $fileName = $entity;
        }

        return $fileName . '_' . date('Ymd_His') . '.' . $fileExtensions;
    }

    /**
     * Create instance of entity adapter and return it.
     *
     * @param string $entity
     * @param string $fileFormat
     * @param array $exportFilter
     * @param array $skipAttr
     * @param string $contentType
     * @return \Magento\ImportExport\Model\Export\AbstractEntity|AbstractEntity
     * @throws LocalizedExceptionAlias
     */
    private function getEntityAdapter(string $entity, string $fileFormat, array $exportFilter, array $skipAttr, string $contentType)
    {
        $entities = $this->exportConfig->getEntities();
        if (isset($entities[$entity])) {
            try {
                $entityAdapter = $this->entityFactory->create($entities[$entity]['model']);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedExceptionAlias(
                    __('Please enter a correct entity model.')
                );
            }
            if (!$entityAdapter instanceof \Magento\ImportExport\Model\Export\Entity\AbstractEntity &&
                !$entityAdapter instanceof \Magento\ImportExport\Model\Export\AbstractEntity
            ) {
                throw new LocalizedExceptionAlias(
                    __(
                        'The entity adapter object must be an instance of %1 or %2.',
                        \Magento\ImportExport\Model\Export\Entity\AbstractEntity::class,
                        \Magento\ImportExport\Model\Export\AbstractEntity::class
                    )
                );
            }
            // check for entity codes integrity
            if ($entity != $entityAdapter->getEntityTypeCode()) {
                throw new LocalizedExceptionAlias(
                    __('The input entity code is not equal to entity adapter code.')
                );
            }
        } else {
            throw new LocalizedExceptionAlias(__('Please enter a correct entity.'));
        }
        $entityAdapter->setParameters(
            [
                'fileFormat' => $fileFormat,
                'entity' => $entity,
                'exportFilter' => $exportFilter,
                'skipAttr' => $skipAttr,
                'contentType' => $contentType,
            ]
        );

        return $entityAdapter;
    }

    /**
     * Returns writer for a file format
     *
     * @param string $fileFormat
     * @return AbstractAdapterAlias
     * @throws LocalizedExceptionAlias
     */
    private function getWriter(string $fileFormat): AbstractAdapterAlias
    {
        $fileFormats = $this->exportConfig->getFileFormats();
        if (isset($fileFormats[$fileFormat])) {
            try {
                $writer = $this->exportAdapterFac->create($fileFormats[$fileFormat]['model']);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedExceptionAlias(
                    __('Please enter a correct entity model.')
                );
            }
            if (!$writer instanceof AbstractAdapterAlias) {
                throw new LocalizedExceptionAlias(
                    __(
                        'The adapter object must be an instance of %1.',
                        AbstractAdapterAlias::class
                    )
                );
            }
        } else {
            throw new LocalizedExceptionAlias(__('Please correct the file format.'));
        }

        return $writer;
    }
}
