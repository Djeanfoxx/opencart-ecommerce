<?php

/* extension/theme/default.twig */
class __TwigTemplate_fdf0af8c41a09a6c2285095cba60d1548b8edcccec29483af3e21cd1e952d29e extends Twig_Template
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
      <div class=\"pull-right\">
        <button type=\"submit\" form=\"form-theme\" data-toggle=\"tooltip\" title=\"";
        // line 6
        echo (isset($context["button_save"]) ? $context["button_save"] : null);
        echo "\" class=\"btn btn-primary\"><i class=\"fa fa-save\"></i></button>
        <a href=\"";
        // line 7
        echo (isset($context["cancel"]) ? $context["cancel"] : null);
        echo "\" data-toggle=\"tooltip\" title=\"";
        echo (isset($context["button_cancel"]) ? $context["button_cancel"] : null);
        echo "\" class=\"btn btn-default\"><i class=\"fa fa-reply\"></i></a></div>
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
        echo "    <div class=\"panel panel-default\">
      <div class=\"panel-heading\">
        <h3 class=\"panel-title\"><i class=\"fa fa-pencil\"></i> ";
        // line 24
        echo (isset($context["text_edit"]) ? $context["text_edit"] : null);
        echo "</h3>
      </div>
      <div class=\"panel-body\">
        <form action=\"";
        // line 27
        echo (isset($context["action"]) ? $context["action"] : null);
        echo "\" method=\"post\" enctype=\"multipart/form-data\" id=\"form-theme\" class=\"form-horizontal\">
          <fieldset>
            <legend>";
        // line 29
        echo (isset($context["text_general"]) ? $context["text_general"] : null);
        echo "</legend>
            <div class=\"form-group\">
              <label class=\"col-sm-2 control-label\" for=\"input-directory\"><span data-toggle=\"tooltip\" title=\"";
        // line 31
        echo (isset($context["help_directory"]) ? $context["help_directory"] : null);
        echo "\">";
        echo (isset($context["entry_directory"]) ? $context["entry_directory"] : null);
        echo "</span></label>
              <div class=\"col-sm-10\">
                <select name=\"theme_default_directory\" id=\"input-directory\" class=\"form-control\">
                  ";
        // line 34
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["directories"]) ? $context["directories"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["directory"]) {
            // line 35
            echo "                  ";
            if (($context["directory"] == (isset($context["theme_default_directory"]) ? $context["theme_default_directory"] : null))) {
                // line 36
                echo "                  <option value=\"";
                echo $context["directory"];
                echo "\" selected=\"selected\">";
                echo $context["directory"];
                echo "</option>
                  ";
            } else {
                // line 38
                echo "                  <option value=\"";
                echo $context["directory"];
                echo "\">";
                echo $context["directory"];
                echo "</option>
                  ";
            }
            // line 40
            echo "                  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['directory'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 41
        echo "                </select>
              </div>
            </div>
            <div class=\"form-group\">
              <label class=\"col-sm-2 control-label\" for=\"input-status\">";
        // line 45
        echo (isset($context["entry_status"]) ? $context["entry_status"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <select name=\"theme_default_status\" id=\"input-status\" class=\"form-control\">
                  ";
        // line 48
        if ((isset($context["theme_default_status"]) ? $context["theme_default_status"] : null)) {
            // line 49
            echo "                  <option value=\"1\" selected=\"selected\">";
            echo (isset($context["text_enabled"]) ? $context["text_enabled"] : null);
            echo "</option>
                  <option value=\"0\">";
            // line 50
            echo (isset($context["text_disabled"]) ? $context["text_disabled"] : null);
            echo "</option>
                  ";
        } else {
            // line 52
            echo "                  <option value=\"1\">";
            echo (isset($context["text_enabled"]) ? $context["text_enabled"] : null);
            echo "</option>
                  <option value=\"0\" selected=\"selected\">";
            // line 53
            echo (isset($context["text_disabled"]) ? $context["text_disabled"] : null);
            echo "</option>
                  ";
        }
        // line 55
        echo "                </select>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>";
        // line 60
        echo (isset($context["text_product"]) ? $context["text_product"] : null);
        echo "</legend>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-catalog-limit\"><span data-toggle=\"tooltip\" title=\"";
        // line 62
        echo (isset($context["help_product_limit"]) ? $context["help_product_limit"] : null);
        echo "\">";
        echo (isset($context["entry_product_limit"]) ? $context["entry_product_limit"] : null);
        echo "</span></label>
              <div class=\"col-sm-10\">
                <input type=\"text\" name=\"theme_default_product_limit\" value=\"";
        // line 64
        echo (isset($context["theme_default_product_limit"]) ? $context["theme_default_product_limit"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_product_limit"]) ? $context["entry_product_limit"] : null);
        echo "\" id=\"input-catalog-limit\" class=\"form-control\" />
                ";
        // line 65
        if ((isset($context["error_product_limit"]) ? $context["error_product_limit"] : null)) {
            // line 66
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_product_limit"]) ? $context["error_product_limit"] : null);
            echo "</div>
                ";
        }
        // line 68
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-description-limit\"><span data-toggle=\"tooltip\" title=\"";
        // line 71
        echo (isset($context["help_product_description_length"]) ? $context["help_product_description_length"] : null);
        echo "\">";
        echo (isset($context["entry_product_description_length"]) ? $context["entry_product_description_length"] : null);
        echo "</span></label>
              <div class=\"col-sm-10\">
                <input type=\"text\" name=\"theme_default_product_description_length\" value=\"";
        // line 73
        echo (isset($context["theme_default_product_description_length"]) ? $context["theme_default_product_description_length"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_product_description_length"]) ? $context["entry_product_description_length"] : null);
        echo "\" id=\"input-description-limit\" class=\"form-control\" />
                ";
        // line 74
        if ((isset($context["error_product_description_length"]) ? $context["error_product_description_length"] : null)) {
            // line 75
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_product_description_length"]) ? $context["error_product_description_length"] : null);
            echo "</div>
                ";
        }
        // line 77
        echo "              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>";
        // line 81
        echo (isset($context["text_image"]) ? $context["text_image"] : null);
        echo "</legend>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-category-width\">";
        // line 83
        echo (isset($context["entry_image_category"]) ? $context["entry_image_category"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_category_width\" value=\"";
        // line 87
        echo (isset($context["theme_default_image_category_width"]) ? $context["theme_default_image_category_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-category-width\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_category_height\" value=\"";
        // line 90
        echo (isset($context["theme_default_image_category_height"]) ? $context["theme_default_image_category_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 93
        if ((isset($context["error_image_category"]) ? $context["error_image_category"] : null)) {
            // line 94
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_category"]) ? $context["error_image_category"] : null);
            echo "</div>
                ";
        }
        // line 96
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-thumb-width\">";
        // line 99
        echo (isset($context["entry_image_thumb"]) ? $context["entry_image_thumb"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_thumb_width\" value=\"";
        // line 103
        echo (isset($context["theme_default_image_thumb_width"]) ? $context["theme_default_image_thumb_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-thumb-width\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_thumb_height\" value=\"";
        // line 106
        echo (isset($context["theme_default_image_thumb_height"]) ? $context["theme_default_image_thumb_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 109
        if ((isset($context["error_image_thumb"]) ? $context["error_image_thumb"] : null)) {
            // line 110
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_thumb"]) ? $context["error_image_thumb"] : null);
            echo "</div>
                ";
        }
        // line 112
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-popup-width\">";
        // line 115
        echo (isset($context["entry_image_popup"]) ? $context["entry_image_popup"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_popup_width\" value=\"";
        // line 119
        echo (isset($context["theme_default_image_popup_width"]) ? $context["theme_default_image_popup_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-popup-width\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_popup_height\" value=\"";
        // line 122
        echo (isset($context["theme_default_image_popup_height"]) ? $context["theme_default_image_popup_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 125
        if ((isset($context["error_image_popup"]) ? $context["error_image_popup"] : null)) {
            // line 126
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_popup"]) ? $context["error_image_popup"] : null);
            echo "</div>
                ";
        }
        // line 128
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-product-width\">";
        // line 131
        echo (isset($context["entry_image_product"]) ? $context["entry_image_product"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_product_width\" value=\"";
        // line 135
        echo (isset($context["theme_default_image_product_width"]) ? $context["theme_default_image_product_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-product-width\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_product_height\" value=\"";
        // line 138
        echo (isset($context["theme_default_image_product_height"]) ? $context["theme_default_image_product_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 141
        if ((isset($context["error_image_product"]) ? $context["error_image_product"] : null)) {
            // line 142
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_product"]) ? $context["error_image_product"] : null);
            echo "</div>
                ";
        }
        // line 144
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-additional-width\">";
        // line 147
        echo (isset($context["entry_image_additional"]) ? $context["entry_image_additional"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_additional_width\" value=\"";
        // line 151
        echo (isset($context["theme_default_image_additional_width"]) ? $context["theme_default_image_additional_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-additional-width\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_additional_height\" value=\"";
        // line 154
        echo (isset($context["theme_default_image_additional_height"]) ? $context["theme_default_image_additional_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 157
        if ((isset($context["error_image_additional"]) ? $context["error_image_additional"] : null)) {
            // line 158
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_additional"]) ? $context["error_image_additional"] : null);
            echo "</div>
                ";
        }
        // line 160
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-related\">";
        // line 163
        echo (isset($context["entry_image_related"]) ? $context["entry_image_related"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_related_width\" value=\"";
        // line 167
        echo (isset($context["theme_default_image_related_width"]) ? $context["theme_default_image_related_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-related\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_related_height\" value=\"";
        // line 170
        echo (isset($context["theme_default_image_related_height"]) ? $context["theme_default_image_related_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 173
        if ((isset($context["error_image_related"]) ? $context["error_image_related"] : null)) {
            // line 174
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_related"]) ? $context["error_image_related"] : null);
            echo "</div>
                ";
        }
        // line 176
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-compare\">";
        // line 179
        echo (isset($context["entry_image_compare"]) ? $context["entry_image_compare"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_compare_width\" value=\"";
        // line 183
        echo (isset($context["theme_default_image_compare_width"]) ? $context["theme_default_image_compare_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-compare\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_compare_height\" value=\"";
        // line 186
        echo (isset($context["theme_default_image_compare_height"]) ? $context["theme_default_image_compare_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 189
        if ((isset($context["error_image_compare"]) ? $context["error_image_compare"] : null)) {
            // line 190
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_compare"]) ? $context["error_image_compare"] : null);
            echo "</div>
                ";
        }
        // line 192
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-wishlist\">";
        // line 195
        echo (isset($context["entry_image_wishlist"]) ? $context["entry_image_wishlist"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_wishlist_width\" value=\"";
        // line 199
        echo (isset($context["theme_default_image_wishlist_width"]) ? $context["theme_default_image_wishlist_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-wishlist\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_wishlist_height\" value=\"";
        // line 202
        echo (isset($context["theme_default_image_wishlist_height"]) ? $context["theme_default_image_wishlist_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 205
        if ((isset($context["error_image_wishlist"]) ? $context["error_image_wishlist"] : null)) {
            // line 206
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_wishlist"]) ? $context["error_image_wishlist"] : null);
            echo "</div>
                ";
        }
        // line 208
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-cart\">";
        // line 211
        echo (isset($context["entry_image_cart"]) ? $context["entry_image_cart"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_cart_width\" value=\"";
        // line 215
        echo (isset($context["theme_default_image_cart_width"]) ? $context["theme_default_image_cart_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-cart\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_cart_height\" value=\"";
        // line 218
        echo (isset($context["theme_default_image_cart_height"]) ? $context["theme_default_image_cart_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 221
        if ((isset($context["error_image_cart"]) ? $context["error_image_cart"] : null)) {
            // line 222
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_cart"]) ? $context["error_image_cart"] : null);
            echo "</div>
                ";
        }
        // line 224
        echo "              </div>
            </div>
            <div class=\"form-group required\">
              <label class=\"col-sm-2 control-label\" for=\"input-image-location\">";
        // line 227
        echo (isset($context["entry_image_location"]) ? $context["entry_image_location"] : null);
        echo "</label>
              <div class=\"col-sm-10\">
                <div class=\"row\">
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_location_width\" value=\"";
        // line 231
        echo (isset($context["theme_default_image_location_width"]) ? $context["theme_default_image_location_width"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_width"]) ? $context["entry_width"] : null);
        echo "\" id=\"input-image-location\" class=\"form-control\" />
                  </div>
                  <div class=\"col-sm-6\">
                    <input type=\"text\" name=\"theme_default_image_location_height\" value=\"";
        // line 234
        echo (isset($context["theme_default_image_location_height"]) ? $context["theme_default_image_location_height"] : null);
        echo "\" placeholder=\"";
        echo (isset($context["entry_height"]) ? $context["entry_height"] : null);
        echo "\" class=\"form-control\" />
                  </div>
                </div>
                ";
        // line 237
        if ((isset($context["error_image_location"]) ? $context["error_image_location"] : null)) {
            // line 238
            echo "                <div class=\"text-danger\">";
            echo (isset($context["error_image_location"]) ? $context["error_image_location"] : null);
            echo "</div>
                ";
        }
        // line 240
        echo "              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
";
        // line 248
        echo (isset($context["footer"]) ? $context["footer"] : null);
    }

    public function getTemplateName()
    {
        return "extension/theme/default.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  600 => 248,  590 => 240,  584 => 238,  582 => 237,  574 => 234,  566 => 231,  559 => 227,  554 => 224,  548 => 222,  546 => 221,  538 => 218,  530 => 215,  523 => 211,  518 => 208,  512 => 206,  510 => 205,  502 => 202,  494 => 199,  487 => 195,  482 => 192,  476 => 190,  474 => 189,  466 => 186,  458 => 183,  451 => 179,  446 => 176,  440 => 174,  438 => 173,  430 => 170,  422 => 167,  415 => 163,  410 => 160,  404 => 158,  402 => 157,  394 => 154,  386 => 151,  379 => 147,  374 => 144,  368 => 142,  366 => 141,  358 => 138,  350 => 135,  343 => 131,  338 => 128,  332 => 126,  330 => 125,  322 => 122,  314 => 119,  307 => 115,  302 => 112,  296 => 110,  294 => 109,  286 => 106,  278 => 103,  271 => 99,  266 => 96,  260 => 94,  258 => 93,  250 => 90,  242 => 87,  235 => 83,  230 => 81,  224 => 77,  218 => 75,  216 => 74,  210 => 73,  203 => 71,  198 => 68,  192 => 66,  190 => 65,  184 => 64,  177 => 62,  172 => 60,  165 => 55,  160 => 53,  155 => 52,  150 => 50,  145 => 49,  143 => 48,  137 => 45,  131 => 41,  125 => 40,  117 => 38,  109 => 36,  106 => 35,  102 => 34,  94 => 31,  89 => 29,  84 => 27,  78 => 24,  74 => 22,  66 => 18,  64 => 17,  58 => 13,  47 => 11,  43 => 10,  38 => 8,  32 => 7,  28 => 6,  19 => 1,);
    }
}
/* {{ header }}{{ column_left }}*/
/* <div id="content">*/
/*   <div class="page-header">*/
/*     <div class="container-fluid">*/
/*       <div class="pull-right">*/
/*         <button type="submit" form="form-theme" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa fa-save"></i></button>*/
/*         <a href="{{ cancel }}" data-toggle="tooltip" title="{{ button_cancel }}" class="btn btn-default"><i class="fa fa-reply"></i></a></div>*/
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
/*     <div class="panel panel-default">*/
/*       <div class="panel-heading">*/
/*         <h3 class="panel-title"><i class="fa fa-pencil"></i> {{ text_edit }}</h3>*/
/*       </div>*/
/*       <div class="panel-body">*/
/*         <form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-theme" class="form-horizontal">*/
/*           <fieldset>*/
/*             <legend>{{ text_general }}</legend>*/
/*             <div class="form-group">*/
/*               <label class="col-sm-2 control-label" for="input-directory"><span data-toggle="tooltip" title="{{ help_directory }}">{{ entry_directory }}</span></label>*/
/*               <div class="col-sm-10">*/
/*                 <select name="theme_default_directory" id="input-directory" class="form-control">*/
/*                   {% for directory in directories %}*/
/*                   {% if directory == theme_default_directory %}*/
/*                   <option value="{{ directory }}" selected="selected">{{ directory }}</option>*/
/*                   {% else %}*/
/*                   <option value="{{ directory }}">{{ directory }}</option>*/
/*                   {% endif %}*/
/*                   {% endfor %}*/
/*                 </select>*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group">*/
/*               <label class="col-sm-2 control-label" for="input-status">{{ entry_status }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <select name="theme_default_status" id="input-status" class="form-control">*/
/*                   {% if theme_default_status %}*/
/*                   <option value="1" selected="selected">{{ text_enabled }}</option>*/
/*                   <option value="0">{{ text_disabled }}</option>*/
/*                   {% else %}*/
/*                   <option value="1">{{ text_enabled }}</option>*/
/*                   <option value="0" selected="selected">{{ text_disabled }}</option>*/
/*                   {% endif %}*/
/*                 </select>*/
/*               </div>*/
/*             </div>*/
/*           </fieldset>*/
/*           <fieldset>*/
/*             <legend>{{ text_product }}</legend>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-catalog-limit"><span data-toggle="tooltip" title="{{ help_product_limit }}">{{ entry_product_limit }}</span></label>*/
/*               <div class="col-sm-10">*/
/*                 <input type="text" name="theme_default_product_limit" value="{{ theme_default_product_limit }}" placeholder="{{ entry_product_limit }}" id="input-catalog-limit" class="form-control" />*/
/*                 {% if error_product_limit %}*/
/*                 <div class="text-danger">{{ error_product_limit }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-description-limit"><span data-toggle="tooltip" title="{{ help_product_description_length }}">{{ entry_product_description_length }}</span></label>*/
/*               <div class="col-sm-10">*/
/*                 <input type="text" name="theme_default_product_description_length" value="{{ theme_default_product_description_length }}" placeholder="{{ entry_product_description_length }}" id="input-description-limit" class="form-control" />*/
/*                 {% if error_product_description_length %}*/
/*                 <div class="text-danger">{{ error_product_description_length }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*           </fieldset>*/
/*           <fieldset>*/
/*             <legend>{{ text_image }}</legend>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-category-width">{{ entry_image_category }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_category_width" value="{{ theme_default_image_category_width }}" placeholder="{{ entry_width }}" id="input-image-category-width" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_category_height" value="{{ theme_default_image_category_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_category %}*/
/*                 <div class="text-danger">{{ error_image_category }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-thumb-width">{{ entry_image_thumb }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_thumb_width" value="{{ theme_default_image_thumb_width }}" placeholder="{{ entry_width }}" id="input-image-thumb-width" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_thumb_height" value="{{ theme_default_image_thumb_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_thumb %}*/
/*                 <div class="text-danger">{{ error_image_thumb }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-popup-width">{{ entry_image_popup }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_popup_width" value="{{ theme_default_image_popup_width }}" placeholder="{{ entry_width }}" id="input-image-popup-width" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_popup_height" value="{{ theme_default_image_popup_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_popup %}*/
/*                 <div class="text-danger">{{ error_image_popup }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-product-width">{{ entry_image_product }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_product_width" value="{{ theme_default_image_product_width }}" placeholder="{{ entry_width }}" id="input-image-product-width" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_product_height" value="{{ theme_default_image_product_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_product %}*/
/*                 <div class="text-danger">{{ error_image_product }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-additional-width">{{ entry_image_additional }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_additional_width" value="{{ theme_default_image_additional_width }}" placeholder="{{ entry_width }}" id="input-image-additional-width" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_additional_height" value="{{ theme_default_image_additional_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_additional %}*/
/*                 <div class="text-danger">{{ error_image_additional }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-related">{{ entry_image_related }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_related_width" value="{{ theme_default_image_related_width }}" placeholder="{{ entry_width }}" id="input-image-related" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_related_height" value="{{ theme_default_image_related_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_related %}*/
/*                 <div class="text-danger">{{ error_image_related }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-compare">{{ entry_image_compare }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_compare_width" value="{{ theme_default_image_compare_width }}" placeholder="{{ entry_width }}" id="input-image-compare" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_compare_height" value="{{ theme_default_image_compare_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_compare %}*/
/*                 <div class="text-danger">{{ error_image_compare }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-wishlist">{{ entry_image_wishlist }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_wishlist_width" value="{{ theme_default_image_wishlist_width }}" placeholder="{{ entry_width }}" id="input-image-wishlist" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_wishlist_height" value="{{ theme_default_image_wishlist_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_wishlist %}*/
/*                 <div class="text-danger">{{ error_image_wishlist }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-cart">{{ entry_image_cart }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_cart_width" value="{{ theme_default_image_cart_width }}" placeholder="{{ entry_width }}" id="input-image-cart" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_cart_height" value="{{ theme_default_image_cart_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_cart %}*/
/*                 <div class="text-danger">{{ error_image_cart }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*             <div class="form-group required">*/
/*               <label class="col-sm-2 control-label" for="input-image-location">{{ entry_image_location }}</label>*/
/*               <div class="col-sm-10">*/
/*                 <div class="row">*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_location_width" value="{{ theme_default_image_location_width }}" placeholder="{{ entry_width }}" id="input-image-location" class="form-control" />*/
/*                   </div>*/
/*                   <div class="col-sm-6">*/
/*                     <input type="text" name="theme_default_image_location_height" value="{{ theme_default_image_location_height }}" placeholder="{{ entry_height }}" class="form-control" />*/
/*                   </div>*/
/*                 </div>*/
/*                 {% if error_image_location %}*/
/*                 <div class="text-danger">{{ error_image_location }}</div>*/
/*                 {% endif %}*/
/*               </div>*/
/*             </div>*/
/*           </fieldset>*/
/*         </form>*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/* </div>*/
/* {{ footer }}*/
