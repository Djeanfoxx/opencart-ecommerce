<?php

/* extension/extension/module.twig */
class __TwigTemplate_f39b61068b0c5704fad98e0803b4a9e7a5f96dc284096d92291b13ed7c35eddc extends Twig_Template
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
        echo (isset($context["promotion"]) ? $context["promotion"] : null);
        echo "
<fieldset>
  <legend>";
        // line 3
        echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
        echo "</legend>
  ";
        // line 4
        if ((isset($context["error_warning"]) ? $context["error_warning"] : null)) {
            // line 5
            echo "  <div class=\"alert alert-danger alert-dismissible\"><i class=\"fa fa-exclamation-circle\"></i> ";
            echo (isset($context["error_warning"]) ? $context["error_warning"] : null);
            echo "
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
  </div>
  ";
        }
        // line 9
        echo "  ";
        if ((isset($context["success"]) ? $context["success"] : null)) {
            // line 10
            echo "  <div class=\"alert alert-success alert-dismissible\"><i class=\"fa fa-check-circle\"></i> ";
            echo (isset($context["success"]) ? $context["success"] : null);
            echo "
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
  </div>
  ";
        }
        // line 14
        echo "  <div class=\"alert alert-info\"><i class=\"fa fa-info-circle\"></i> ";
        echo (isset($context["text_layout"]) ? $context["text_layout"] : null);
        echo "</div>
  <div class=\"table-responsive\">
    <table class=\"table table-bordered table-hover\">
      <thead>
        <tr>
          <td class=\"text-left\">";
        // line 19
        echo (isset($context["column_name"]) ? $context["column_name"] : null);
        echo "</td>
          <td class=\"text-left\">";
        // line 20
        echo (isset($context["column_status"]) ? $context["column_status"] : null);
        echo "</td>
          <td class=\"text-right\">";
        // line 21
        echo (isset($context["column_action"]) ? $context["column_action"] : null);
        echo "</td>
        </tr>
      </thead>
      <tbody>
      
      ";
        // line 26
        if ((isset($context["extensions"]) ? $context["extensions"] : null)) {
            // line 27
            echo "      ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["extensions"]) ? $context["extensions"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["extension"]) {
                // line 28
                echo "      <tr>
        <td><b>";
                // line 29
                echo $this->getAttribute($context["extension"], "name", array());
                echo "</b></td>
        <td>";
                // line 30
                echo $this->getAttribute($context["extension"], "status", array());
                echo "</td>
        <td class=\"text-right\">";
                // line 31
                if ($this->getAttribute($context["extension"], "installed", array())) {
                    // line 32
                    echo "          ";
                    if ($this->getAttribute($context["extension"], "module", array())) {
                        echo " <a href=\"";
                        echo $this->getAttribute($context["extension"], "edit", array());
                        echo "\" data-toggle=\"tooltip\" title=\"";
                        echo (isset($context["button_add"]) ? $context["button_add"] : null);
                        echo "\" class=\"btn btn-primary\"><i class=\"fa fa-plus-circle\"></i></a> ";
                    } else {
                        echo " <a href=\"";
                        echo $this->getAttribute($context["extension"], "edit", array());
                        echo "\" data-toggle=\"tooltip\" title=\"";
                        echo (isset($context["button_edit"]) ? $context["button_edit"] : null);
                        echo "\" class=\"btn btn-primary\"><i class=\"fa fa-pencil\"></i></a> ";
                    }
                    // line 33
                    echo "          ";
                } else {
                    // line 34
                    echo "          <button type=\"button\" class=\"btn btn-primary\" disabled=\"disabled\"><i class=\"fa fa-pencil\"></i></button>
          ";
                }
                // line 36
                echo "          ";
                if ( !$this->getAttribute($context["extension"], "installed", array())) {
                    echo "<a href=\"";
                    echo $this->getAttribute($context["extension"], "install", array());
                    echo "\" data-toggle=\"tooltip\" title=\"";
                    echo (isset($context["button_install"]) ? $context["button_install"] : null);
                    echo "\" class=\"btn btn-success\"><i class=\"fa fa-plus-circle\"></i></a> ";
                } else {
                    echo " <a href=\"";
                    echo $this->getAttribute($context["extension"], "uninstall", array());
                    echo "\" data-toggle=\"tooltip\" title=\"";
                    echo (isset($context["button_uninstall"]) ? $context["button_uninstall"] : null);
                    echo "\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\"></i></a>";
                }
                echo "</td>
      </tr>
      ";
                // line 38
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["extension"], "module", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["module"]) {
                    // line 39
                    echo "      <tr>
        <td class=\"text-left\">&nbsp;&nbsp;&nbsp;<i class=\"fa fa-folder-open\"></i>&nbsp;&nbsp;&nbsp;";
                    // line 40
                    echo $this->getAttribute($context["module"], "name", array());
                    echo "</td>
        <td class=\"text-left\">";
                    // line 41
                    echo $this->getAttribute($context["module"], "status", array());
                    echo "</td>
        <td class=\"text-right\"><a href=\"";
                    // line 42
                    echo $this->getAttribute($context["module"], "edit", array());
                    echo "\" data-toggle=\"tooltip\" title=\"";
                    echo (isset($context["button_edit"]) ? $context["button_edit"] : null);
                    echo "\" class=\"btn btn-info\"><i class=\"fa fa-pencil\"></i></a> <a href=\"";
                    echo $this->getAttribute($context["module"], "delete", array());
                    echo "\" data-toggle=\"tooltip\" title=\"";
                    echo (isset($context["button_delete"]) ? $context["button_delete"] : null);
                    echo "\" class=\"btn btn-warning\"><i class=\"fa fa-trash-o\"></i></a></td>
      </tr>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['module'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 45
                echo "      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['extension'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 46
            echo "      ";
        } else {
            // line 47
            echo "      <tr>
        <td class=\"text-center\" colspan=\"3\">";
            // line 48
            echo (isset($context["text_no_results"]) ? $context["text_no_results"] : null);
            echo "</td>
      </tr>
      ";
        }
        // line 51
        echo "      </tbody>
      
    </table>
  </div>
</fieldset>
";
    }

    public function getTemplateName()
    {
        return "extension/extension/module.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  182 => 51,  176 => 48,  173 => 47,  170 => 46,  164 => 45,  149 => 42,  145 => 41,  141 => 40,  138 => 39,  134 => 38,  116 => 36,  112 => 34,  109 => 33,  94 => 32,  92 => 31,  88 => 30,  84 => 29,  81 => 28,  76 => 27,  74 => 26,  66 => 21,  62 => 20,  58 => 19,  49 => 14,  41 => 10,  38 => 9,  30 => 5,  28 => 4,  24 => 3,  19 => 1,);
    }
}
/* {{ promotion }}*/
/* <fieldset>*/
/*   <legend>{{ heading_title }}</legend>*/
/*   {% if error_warning %}*/
/*   <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}*/
/*     <button type="button" class="close" data-dismiss="alert">&times;</button>*/
/*   </div>*/
/*   {% endif %}*/
/*   {% if success %}*/
/*   <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> {{ success }}*/
/*     <button type="button" class="close" data-dismiss="alert">&times;</button>*/
/*   </div>*/
/*   {% endif %}*/
/*   <div class="alert alert-info"><i class="fa fa-info-circle"></i> {{ text_layout }}</div>*/
/*   <div class="table-responsive">*/
/*     <table class="table table-bordered table-hover">*/
/*       <thead>*/
/*         <tr>*/
/*           <td class="text-left">{{ column_name }}</td>*/
/*           <td class="text-left">{{ column_status }}</td>*/
/*           <td class="text-right">{{ column_action }}</td>*/
/*         </tr>*/
/*       </thead>*/
/*       <tbody>*/
/*       */
/*       {% if extensions %}*/
/*       {% for extension in extensions %}*/
/*       <tr>*/
/*         <td><b>{{ extension.name }}</b></td>*/
/*         <td>{{ extension.status }}</td>*/
/*         <td class="text-right">{% if extension.installed %}*/
/*           {% if extension.module %} <a href="{{ extension.edit }}" data-toggle="tooltip" title="{{ button_add }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a> {% else %} <a href="{{ extension.edit }}" data-toggle="tooltip" title="{{ button_edit }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a> {% endif %}*/
/*           {% else %}*/
/*           <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>*/
/*           {% endif %}*/
/*           {% if not extension.installed %}<a href="{{ extension.install }}" data-toggle="tooltip" title="{{ button_install }}" class="btn btn-success"><i class="fa fa-plus-circle"></i></a> {% else %} <a href="{{ extension.uninstall }}" data-toggle="tooltip" title="{{ button_uninstall }}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>{% endif %}</td>*/
/*       </tr>*/
/*       {% for module in extension.module %}*/
/*       <tr>*/
/*         <td class="text-left">&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open"></i>&nbsp;&nbsp;&nbsp;{{ module.name }}</td>*/
/*         <td class="text-left">{{ module.status }}</td>*/
/*         <td class="text-right"><a href="{{ module.edit }}" data-toggle="tooltip" title="{{ button_edit }}" class="btn btn-info"><i class="fa fa-pencil"></i></a> <a href="{{ module.delete }}" data-toggle="tooltip" title="{{ button_delete }}" class="btn btn-warning"><i class="fa fa-trash-o"></i></a></td>*/
/*       </tr>*/
/*       {% endfor %}*/
/*       {% endfor %}*/
/*       {% else %}*/
/*       <tr>*/
/*         <td class="text-center" colspan="3">{{ text_no_results }}</td>*/
/*       </tr>*/
/*       {% endif %}*/
/*       </tbody>*/
/*       */
/*     </table>*/
/*   </div>*/
/* </fieldset>*/
/* */
