<?php

/* themes/nth/templates/page/page--work.html.twig */
class __TwigTemplate_2918e0592d46eeb14b6c97ada08a2455ab24fff554ce020d00a87326ed5031a5 extends Twig_Template
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
        $tags = array("if" => 67);
        $filters = array("raw" => 80);
        $functions = array();

        try {
            $this->env->getExtension('Twig_Extension_Sandbox')->checkSecurity(
                array('if'),
                array('raw'),
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

\t\t\t<main id=\"main\" class=\"main level row\">

\t\t\t\t";
        // line 67
        if (($context["thumbnail"] ?? null)) {
            // line 68
            echo "\t\t\t\t\t<figure class=\"column masthead\">
\t\t\t\t\t\t<div class=\"image\" style=\"background-image: url('";
            // line 69
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["masthead"] ?? null), "html", null, true));
            echo "');\" role=\"img\" aria-label=\"";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["alt"] ?? null), "html", null, true));
            echo "\"></div>
\t\t\t\t\t</figure>
\t\t\t\t";
        }
        // line 72
        echo "
\t\t\t\t<article class=\"article column\">

\t\t\t\t\t<header class=\"article__header\">

\t\t\t\t\t\t";
        // line 77
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "title", array()), "html", null, true));
        echo "

\t\t\t\t\t\t";
        // line 79
        if (($context["summary"] ?? null)) {
            // line 80
            echo "\t\t\t\t\t\t\t<p class=\"article__introduction\">";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["summary"] ?? null), "html", null, true));
            echo " ";
            if (($context["url"] ?? null)) {
                echo "&mdash; ";
                echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(($context["url"] ?? null)));
            }
            echo "</p>
\t\t\t\t\t\t";
        }
        // line 82
        echo "
\t\t\t\t\t\t";
        // line 83
        if ( !($context["is_active"] ?? null)) {
            // line 84
            echo "\t\t\t\t\t\t\t<p><em>This project is no longer live or was changed.</em></p>
\t\t\t\t\t\t";
        }
        // line 86
        echo "
\t\t\t\t\t\t<div class=\"article__dates\">
\t\t\t\t\t\t\t<time datetime=\"20170911\"><strong>Project Start:</strong> 9-11-2017</time>
\t\t\t\t\t\t\t<time datetime=\"20180307\"><strong>Project Launch:</strong> 3-7-2018</time>
\t\t\t\t\t\t</div>

\t\t\t\t\t</header>

\t\t\t\t\t";
        // line 94
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "content", array()), "html", null, true));
        echo "

\t\t\t\t</article>

\t\t\t</main>

\t\t\t";
        // line 100
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "footer", array()), "html", null, true));
        echo "

\t\t</div>

\t</div>
</div>

";
    }

    public function getTemplateName()
    {
        return "themes/nth/templates/page/page--work.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 100,  124 => 94,  114 => 86,  110 => 84,  108 => 83,  105 => 82,  94 => 80,  92 => 79,  87 => 77,  80 => 72,  72 => 69,  69 => 68,  67 => 67,  49 => 52,  43 => 48,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "themes/nth/templates/page/page--work.html.twig", "/var/www/v2.ianmoffitt.co/web/themes/nth/templates/page/page--work.html.twig");
    }
}
