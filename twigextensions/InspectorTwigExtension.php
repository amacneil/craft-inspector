<?php

namespace Craft;

class InspectorTwigExtension extends \Twig_Extension
{
    protected $env;

    public function getName()
    {
        return 'inspector';
    }

    public function getFilters()
    {
        return array('inspect' => new \Twig_Filter_Method($this, 'inspect'));
    }

    public function getFunctions()
    {
        return array('inspect' => new \Twig_Function_Method($this, 'inspect'));
    }

    /**
     * Initializes the runtime environment.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     */
    public function initRuntime(\Twig_Environment $env)
    {
        $this->env = $env;
    }

    /**
     * Display an object as a helpful string representation
     *
     * @param mixed $var
     */
    public function inspect($var)
    {
        if (is_null($var)) {
            $out = 'null';
        } elseif (is_array($var)) {
            $out = 'Array: '.$this->inspectArray($var);
        } elseif (is_object($var)) {
            $out = get_class($var);
            $out .= "\n".str_repeat('-', strlen($out));

            if (method_exists($var, 'getHelpText')) {
                $out .= "\n".$var->getHelpText();
            }

            $out .= $this->inspectAttributes($var);
            $out .= $this->inspectMethods($var);
        } else {
            $out = ucfirst(gettype($var)).': '.print_r($var, true);
        }

        return new \Twig_Markup('<pre><code>'.$out.'</code></pre>', $this->env->getCharset());
    }

    protected function inspectAttributes($var)
    {
        $attributes = array();

        if (method_exists($var, 'getAttributes')) {
            $attributes = $var->getAttributes();
        }

        $reflector = new \ReflectionClass($var);
        foreach ($reflector->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $attributes[$property->name] = $property->getValue($var);
        }

        ksort($attributes);
        $out = "\n\nAttributes: ";
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $value = $this->inspectArray($value);
            }

            $out .= sprintf("\n    %-20s ", $key).sprintf("%s", $value);
        }

        return $out;
    }

    protected function inspectMethods($var)
    {
        $reflector = new \ReflectionClass($var);
        $methods = array();

        foreach ($reflector->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ('_' !== substr($method->name, 0, 1)) {
                $methods[] = "\n    ".$method->name;
            }
        }

        if ($methods) {
            sort($methods);

            return "\n\nMethods: ".implode('', $methods);
        }
    }

    protected function inspectArray($var)
    {
        // convert objects to strings
        foreach ($var as $key => $value) {
            if (is_object($value)) {
                $var[$key] = get_class($value);
                if (method_exists($value, '__toString')) {
                    $var[$key] .= sprintf(': %s', $value);
                }
            }
        }

        return json_encode($var);
    }
}
