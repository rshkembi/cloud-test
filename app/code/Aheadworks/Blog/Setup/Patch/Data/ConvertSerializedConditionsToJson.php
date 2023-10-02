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
declare(strict_types=1);

namespace Aheadworks\Blog\Setup\Patch\Data;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Serialize\Factory as SerializeFactory;
use Magento\Framework\Serialize\Serializer\Serialize;
use Magento\Framework\Serialize\Serializer\SerializeFactory as PhpSerializeFactory;
use Aheadworks\Blog\Model\Serialize\SerializeInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class ConvertSerializedConditionsToJson implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var SerializeInterface
     */
    private $serializer;

    /**
     * @var Serialize
     */
    private $phpSerializer;

    /**
     * ConvertSerializedConditionsToJson constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param PhpSerializeFactory $phpSerializerFactory
     * @param SerializeFactory $serializeFactory
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        PhpSerializeFactory $phpSerializerFactory,
        SerializeFactory $serializeFactory
    ) {
        $this->serializer = $serializeFactory->create();
        $this->phpSerializer = $phpSerializerFactory->create();
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Convert serialized conditions to json
     *
     * @return $this
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $connection = $this->moduleDataSetup->getConnection();
        $table = $this->moduleDataSetup->getTable('aw_blog_post');
        $select = $connection->select()->from(
            $table,
            [
                PostInterface::ID,
                PostInterface::PRODUCT_CONDITION
            ]
        );
        $rulesConditions = $connection->fetchAssoc($select);
        foreach ($rulesConditions as $ruleConditions) {
            $unserializeCond = $this->unserialize($ruleConditions[PostInterface::PRODUCT_CONDITION]);
            if ($unserializeCond !== false) {
                $ruleConditions[PostInterface::PRODUCT_CONDITION] = empty($unserializeCond)
                    ? ''
                    : $this->serializer->serialize($unserializeCond);

                $connection->update(
                    $table,
                    [
                        PostInterface::PRODUCT_CONDITION => $ruleConditions[PostInterface::PRODUCT_CONDITION]
                    ],
                    PostInterface::ID . ' = ' . $ruleConditions[PostInterface::ID]
                );
            }
        }
        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }

    /**
     * Unserialize string
     *
     * @param string $string
     * @return array|bool
     */
    private function unserialize($string)
    {
        $result = '';

        if ($string === 'b:0;') {
            return false;
        }
        if (!empty($string)) {
            try {
                $result = $this->phpSerializer->unserialize($string);
            } catch (\InvalidArgumentException $e) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public static function getVersion()
    {
        return '2.4.6';
    }
}
