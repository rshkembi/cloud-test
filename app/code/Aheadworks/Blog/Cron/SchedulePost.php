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
namespace Aheadworks\Blog\Cron;

use Aheadworks\Blog\Model\Flag;
use Aheadworks\Blog\Model\FlagFactory;
use Aheadworks\Blog\Model\Post\Collector as PostsCollector;
use Aheadworks\Blog\Model\Post\Publisher as PostsPublisher;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class SchedulePost
 */
class SchedulePost extends CronAbstract
{
    /**
     * @var PostsPublisher
     */
    private $postsPublisher;

    /**
     * @var PostsCollector
     */
    private $postsCollector;

    /**
     * @param DateTime $dateTime
     * @param FlagFactory $flagFactory
     * @param PostsCollector $postsCollector
     * @param PostsPublisher $postsPublisher
     */
   public function __construct(
       DateTime $dateTime,
       FlagFactory $flagFactory,
       PostsCollector $postsCollector,
       PostsPublisher $postsPublisher
   ) {
       parent::__construct($dateTime, $flagFactory);

       $this->postsCollector = $postsCollector;
       $this->postsPublisher = $postsPublisher;
   }

    /**
     * {@inheritdoc}
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        if ($this->isLocked(Flag::AW_BLOG_SCHEDULE_POST_LAST_EXEC_TIME)) {
            return $this;
        }

        $this->postsPublisher->publishPosts(
            $this->postsCollector->collectScheduledPosts()
        );

        $this->setFlagData(Flag::AW_BLOG_SCHEDULE_POST_LAST_EXEC_TIME);
        return $this;
    }
}
