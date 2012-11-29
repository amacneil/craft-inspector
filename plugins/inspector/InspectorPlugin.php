<?php

namespace Blocks;

class InspectorPlugin extends BasePlugin
{
    public function getName()
    {
        return Blocks::t('Inspector');
    }

    public function getVersion()
    {
        return '0.1';
    }

    public function getDeveloper()
    {
        return 'Adrian Macneil';
    }

    public function getDeveloperUrl()
    {
        return 'http://adrianmacneil.com';
    }

    public function hookAddTwigExtension()
    {
        Blocks::import('plugins.inspector.twigextensions.InspectorTwigExtension');

        return new InspectorTwigExtension();
    }
}
