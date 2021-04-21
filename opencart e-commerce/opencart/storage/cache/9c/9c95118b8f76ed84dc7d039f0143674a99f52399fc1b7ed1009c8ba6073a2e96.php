<?php

/* marketplace/modification.twig */
class __TwigTemplate_caf590da1801514a6d5490e891d35944ba446d424653b4fc63acc76d9005515e extends Twig_Template
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
        echo (isset($context["refresh"]) ? $context["refresh"] : null);
        echo "\" data-toggle=\"tooltip\" title=\"";
        echo (isset($context["button_refresh"]) ? $context["button_refresh"] : null);
        echo "\" class=\"btn btn-info\"><i class=\"fa fa-refresh\"></i></a> <a href=\"";
        echo (isset($context["clear"]) ? $context["clear"] : null);
        echo "\" data-toggle=\"tooltip\" title=\"";
        echo (isset($context["button_clear"]) ? $context["button_clear"] : null);
        echo "\" class=\"btn btn-warning\"><i class=\"fa fa-eraser\"></i></a>
        <button type=\"button\" data-toggle=\"tooltip\" title=\"";
        // line 6
        echo (isset($context["button_delete"]) ? $context["button_delete"] : null);
        echo "\" class=\"btn btn-danger\" onclick=\"confirm('";
        echo (isset($context["text_confirm"]) ? $context["text_confirm"] : null);
        echo "') ? \$('#form-modification').submit() : false;\"><i class=\"fa fa-trash-o\"></i></button>
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
  <div class=\"container-fluid\">
    ";
        // line 17
        if ((isset($context["error_warning"]) ? $context["error_warning"] : null)) {
            // line 18
            echo "    <div class=\"alert alert-danger alert-dismissible\"><i class=\"fa fa-exclamation-circle\"></i> ";
            echo (isset($context["error_warning"]) ? $context["error_warning"] : null);
            echo "
      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    </div>
    ";
        }
        // line 22
        echo "    ";
        if ((isset($context["success"]) ? $context["success"] : null)) {
            // line 23
            echo "    <div class=\"alert alert-success alert-dismissible\"><i class=\"fa fa-check-circle\"></i> ";
            echo (isset($context["success"]) ? $context["success"] : null);
            echo "
      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    </div>
    ";
        }
        // line 27
        echo "    <div class=\"alert alert-info\"><i class=\"fa fa-info-circle\"></i> ";
        echo (isset($context["text_refresh"]) ? $context["text_refresh"] : null);
        echo "</div>
    <div class=\"panel panel-default\">
      <div class=\"panel-heading\">
        <h3 class=\"panel-title\"><i class=\"fa fa-list\"></i> ";
        // line 30
        echo (isset($context["text_list"]) ? $context["text_list"] : null);
        echo "</h3>
      </div>
      <div class=\"panel-body\">
        <ul class=\"nav nav-tabs\">
          <li class=\"active\"><a href=\"#tab-general\" data-toggle=\"tab\">";
        // line 34
        echo (isset($context["tab_general"]) ? $context["tab_general"] : null);
        echo "</a></li>
          <li><a href=\"#tab-log\" data-toggle=\"tab\">";
        // line 35
        echo (isset($context["tab_log"]) ? $context["tab_log"] : null);
        echo "</a></li>
        </ul>
        <div class=\"tab-content\">
          <div class=\"tab-pane active\" id=\"tab-general\">
            <form action=\"";
        // line 39
        echo (isset($context["delete"]) ? $context["delete"] : null);
        echo "\" method=\"post\" enctype=\"multipart/form-data\" id=\"form-modification\">
              <div class=\"table-responsive\">
                <table class=\"table table-bordered table-hover\">
                  <thead>
                    <tr>
                      <td style=\"width: 1px;\" class=\"text-center\"><input type=\"checkbox\" onclick=\"\$('input[name*=\\'selected\\']').prop('checked', this.checked);\" /></td>
                      <td class=\"text-left\">";
        // line 45
        if (((isset($context["sort"]) ? $context["sort"] : null) == "name")) {
            // line 46
            echo "                        <a href=\"";
            echo (isset($context["sort_name"]) ? $context["sort_name"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_name"]) ? $context["column_name"] : null);
            echo "</a>
                        ";
        } else {
            // line 48
            echo "                        <a href=\"";
            echo (isset($context["sort_name"]) ? $context["sort_name"] : null);
            echo "\">";
            echo (isset($context["column_name"]) ? $context["column_name"] : null);
            echo "</a>
                        ";
        }
        // line 49
        echo "</td>
                      <td class=\"text-left\">";
        // line 50
        if (((isset($context["sort"]) ? $context["sort"] : null) == "author")) {
            // line 51
            echo "                        <a href=\"";
            echo (isset($context["sort_author"]) ? $context["sort_author"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_author"]) ? $context["column_author"] : null);
            echo "</a>
                        ";
        } else {
            // line 53
            echo "                        <a href=\"";
            echo (isset($context["sort_author"]) ? $context["sort_author"] : null);
            echo "\">";
            echo (isset($context["column_author"]) ? $context["column_author"] : null);
            echo "</a>
                        ";
        }
        // line 54
        echo "</td>
                      <td class=\"text-left\">";
        // line 55
        if (((isset($context["sort"]) ? $context["sort"] : null) == "version")) {
            // line 56
            echo "                        <a href=\"";
            echo (isset($context["sort_version"]) ? $context["sort_version"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_version"]) ? $context["column_version"] : null);
            echo "</a>
                        ";
        } else {
            // line 58
            echo "                        <a href=\"";
            echo (isset($context["sort_version"]) ? $context["sort_version"] : null);
            echo "\">";
            echo (isset($context["column_version"]) ? $context["column_version"] : null);
            echo "</a>
                        ";
        }
        // line 59
        echo "</td>
                      <td class=\"text-left\">";
        // line 60
        if (((isset($context["sort"]) ? $context["sort"] : null) == "status")) {
            // line 61
            echo "                        <a href=\"";
            echo (isset($context["sort_status"]) ? $context["sort_status"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_status"]) ? $context["column_status"] : null);
            echo "</a>
                        ";
        } else {
            // line 63
            echo "                        <a href=\"";
            echo (isset($context["sort_status"]) ? $context["sort_status"] : null);
            echo "\">";
            echo (isset($context["column_status"]) ? $context["column_status"] : null);
            echo "</a>
                        ";
        }
        // line 64
        echo "</td>
                      <td class=\"text-left\">";
        // line 65
        if (((isset($context["sort"]) ? $context["sort"] : null) == "date_added")) {
            // line 66
            echo "                        <a href=\"";
            echo (isset($context["sort_date_added"]) ? $context["sort_date_added"] : null);
            echo "\" class=\"";
            echo twig_lower_filter($this->env, (isset($context["order"]) ? $context["order"] : null));
            echo "\">";
            echo (isset($context["column_date_added"]) ? $context["column_date_added"] : null);
            echo "</a>
                        ";
        } else {
            // line 68
            echo "                        <a href=\"";
            echo (isset($context["sort_date_added"]) ? $context["sort_date_added"] : null);
            echo "\">";
            echo (isset($context["column_date_added"]) ? $context["column_date_added"] : null);
            echo "</a>
                        ";
        }
        // line 69
        echo "</td>
                      <td class=\"text-right\">";
        // line 70
        echo (isset($context["column_action"]) ? $context["column_action"] : null);
        echo "</td>
                    </tr>
                  </thead>
                  <tbody>
                    ";
        // line 74
        if ((isset($context["modifications"]) ? $context["modifications"] : null)) {
            // line 75
            echo "                    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["modifications"]) ? $context["modifications"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["modification"]) {
                // line 76
                echo "                    <tr>
                      <td class=\"text-center\">";
                // line 77
                if (twig_in_filter($this->getAttribute($context["modification"], "modification_id", array()), (isset($context["selected"]) ? $context["selected"] : null))) {
                    // line 78
                    echo "                        <input type=\"checkbox\" name=\"selected[]\" value=\"";
                    echo $this->getAttribute($context["modification"], "modification_id", array());
                    echo "\" checked=\"checked\" />
                        ";
                } else {
                    // line 80
                    echo "                        <input type=\"checkbox\" name=\"selected[]\" value=\"";
                    echo $this->getAttribute($context["modification"], "modification_id", array());
                    echo "\" />
                        ";
                }
                // line 81
                echo "</td>
                      <td class=\"text-left\">";
                // line 82
                echo $this->getAttribute($context["modification"], "name", array());
                echo "</td>
                      <td class=\"text-left\">";
                // line 83
                echo $this->getAttribute($context["modification"], "author", array());
                echo "</td>
                      <td class=\"text-left\">";
                // line 84
                echo $this->getAttribute($context["modification"], "version", array());
                echo "</td>
                      <td class=\"text-left\">";
                // line 85
                echo $this->getAttribute($context["modification"], "status", array());
                echo "</td>
                      <td class=\"text-left\">";
                // line 86
                echo $this->getAttribute($context["modification"], "date_added", array());
                echo "</td>
                      <td class=\"text-right\">";
                // line 87
                if ($this->getAttribute($context["modification"], "link", array())) {
                    // line 88
                    echo "                        <a href=\"";
                    echo $this->getAttribute($context["modification"], "link", array());
                    echo "\" data-toggle=\"tooltip\" title=\"";
                    echo (isset($context["button_link"]) ? $context["button_link"] : null);
                    echo "\" class=\"btn btn-info\" target=\"_blank\"><i class=\"fa fa-link\"></i></a>
                        ";
                } else {
                    // line 90
                    echo "                        <button type=\"button\" class=\"btn btn-info\" disabled=\"disabled\"><i class=\"fa fa-link\"></i></button>
                        ";
                }
                // line 92
                echo "                        ";
                if ( !$this->getAttribute($context["modification"], "enabled", array())) {
                    // line 93
                    echo "                        <a href=\"";
                    echo $this->getAttribute($context["modification"], "enable", array());
                    echo "\" data-toggle=\"tooltip\" title=\"";
                    echo (isset($context["button_enable"]) ? $context["button_enable"] : null);
                    echo "\" class=\"btn btn-success\"><i class=\"fa fa-plus-circle\"></i></a>
                        ";
                } else {
                    // line 95
                    echo "                        <a href=\"";
                    echo $this->getAttribute($context["modification"], "disable", array());
                    echo "\" data-toggle=\"tooltip\" title=\"";
                    echo (isset($context["button_disable"]) ? $context["button_disable"] : null);
                    echo "\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\"></i></a>
                        ";
                }
                // line 96
                echo "</td>
                    </tr>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['modification'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 99
            echo "                    ";
        } else {
            // line 100
            echo "                    <tr>
                      <td class=\"text-center\" colspan=\"7\">";
            // line 101
            echo (isset($context["text_no_results"]) ? $context["text_no_results"] : null);
            echo "</td>
                    </tr>
                    ";
        }
        // line 104
        echo "                  </tbody>
                </table>
              </div>
            </form>
            <div class=\"row\">
              <div class=\"col-sm-6 text-left\">";
        // line 109
        echo (isset($context["pagination"]) ? $context["pagination"] : null);
        echo "</div>
              <div class=\"col-sm-6 text-right\">";
        // line 110
        echo (isset($context["results"]) ? $context["results"] : null);
        echo "</div>
            </div>
          </div>
          <div class=\"tab-pane\" id=\"tab-log\">
            <p>
              <textarea wrap=\"off\" rows=\"15\" class=\"form-control\">";
        // line 115
        echo (isset($context["log"]) ? $context["log"] : null);
        echo "</textarea>
            </p>
            <div class=\"text-center\"><a href=\"";
        // line 117
        echo (isset($context["clear_log"]) ? $context["clear_log"] : null);
        echo "\" class=\"btn btn-danger\"><i class=\"fa fa-eraser\"></i> ";
        echo (isset($context["button_clear"]) ? $context["button_clear"] : null);
        echo "</a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
";
        // line 124
        echo (isset($context["footer"]) ? $context["footer"] : null);
    }

    public function getTemplateName()
    {
        return "marketplace/modification.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  383 => 124,  371 => 117,  366 => 115,  358 => 110,  354 => 109,  347 => 104,  341 => 101,  338 => 100,  335 => 99,  327 => 96,  319 => 95,  311 => 93,  308 => 92,  304 => 90,  296 => 88,  294 => 87,  290 => 86,  286 => 85,  282 => 84,  278 => 83,  274 => 82,  271 => 81,  265 => 80,  259 => 78,  257 => 77,  254 => 76,  249 => 75,  247 => 74,  240 => 70,  237 => 69,  229 => 68,  219 => 66,  217 => 65,  214 => 64,  206 => 63,  196 => 61,  194 => 60,  191 => 59,  183 => 58,  173 => 56,  171 => 55,  168 => 54,  160 => 53,  150 => 51,  148 => 50,  145 => 49,  137 => 48,  127 => 46,  125 => 45,  116 => 39,  109 => 35,  105 => 34,  98 => 30,  91 => 27,  83 => 23,  80 => 22,  72 => 18,  70 => 17,  64 => 13,  53 => 11,  49 => 10,  44 => 8,  37 => 6,  27 => 5,  19 => 1,);
    }
}
/* {{ header }}{{ column_left }}*/
/* <div id="content">*/
/*   <div class="page-header">*/
/*     <div class="container-fluid">*/
/*       <div class="pull-right"><a href="{{ refresh }}" data-toggle="tooltip" title="{{ button_refresh }}" class="btn btn-info"><i class="fa fa-refresh"></i></a> <a href="{{ clear }}" data-toggle="tooltip" title="{{ button_clear }}" class="btn btn-warning"><i class="fa fa-eraser"></i></a>*/
/*         <button type="button" data-toggle="tooltip" title="{{ button_delete }}" class="btn btn-danger" onclick="confirm('{{ text_confirm }}') ? $('#form-modification').submit() : false;"><i class="fa fa-trash-o"></i></button>*/
/*       </div>*/
/*       <h1>{{ heading_title }}</h1>*/
/*       <ul class="breadcrumb">*/
/*         {% for breadcrumb in breadcrumbs %}*/
/*         <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>*/
/*         {% endfor %}*/
/*       </ul>*/
/*     </div>*/
/*   </div>*/
/*   <div class="container-fluid">*/
/*     {% if error_warning %}*/
/*     <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}*/
/*       <button type="button" class="close" data-dismiss="alert">&times;</button>*/
/*     </div>*/
/*     {% endif %}*/
/*     {% if success %}*/
/*     <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> {{ success }}*/
/*       <button type="button" class="close" data-dismiss="alert">&times;</button>*/
/*     </div>*/
/*     {% endif %}*/
/*     <div class="alert alert-info"><i class="fa fa-info-circle"></i> {{ text_refresh }}</div>*/
/*     <div class="panel panel-default">*/
/*       <div class="panel-heading">*/
/*         <h3 class="panel-title"><i class="fa fa-list"></i> {{ text_list }}</h3>*/
/*       </div>*/
/*       <div class="panel-body">*/
/*         <ul class="nav nav-tabs">*/
/*           <li class="active"><a href="#tab-general" data-toggle="tab">{{ tab_general }}</a></li>*/
/*           <li><a href="#tab-log" data-toggle="tab">{{ tab_log }}</a></li>*/
/*         </ul>*/
/*         <div class="tab-content">*/
/*           <div class="tab-pane active" id="tab-general">*/
/*             <form action="{{ delete }}" method="post" enctype="multipart/form-data" id="form-modification">*/
/*               <div class="table-responsive">*/
/*                 <table class="table table-bordered table-hover">*/
/*                   <thead>*/
/*                     <tr>*/
/*                       <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>*/
/*                       <td class="text-left">{% if sort == 'name' %}*/
/*                         <a href="{{ sort_name }}" class="{{ order|lower }}">{{ column_name }}</a>*/
/*                         {% else %}*/
/*                         <a href="{{ sort_name }}">{{ column_name }}</a>*/
/*                         {% endif %}</td>*/
/*                       <td class="text-left">{% if sort == 'author' %}*/
/*                         <a href="{{ sort_author }}" class="{{ order|lower }}">{{ column_author }}</a>*/
/*                         {% else %}*/
/*                         <a href="{{ sort_author }}">{{ column_author }}</a>*/
/*                         {% endif %}</td>*/
/*                       <td class="text-left">{% if sort == 'version' %}*/
/*                         <a href="{{ sort_version }}" class="{{ order|lower }}">{{ column_version }}</a>*/
/*                         {% else %}*/
/*                         <a href="{{ sort_version }}">{{ column_version }}</a>*/
/*                         {% endif %}</td>*/
/*                       <td class="text-left">{% if sort == 'status' %}*/
/*                         <a href="{{ sort_status }}" class="{{ order|lower }}">{{ column_status }}</a>*/
/*                         {% else %}*/
/*                         <a href="{{ sort_status }}">{{ column_status }}</a>*/
/*                         {% endif %}</td>*/
/*                       <td class="text-left">{% if sort == 'date_added' %}*/
/*                         <a href="{{ sort_date_added }}" class="{{ order|lower }}">{{ column_date_added }}</a>*/
/*                         {% else %}*/
/*                         <a href="{{ sort_date_added }}">{{ column_date_added }}</a>*/
/*                         {% endif %}</td>*/
/*                       <td class="text-right">{{ column_action }}</td>*/
/*                     </tr>*/
/*                   </thead>*/
/*                   <tbody>*/
/*                     {% if modifications %}*/
/*                     {% for modification in modifications %}*/
/*                     <tr>*/
/*                       <td class="text-center">{% if modification.modification_id in selected %}*/
/*                         <input type="checkbox" name="selected[]" value="{{ modification.modification_id }}" checked="checked" />*/
/*                         {% else %}*/
/*                         <input type="checkbox" name="selected[]" value="{{ modification.modification_id }}" />*/
/*                         {% endif %}</td>*/
/*                       <td class="text-left">{{ modification.name }}</td>*/
/*                       <td class="text-left">{{ modification.author }}</td>*/
/*                       <td class="text-left">{{ modification.version }}</td>*/
/*                       <td class="text-left">{{ modification.status }}</td>*/
/*                       <td class="text-left">{{ modification.date_added }}</td>*/
/*                       <td class="text-right">{% if modification.link %}*/
/*                         <a href="{{ modification.link }}" data-toggle="tooltip" title="{{ button_link }}" class="btn btn-info" target="_blank"><i class="fa fa-link"></i></a>*/
/*                         {% else %}*/
/*                         <button type="button" class="btn btn-info" disabled="disabled"><i class="fa fa-link"></i></button>*/
/*                         {% endif %}*/
/*                         {% if not modification.enabled %}*/
/*                         <a href="{{ modification.enable }}" data-toggle="tooltip" title="{{ button_enable }}" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>*/
/*                         {% else %}*/
/*                         <a href="{{ modification.disable }}" data-toggle="tooltip" title="{{ button_disable }}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>*/
/*                         {% endif %}</td>*/
/*                     </tr>*/
/*                     {% endfor %}*/
/*                     {% else %}*/
/*                     <tr>*/
/*                       <td class="text-center" colspan="7">{{ text_no_results }}</td>*/
/*                     </tr>*/
/*                     {% endif %}*/
/*                   </tbody>*/
/*                 </table>*/
/*               </div>*/
/*             </form>*/
/*             <div class="row">*/
/*               <div class="col-sm-6 text-left">{{ pagination }}</div>*/
/*               <div class="col-sm-6 text-right">{{ results }}</div>*/
/*             </div>*/
/*           </div>*/
/*           <div class="tab-pane" id="tab-log">*/
/*             <p>*/
/*               <textarea wrap="off" rows="15" class="form-control">{{ log }}</textarea>*/
/*             </p>*/
/*             <div class="text-center"><a href="{{ clear_log }}" class="btn btn-danger"><i class="fa fa-eraser"></i> {{ button_clear }}</a></div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/* </div>*/
/* {{ footer }}*/
