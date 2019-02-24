<?php

/* base.html.twig */
class __TwigTemplate_dd90a500d6cac82060747e887f4cc16d748939252d692bd4a16e62e2c61a1df9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'links' => array($this, 'block_links'),
            'bodyClass' => array($this, 'block_bodyClass'),
            'body' => array($this, 'block_body'),
            'scripts' => array($this, 'block_scripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\"/>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=2.0 user-scalable=yes\">
    <title>";
        // line 6
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
    ";
        // line 7
        $this->displayBlock('links', $context, $blocks);
        // line 8
        echo "    <link rel=\"icon\" type=\"image/x-icon\" href=\"/favicon.ico\"/>
</head>
<body class=\"";
        // line 10
        $this->displayBlock('bodyClass', $context, $blocks);
        echo "\">
    ";
        // line 11
        $this->displayBlock('body', $context, $blocks);
        // line 12
        echo "    ";
        $this->displayBlock('scripts', $context, $blocks);
        // line 13
        echo "</body>
</html>
";
    }

    // line 6
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["page"] ?? null), "title", array()), "html", null, true);
    }

    // line 7
    public function block_links($context, array $blocks = array())
    {
    }

    // line 10
    public function block_bodyClass($context, array $blocks = array())
    {
    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
    }

    // line 12
    public function block_scripts($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "base.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  77 => 12,  72 => 11,  67 => 10,  62 => 7,  56 => 6,  50 => 13,  47 => 12,  45 => 11,  41 => 10,  37 => 8,  35 => 7,  31 => 6,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "base.html.twig", "/var/www/html/src/Views/base.html.twig");
    }
}
