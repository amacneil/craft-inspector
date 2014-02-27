<?php

namespace Craft;

class InspectorPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Inspector');
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'Adrian Macneil';
    }

    public function getDeveloperUrl()
    {
        return 'http://adrianmacneil.com';
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.inspector.twigextensions.InspectorTwigExtension');

        return new InspectorTwigExtension();
    }
}
