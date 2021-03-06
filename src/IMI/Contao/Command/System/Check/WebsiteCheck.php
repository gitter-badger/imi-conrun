<?php

namespace IMI\Contao\Command\System\Check;

interface WebsiteCheck
{
    /**
     * @param ResultCollection $results
     */
    public function check(ResultCollection $results, \Mage_Core_Model_Website $website);
}