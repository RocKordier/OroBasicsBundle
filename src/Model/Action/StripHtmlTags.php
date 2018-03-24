<?php
namespace EHDev\BasicsBundle\Model\Action;

use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\UIBundle\Tools\HtmlTagHelper;
use Oro\Component\ConfigExpression\ContextAccessor;

class StripHtmlTags extends \Oro\Bundle\EmailBundle\Model\Action\StripHtmlTags
{
    private $configManager;

    public function __construct(ContextAccessor $contextAccessor, HtmlTagHelper $htmlTagHelper, ConfigManager $configManager)
    {
        parent::__construct($contextAccessor, $htmlTagHelper);
        $this->configManager = $configManager;
    }

    protected function executeAction($context)
    {
        $result = $this->htmlTagHelper->purify($this->contextAccessor->getValue($context, $this->html));
        $result = $this->htmlTagHelper->stripTags($result, $this->configManager->get('ehdev_basics.allow_html_tags_mail'));

        $this->contextAccessor->setValue($context, $this->attribute, $result);
    }
}
