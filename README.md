# Inspector Plugin for Craft CMS

This plugin adds a helpful `inspect` method to your Craft templates. This will help you
see in the dark - figure out what kind of variables you are dealing with, and what methods
they implement.

## Installation

1. Upload this directory to `craft/plugins/inspector/` on your server.
2. Enable the plugin under *Craft Admin > Settings > Plugins*

## Usage

You can use inspector either as a function or filter:

    {{ craft.request | inspect }}
    {{ inspect(craft.request) }}

Either way, it will return the same output:

    Craft\HttpRequestVariable
    -------------------------

    Attributes:

    Methods:
        getFirstSegment
        getLastSegment
        getParam
        getPost
        getQuery
        getSegment
        getSegments
        getServerName
        getUri
        getUrl
        getUrlFormat
        isSecure

Aha! We can see this variable has some useful methods on it. Let's dig deeper:

    {{ craft.request.getSegments | inspect }}

This returns:

    Array: ["blog","2012","11","my-post-title"]

Now we're getting somewhere! No more digging around in the documentation trying to figure out
what's available to you.

The output is wrapped in `<pre>` tags to format it nicely.

## Developers

Add support for Inspector to your plugins! Simply add a `getHelpText` method to your models,
and it will be displayed when people inspect your object. For example:

    class PizzaModel extends BaseModel
    {
        public function getHelpText()
        {
            return "This variable represents a pizza.";
        }
    }

When people inspect your object, they will now see:

    Craft\PizzaModel
    ----------------
    This variable represents a pizza.

    Attributes:

    Methods:
