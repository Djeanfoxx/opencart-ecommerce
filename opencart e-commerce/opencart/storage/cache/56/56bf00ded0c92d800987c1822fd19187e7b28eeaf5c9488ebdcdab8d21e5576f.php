<?php

/* design/translation_list.twig */
class __TwigTemplate_1468e24957f89f2f2fb6190a4e91c437c4bebe97026e1a2590c5094b13486d2c extends Twig_Template
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
        // line 1
        echo (isset($context["header"]) ? $context["header"] : null);
        echo (isset($context["column_left"]) ? $context["column_left"] : null);
        echo "
<div id=\"content\">
  <div class=\"page-header\">
    <div class=\"container-fluid\">
      <div class=\"pull-right\"><a href=\"";
        // line 5
        echo (isset($context["add"]) ? $context["add"] : null);
        echo "\" data-toggle=\"tooltip\" title=\"";
        echo (isset($context["button_add"]) ? $context["button_add"] : null);
        echo "\" class=\"btn btn-primary\"><i class=\"fa fa-plus\"></i></a>
        <button type=\"button\" data-toggle=\"tooltip\" title=\"";
        // line 6
        echo (isset($context["button_delete"]) ? $context["button_delete"] : null);
        echo "\" class=\"btn btn-danger\" onclick=\"confirm('";
        echo (isset($context["text_confirm"]) ? $context["text_confirm"] : null);
        echo "') ? \$('#form-translation').submit() : false;\"><i class=\"fa fa-trash-o\"></i></button>
      </div>
      <h1>";
        // line 8
        echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
        echo "</h1>
      <ul class=\"breadcrumb\">
        ";
        // line 10
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["breadcrumbs"]) ? $context["breadcrumbs"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 11
            echo "        <li><a href=\"";
            echo $this->getAttribute($context["breadcrumb"], "href", array());
            echo "\">";
            echo $this->getAttribute($context["breadcrumb"], "text", array());
            echo "</a></li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['breadcrumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 13
        echo "      </ul>
    </div>
  </div>
  <div class=\"container-fluid\">";
        // line 16
        if ((isset($context["error_warning"]) ? $context["error_warning"] : null)) {
            // line 17
            echo "    <div class=\"alert alert-danger alert-dismissible\"><i class=\"fa fa-exclamation-circle\"></i> ";
            echo (isset($context["error_warning"]) ? $context["error_warning"] : null);
            echo "
      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    </div>
    ";
        }
        // line 21
        echo "    ";
        if ((isset($context["success"]) ? $context["success"] : null)) {
            // line 22
            echo "    <div class=\"alert alert-success alert-dismissible\"><i class=\"fa fa-check-circle\"></i> ";
            echo (isset($context["success"]) ? $context["success"] : null);
            echo "
      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    </div>
    ";
        }
        // line 26
        echo "    <div class=\"panel panel-default\">
      <div class=\"panel-heading\">
        <h3 class=\"panel-title\"><i class=\"fa fa-list\"></i> ";
        // line 28
        echo (isset($context["text_list"]) ? $context["text_list"] : null);
        echo "</h3>
      </div>
      <div class=\"panel-body\">
        <form action=\"";
        // line 31
        echo (isset($context["delete"]) ? $context["delete"] : null);
        echo "\" method=\"post\" enctype=\"multipart/form-data\" id=\"form-translation\">
          <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
              <thead>
                <tr>
                  <td style=\"width: 1px;\" class=\"text-center\"><input type=\"checkbox\" onclick=\"\$('input[name*=\\'selected\\']').prop('checked', this.checked);\" /></td>
                  <td class=\"text-left\">";
        // line 37
        if (((isset($context["sort"]) ? $context["sort"] : null) == "store")) {
            echo "<a href=\"";
            echo (isset($context["sort_store"]) ? $context["sort_store"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_store"]) ? $context["column_store"] : null);
            echo "</a> ";
        } else {
            echo "<a href=\"";
            echo (isset($context["sort_store"]) ? $context["sort_store"] : null);
            echo "\">";
            echo (isset($context["column_store"]) ? $context["column_store"] : null);
            echo "</a>";
        }
        echo "</td>
                  <td class=\"text-left\">";
        // line 38
        if (((isset($context["sort"]) ? $context["sort"] : null) == "language")) {
            echo "<a href=\"";
            echo (isset($context["sort_language"]) ? $context["sort_language"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_language"]) ? $context["column_language"] : null);
            echo "</a>";
        } else {
            echo "<a href=\"";
            echo (isset($context["sort_language"]) ? $context["sort_language"] : null);
            echo "\">";
            echo (isset($context["column_language"]) ? $context["column_language"] : null);
            echo "</a>";
        }
        echo "</td>
                  <td class=\"text-left\">";
        // line 39
        if (((isset($context["sort"]) ? $context["sort"] : null) == "route")) {
            echo "<a href=\"";
            echo (isset($context["sort_route"]) ? $context["sort_route"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_route"]) ? $context["column_route"] : null);
            echo "</a> ";
        } else {
            echo "<a href=\"";
            echo (isset($context["sort_route"]) ? $context["sort_route"] : null);
            echo "\">";
            echo (isset($context["column_route"]) ? $context["column_route"] : null);
            echo "</a>";
        }
        echo "</td>
                  <td class=\"text-left\">";
        // line 40
        if (((isset($context["sort"]) ? $context["sort"] : null) == "key")) {
            echo "<a href=\"";
            echo (isset($context["sort_key"]) ? $context["sort_key"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_key"]) ? $context["column_key"] : null);
            echo "</a>";
        } else {
            echo "<a href=\"";
            echo (isset($context["sort_key"]) ? $context["sort_key"] : null);
            echo "\">";
            echo (isset($context["column_key"]) ? $context["column_key"] : null);
            echo "</a>";
        }
        echo "</td>
                  <td class=\"text-left\">";
        // line 41
        if (((isset($context["sort"]) ? $context["sort"] : null) == "value")) {
            echo "<a href=\"";
            echo (isset($context["sort_value"]) ? $context["sort_value"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_value"]) ? $context["column_value"] : null);
            echo "</a>";
        } else {
            echo "<a href=\"";
            echo (isset($context["sort_value"]) ? $context["sort_value"] : null);
            echo "\">";
            echo (isset($context["column_value"]) ? $context["column_value"] : null);
            echo "</a>";
        }
        echo "</td>
                  <td class=\"text-right\">";
        // line 42
        echo (isset($context["column_action"]) ? $context["column_action"] : null);
        echo "</td>
                </tr>
              </thead>
              <tbody>
              ";
        // line 46
        if ((isset($context["translations"]) ? $context["translations"] : null)) {
            // line 47
            echo "              ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["translations"]) ? $context["translations"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["translation"]) {
                // line 48
                echo "              <tr>
                <td class=\"text-center\">";
                // line 49
                if (twig_in_filter($this->getAttribute($context["translation"], "translation_id", array()), (isset($context["selected"]) ? $context["selected"] : null))) {
                    // line 50
                    echo "                  <input type=\"checkbox\" name=\"selected[]\" value=\"";
                    echo $this->getAttribute($context["translation"], "translation_id", array());
                    echo "\" checked=\"checked\" />
                  ";
                } else {
                    // line 52
                    echo "                  <input type=\"checkbox\" name=\"selected[]\" value=\"";
                    echo $this->getAttribute($context["translation"], "translation_id", array());
                    echo "\" />
                  ";
                }
                // line 53
                echo "</td>
                <td class=\"text-left\">";
                // line 54
                echo $this->getAttribute($context["translation"], "store", array());
                echo "</td>
                <td class=\"text-left\">";
                // line 55
                echo $this->getAttribute($context["translation"], "language", array());
                echo "</td>
                <td class=\"text-left\">";
                // line 56
                echo $this->getAttribute($context["translation"], "route", array());
                echo "</td>
                <td class=\"text-left\">";
                // line 57
                echo $this->getAttribute($context["translation"], "key", array());
                echo "</td>
                <td class=\"text-left\">";
                // line 58
                echo $this->getAttribute($context["translation"], "value", array());
                echo "</td>
                <td class=\"text-right\"><a href=\"";
                // line 59
                echo $this->getAttribute($context["translation"], "edit", array());
                echo "\" data-toggle=\"tooltip\" title=\"";
                echo (isset($context["button_edit"]) ? $context["button_edit"] : null);
                echo "\" class=\"btn btn-primary\"><i class=\"fa fa-pencil\"></i></a></td>
              </tr>
              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['translation'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 62
            echo "              ";
        } else {
            // line 63
            echo "              <tr>
                <td class=\"text-center\" colspan=\"7\">";
            // line 64
            echo (isset($context["text_no_results"]) ? $context["text_no_results"] : null);
            echo "</td>
              </tr>
              ";
        }
        // line 67
        echo "              </tbody>
            </table>
          </div>
        </form>
        <div class=\"row\">
          <div class=\"col-sm-6 text-left\">";
        // line 72
        echo (isset($context["pagination"]) ? $context["pagination"] : null);
        echo "</div>
          <div class=\"col-sm-6 text-right\">";
        // line 73
        echo (isset($context["results"]) ? $context["results"] : null);
        echo "</div>
        </div>
      </div>
    </div>
  </div>
</div>
";
        // line 79
        echo (isset($context["footer"]) ? $context["footer"] : null);
        echo " 
";
    }

    public function getTemplateName()
    {
        return "design/translation_list.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  292 => 79,  283 => 73,  279 => 72,  272 => 67,  266 => 64,  263 => 63,  260 => 62,  249 => 59,  245 => 58,  241 => 57,  237 => 56,  233 => 55,  229 => 54,  226 => 53,  220 => 52,  214 => 50,  212 => 49,  209 => 48,  204 => 47,  202 => 46,  195 => 42,  177 => 41,  159 => 40,  141 => 39,  123 => 38,  105 => 37,  96 => 31,  90 => 28,  86 => 26,  78 => 22,  75 => 21,  67 => 17,  65 => 16,  60 => 13,  49 => 11,  45 => 10,  40 => 8,  33 => 6,  27 => 5,  19 => 1,);
    }
}
/* {{ header }}{{ column_left }}*/
/* <div id="content">*/
/*   <div class="page-header">*/
/*     <div class="container-fluid">*/
/*       <div class="pull-right"><a href="{{ add }}" data-toggle="tooltip" title="{{ button_add }}" class="btn btn-primary"><i class="fa fa-plus"></i></a>*/
/*         <button type="button" data-toggle="tooltip" title="{{ button_delete }}" class="btn btn-danger" onclick="confirm('{{ text_confirm }}') ? $('#form-translation').submit() : false;"><i class="fa fa-trash-o"></i></button>*/
/*       </div>*/
/*       <h1>{{ heading_title }}</h1>*/
/*       <ul class="breadcrumb">*/
/*         {% for breadcrumb in breadcrumbs %}*/
/*         <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>*/
/*         {% endfor %}*/
/*       </ul>*/
/*     </div>*/
/*   </div>*/
/*   <div class="container-fluid">{% if error_warning %}*/
/*     <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}*/
/*       <button type="button" class="close" data-dismiss="alert">&times;</button>*/
/*     </div>*/
/*     {% endif %}*/
/*     {% if success %}*/
/*     <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> {{ success }}*/
/*       <button type="button" class="close" data-dismiss="alert">&times;</button>*/
/*     </div>*/
/*     {% endif %}*/
/*     <div class="panel panel-default">*/
/*       <div class="panel-heading">*/
/*         <h3 class="panel-title"><i class="fa fa-list"></i> {{ text_list }}</h3>*/
/*       </div>*/
/*       <div class="panel-body">*/
/*         <form action="{{ delete }}" method="post" enctype="multipart/form-data" id="form-translation">*/
/*           <div class="table-responsive">*/
/*             <table class="table table-bordered">*/
/*               <thead>*/
/*                 <tr>*/
/*                   <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>*/
/*                   <td class="text-left">{% if sort == 'store' %}<a href="{{ sort_store }}" class="{{ order|lower }}">{{ column_store }}</a> {% else %}<a href="{{ sort_store }}">{{ column_store }}</a>{% endif %}</td>*/
/*                   <td class="text-left">{% if sort == 'language' %}<a href="{{ sort_language }}" class="{{ order|lower }}">{{ column_language }}</a>{% else %}<a href="{{ sort_language }}">{{ column_language }}</a>{% endif %}</td>*/
/*                   <td class="text-left">{% if sort == 'route' %}<a href="{{ sort_route }}" class="{{ order|lower }}">{{ column_route }}</a> {% else %}<a href="{{ sort_route }}">{{ column_route }}</a>{% endif %}</td>*/
/*                   <td class="text-left">{% if sort == 'key' %}<a href="{{ sort_key }}" class="{{ order|lower }}">{{ column_key }}</a>{% else %}<a href="{{ sort_key }}">{{ column_key }}</a>{% endif %}</td>*/
/*                   <td class="text-left">{% if sort == 'value' %}<a href="{{ sort_value }}" class="{{ order|lower }}">{{ column_value }}</a>{% else %}<a href="{{ sort_value }}">{{ column_value }}</a>{% endif %}</td>*/
/*                   <td class="text-right">{{ column_action }}</td>*/
/*                 </tr>*/
/*               </thead>*/
/*               <tbody>*/
/*               {% if translations %}*/
/*               {% for translation in translations %}*/
/*               <tr>*/
/*                 <td class="text-center">{% if translation.translation_id in selected %}*/
/*                   <input type="checkbox" name="selected[]" value="{{ translation.translation_id }}" checked="checked" />*/
/*                   {% else %}*/
/*                   <input type="checkbox" name="selected[]" value="{{ translation.translation_id }}" />*/
/*                   {% endif %}</td>*/
/*                 <td class="text-left">{{ translation.store }}</td>*/
/*                 <td class="text-left">{{ translation.language }}</td>*/
/*                 <td class="text-left">{{ translation.route }}</td>*/
/*                 <td class="text-left">{{ translation.key }}</td>*/
/*                 <td class="text-left">{{ translation.value }}</td>*/
/*                 <td class="text-right"><a href="{{ translation.edit }}" data-toggle="tooltip" title="{{ button_edit }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>*/
/*               </tr>*/
/*               {% endfor %}*/
/*               {% else %}*/
/*               <tr>*/
/*                 <td class="text-center" colspan="7">{{ text_no_results }}</td>*/
/*               </tr>*/
/*               {% endif %}*/
/*               </tbody>*/
/*             </table>*/
/*           </div>*/
/*         </form>*/
/*         <div class="row">*/
/*           <div class="col-sm-6 text-left">{{ pagination }}</div>*/
/*           <div class="col-sm-6 text-right">{{ results }}</div>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/* </div>*/
/* {{ footer }} */
/* */
