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

namespace Aheadworks\Blog\Model\System\Config\Value;

class Parser
{
    /**
     * Retrieve parsed value of multiselect config field
     *
     * @param mixed $multiselectConfigValue
     * @return array
     */
    public function getParsedMultiselectConfigValue($multiselectConfigValue): array
    {
        return
            (empty($multiselectConfigValue) && ((string)$multiselectConfigValue === ''))
                ? []
                : explode(',', (string) $multiselectConfigValue);
    }
}
