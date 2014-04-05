<?php
namespace Craft;

class ComboboxPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Combobox');
    }

    public function getVersion()
    {
        return '0.9.1';
    }

    public function getDeveloper()
    {
        return 'Mario Friz';
    }

    public function getDeveloperUrl()
    {
        return 'http://builtbysplash.com';
    }
}
