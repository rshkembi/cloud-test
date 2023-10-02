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

namespace Aheadworks\Blog\Model\Disqus;

use Aheadworks\Blog\Model\DisqusConfig;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\HTTP\Adapter\CurlFactory;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\Serialize\Serializer\Json;

class Api
{
    /**
     * Api resources
     */
    public const RES_FORUMS_LIST_THREADS = 'forums/listThreads';
    public const RES_POSTS_LIST = 'posts/list';
    public const RES_THREADS_DETAILS = 'threads/details';

    /**
     * Thread statuses
     */
    public const THREAD_STATUS_OPEN = 'open';
    public const THREAD_STATUS_CLOSED = 'closed';
    public const THREAD_STATUS_KILLED = 'killed';

    /**
     * Post statuses
     */
    public const POST_STATUS_UNAPPROVED = 'unapproved';
    public const POST_STATUS_APPROVED = 'approved';
    public const POST_STATUS_SPAM = 'spam';
    public const POST_STATUS_DELETED = 'deleted';
    public const POST_STATUS_FLAGGED = 'flagged';
    public const POST_STATUS_HIGHLIGHTED = 'highlighted';

    /**
     * Response relations
     */
    public const RELATION_FORUM = 'forum';
    public const RELATION_THREAD = 'thread';
    public const RELATION_AUTHOR = 'author';

    /**
     * API version
     */
    public const VERSION = '3.0';

    /**
     * Request method
     */
    public const METHOD = Request::HTTP_METHOD_GET;

    /**
     * Output type
     */
    public const OUTPUT_TYPE = 'json';

    /**
     * @param CurlFactory $curlFactory
     * @param DisqusConfig $disqusConfig
     * @param Json $serializer
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        private readonly CurlFactory $curlFactory,
        private readonly DisqusConfig $disqusConfig,
        private readonly Json $serializer,
        private readonly ProductMetadataInterface $productMetadata
    ) {
    }

    /**
     * Send request
     *
     * @param string $resource
     * @param array $args
     * @return array|bool
     */
    public function sendRequest($resource, $args = [])
    {
        /** @var \Magento\Framework\HTTP\Adapter\Curl $curl */
        $curl = $this->curlFactory->create();
        $magentoVersion = $this->productMetadata->getVersion();
        version_compare($magentoVersion, '2.4.6', '>=')
            ? $curl->setOptions(['timeout' => 60, 'header' => false])
            : $curl->setConfig(['timeout' => 60, 'header' => false]);
        $curl->write(self::METHOD, $this->getEndpoint($resource, $args));
        try {
            $response = $this->serializer->unserialize($curl->read());
            $response = $response['code'] != 0 ? false : $response['response'];
        } catch (\Exception $e) {
            $response = false;
        }
        $curl->close();
        return $response;
    }

    /**
     * Get prepared endpoint url
     *
     * @param string $resource
     * @param array $args
     * @return string
     */
    protected function getEndpoint($resource, $args = [])
    {
        $endpoint = 'https://disqus.com/api/' . self::VERSION . '/' .
            $resource . '.' . self::OUTPUT_TYPE;
        $rawParams = array_merge(
            ['api_secret' => $this->disqusConfig->getSecretKey(null)],
            $args
        ); // todo: store ID

        $params = [];
        foreach ($rawParams as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $params[] = $key . '[]=' . urlencode($item);
                }
            } else {
                $params[] = $key . '=' . urlencode($value ?? '');
            }
        }
        $endpoint .= '?' . implode('&', $params);

        return $endpoint;
    }
}
