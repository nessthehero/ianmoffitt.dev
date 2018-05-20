<?php

/* themes/nth/templates/page/page.html.twig */
class __TwigTemplate_061c7e44424579f8d07d5d17d28b5f3033bbe4307f62b07c156769a56c641d28 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $tags = array();
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('Twig_Extension_Sandbox')->checkSecurity(
                array(),
                array(),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 48
        echo "
<div class=\"off-canvas-wrapper\">
\t<div class=\"off-canvas-wrapper-inner\" data-off-canvas-wrapper>

\t\t";
        // line 52
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "header", array()), "html", null, true));
        echo "

\t\t<div class=\"off-canvas-content\" data-off-canvas-content>

\t\t\t<div id=\"base\" class=\"grid-base\"></div>

\t\t\t<div class=\"title-bar hide-for-large\">
\t\t\t\t<div class=\"title-bar-left\">
\t\t\t\t\t<button class=\"menu-icon\" type=\"button\" data-open=\"global-header\"></button>
\t\t\t\t\t<span class=\"title-bar-title\">Ian Moffitt</span>
\t\t\t\t</div>
\t\t\t</div>

\t\t\t<main id=\"main\" class=\"main level\">

\t\t\t\t<header class=\"article__header\">

\t\t\t\t\t";
        // line 69
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "title", array()), "html", null, true));
        echo "

\t\t\t\t</header>

\t\t\t\t";
        // line 73
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "content", array()), "html", null, true));
        echo "

\t\t\t</main>

\t\t\t";
        // line 77
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "footer", array()), "html", null, true));
        echo "

\t\t</div>

\t</div>
</div>

";
    }

    public function getTemplateName()
    {
        return "themes/nth/templates/page/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  83 => 77,  76 => 73,  69 => 69,  49 => 52,  43 => 48,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "themes/nth/templates/page/page.html.twig", "/var/www/v2.ianmoffitt.co/web/themes/nth/templates/page/page.html.twig");
    }
}
