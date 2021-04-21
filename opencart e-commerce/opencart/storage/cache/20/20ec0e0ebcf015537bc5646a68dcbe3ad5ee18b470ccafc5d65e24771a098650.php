<?php

/* marketplace/extension.twig */
class __TwigTemplate_017c5b971b0cddbfdc75f9af53020a848bda8f0961010560cf4654fde56c2d0e extends Twig_Template
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
      <h1>";
        // line 5
        echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
        echo "</h1>
      <ul class=\"breadcrumb\">
        ";
        // line 7
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["breadcrumbs"]) ? $context["breadcrumbs"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 8
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
        // line 10
        echo "      </ul>
    </div>
  </div>
  <div class=\"container-fluid\">
    <div class=\"panel panel-default\">
      <div class=\"panel-heading\">
        <h3 class=\"panel-title\"><i class=\"fa fa-puzzle-piece\"></i> ";
        // line 16
        echo (isset($context["text_list"]) ? $context["text_list"] : null);
        echo "</h3>
      </div>
      <div class=\"panel-body\">
        <fieldset>
          <legend>";
        // line 20
        echo (isset($context["text_type"]) ? $context["text_type"] : null);
        echo "</legend>
          <div class=\"well\">
            <div class=\"input-group\">
              <select name=\"type\" class=\"form-control\">
                ";
        // line 24
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["categories"]) ? $context["categories"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
            // line 25
            echo "                ";
            if (((isset($context["type"]) ? $context["type"] : null) == $this->getAttribute($context["category"], "code", array()))) {
                // line 26
                echo "                <option value=\"";
                echo $this->getAttribute($context["category"], "href", array());
                echo "\" selected=\"selected\">";
                echo $this->getAttribute($context["category"], "text", array());
                echo "</option>
                ";
            } else {
                // line 28
                echo "                <option value=\"";
                echo $this->getAttribute($context["category"], "href", array());
                echo "\">";
                echo $this->getAttribute($context["category"], "text", array());
                echo "</option>
                ";
            }
            // line 30
            echo "                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        echo "              </select>
              <span class=\"input-group-addon\"><i class=\"fa fa-filter\"></i> ";
        // line 32
        echo (isset($context["text_filter"]) ? $context["text_filter"] : null);
        echo "</span>
            </div>
          </div>
        </fieldset>
        <div id=\"extension\"></div>
      </div>
    </div>
  </div>
  ";
        // line 40
        if ((isset($context["categories"]) ? $context["categories"] : null)) {
            // line 41
            echo "  <script type=\"text/javascript\"><!--
\$('select[name=\"type\"]').on('change', function() {
\t\$.ajax({
\t\turl: \$('select[name=\"type\"]').val(),
\t\tdataType: 'html',
\t\tbeforeSend: function() {
\t\t\t\$('.fa-filter').addClass('fa-circle-o-notch fa-spin');
\t\t\t\$('.fa-filter').removeClass('fa-filter');
\t\t\t\$('select[name=\\'type\\']').prop('disabled', true);
\t\t},
\t\tcomplete: function() {
\t\t\t\$('.fa-circle-o-notch').addClass('fa-filter');
\t\t\t\$('.fa-circle-o-notch').removeClass('fa-circle-o-notch fa-spin');
\t\t\t\$('select[name=\\'type\\']').prop('disabled', false);
\t\t},
\t\tsuccess: function(html) {
\t\t\t\$('#extension').html(html);
\t\t},
\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t}
\t});
});

\$('select[name=\"type\"]').trigger('change');

\$('#extension').on('click', '.btn-success', function(e) {
\te.preventDefault();
\t
\tvar element = this;

\t\$.ajax({
\t\turl: \$(element).attr('href'),
\t\tdataType: 'html',
\t\tbeforeSend: function() {
\t\t\t\$(element).button('loading');
\t\t},
\t\tcomplete: function() {
\t\t\t\$(element).button('reset');
\t\t},
\t\tsuccess: function(html) {
\t\t\t\$('#extension').html(html);
\t\t},
\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t}
\t});
});

\$('#extension').on('click', '.btn-danger, .btn-warning', function(e) {
\te.preventDefault();
\t
\tif (confirm('";
            // line 93
            echo (isset($context["text_confirm"]) ? $context["text_confirm"] : null);
            echo "')) {
\t\tvar element = this;
\t
\t\t\$.ajax({
\t\t\turl: \$(element).attr('href'),
\t\t\tdataType: 'html',
\t\t\tbeforeSend: function() {
\t\t\t\t\$(element).button('loading');
\t\t\t},
\t\t\tcomplete: function() {
\t\t\t\t\$(element).button('reset');
\t\t\t},
\t\t\tsuccess: function(html) {
\t\t\t\t\$('#extension').html(html);
\t\t\t},
\t\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t\t}
\t\t});
\t}
});
//--></script>
  ";
        }
        // line 116
        echo "</div>
";
        // line 117
        echo (isset($context["footer"]) ? $context["footer"] : null);
        echo " ";
    }

    public function getTemplateName()
    {
        return "marketplace/extension.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  197 => 117,  194 => 116,  168 => 93,  114 => 41,  112 => 40,  101 => 32,  98 => 31,  92 => 30,  84 => 28,  76 => 26,  73 => 25,  69 => 24,  62 => 20,  55 => 16,  47 => 10,  36 => 8,  32 => 7,  27 => 5,  19 => 1,);
    }
}
/* {{ header }}{{ column_left }}*/
/* <div id="content">*/
/*   <div class="page-header">*/
/*     <div class="container-fluid">*/
/*       <h1>{{ heading_title }}</h1>*/
/*       <ul class="breadcrumb">*/
/*         {% for breadcrumb in breadcrumbs %}*/
/*         <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>*/
/*         {% endfor %}*/
/*       </ul>*/
/*     </div>*/
/*   </div>*/
/*   <div class="container-fluid">*/
/*     <div class="panel panel-default">*/
/*       <div class="panel-heading">*/
/*         <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> {{ text_list }}</h3>*/
/*       </div>*/
/*       <div class="panel-body">*/
/*         <fieldset>*/
/*           <legend>{{ text_type }}</legend>*/
/*           <div class="well">*/
/*             <div class="input-group">*/
/*               <select name="type" class="form-control">*/
/*                 {% for category in categories %}*/
/*                 {% if type == category.code %}*/
/*                 <option value="{{ category.href }}" selected="selected">{{ category.text }}</option>*/
/*                 {% else %}*/
/*                 <option value="{{ category.href }}">{{ category.text }}</option>*/
/*                 {% endif %}*/
/*                 {% endfor %}*/
/*               </select>*/
/*               <span class="input-group-addon"><i class="fa fa-filter"></i> {{ text_filter }}</span>*/
/*             </div>*/
/*           </div>*/
/*         </fieldset>*/
/*         <div id="extension"></div>*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/*   {% if categories %}*/
/*   <script type="text/javascript"><!--*/
/* $('select[name="type"]').on('change', function() {*/
/* 	$.ajax({*/
/* 		url: $('select[name="type"]').val(),*/
/* 		dataType: 'html',*/
/* 		beforeSend: function() {*/
/* 			$('.fa-filter').addClass('fa-circle-o-notch fa-spin');*/
/* 			$('.fa-filter').removeClass('fa-filter');*/
/* 			$('select[name=\'type\']').prop('disabled', true);*/
/* 		},*/
/* 		complete: function() {*/
/* 			$('.fa-circle-o-notch').addClass('fa-filter');*/
/* 			$('.fa-circle-o-notch').removeClass('fa-circle-o-notch fa-spin');*/
/* 			$('select[name=\'type\']').prop('disabled', false);*/
/* 		},*/
/* 		success: function(html) {*/
/* 			$('#extension').html(html);*/
/* 		},*/
/* 		error: function(xhr, ajaxOptions, thrownError) {*/
/* 			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 		}*/
/* 	});*/
/* });*/
/* */
/* $('select[name="type"]').trigger('change');*/
/* */
/* $('#extension').on('click', '.btn-success', function(e) {*/
/* 	e.preventDefault();*/
/* 	*/
/* 	var element = this;*/
/* */
/* 	$.ajax({*/
/* 		url: $(element).attr('href'),*/
/* 		dataType: 'html',*/
/* 		beforeSend: function() {*/
/* 			$(element).button('loading');*/
/* 		},*/
/* 		complete: function() {*/
/* 			$(element).button('reset');*/
/* 		},*/
/* 		success: function(html) {*/
/* 			$('#extension').html(html);*/
/* 		},*/
/* 		error: function(xhr, ajaxOptions, thrownError) {*/
/* 			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 		}*/
/* 	});*/
/* });*/
/* */
/* $('#extension').on('click', '.btn-danger, .btn-warning', function(e) {*/
/* 	e.preventDefault();*/
/* 	*/
/* 	if (confirm('{{ text_confirm }}')) {*/
/* 		var element = this;*/
/* 	*/
/* 		$.ajax({*/
/* 			url: $(element).attr('href'),*/
/* 			dataType: 'html',*/
/* 			beforeSend: function() {*/
/* 				$(element).button('loading');*/
/* 			},*/
/* 			complete: function() {*/
/* 				$(element).button('reset');*/
/* 			},*/
/* 			success: function(html) {*/
/* 				$('#extension').html(html);*/
/* 			},*/
/* 			error: function(xhr, ajaxOptions, thrownError) {*/
/* 				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 			}*/
/* 		});*/
/* 	}*/
/* });*/
/* //--></script>*/
/*   {% endif %}*/
/* </div>*/
/* {{ footer }} */
