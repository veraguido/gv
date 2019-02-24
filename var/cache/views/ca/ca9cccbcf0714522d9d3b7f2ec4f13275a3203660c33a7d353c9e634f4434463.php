<?php

/* /Products/index.html.twig */
class __TwigTemplate_d8195a155794619190ae93a48fd1799ea4693286020df9c634ea0d1db696c398 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("admin.html.twig", "/Products/index.html.twig", 1);
        $this->blocks = array(
            'admin_content' => array($this, 'block_admin_content'),
            'scripts' => array($this, 'block_scripts'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "admin.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_admin_content($context, array $blocks = array())
    {
        // line 4
        echo "    <div class=\"row\">
        <div class=\"col-lg-12\">
            <h3>";
        // line 6
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "products", array()), "html", null, true);
        echo "</h3><br/>
            <div class=\"panel panel-default\">

                <div class=\"panel-heading\">
                    <button id=\"add-product\" class=\"btn btn-success btn-sm\" data-toggle=\"modal\" data-target=\"#myModal\">
                        <i>";
        // line 11
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "add_new", array()), "html", null, true);
        echo "</i>
                    </button>

                </div>
                <!-- /.panel-heading -->

                <div class=\"panel-body\">
                    <div class=\"table-responsive\">
                        <table width=\"100%\" class=\"table table-striped table-bordered table-hover\" id=\"dataTables-example\">
                            <thead>
                            <tr>
                                <th>Mov.</th>
                                <th>";
        // line 23
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "controls", array()), "html", null, true);
        echo "</th>
                                <th>";
        // line 24
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "code", array()), "html", null, true);
        echo "</th>
                                <th>";
        // line 25
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "name", array()), "html", null, true);
        echo "</th>
                                <th>";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "description", array()), "html", null, true);
        echo "</th>
                                <th>";
        // line 27
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "stock", array()), "html", null, true);
        echo "</th>
                                <th>";
        // line 28
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "price", array()), "html", null, true);
        echo "</th>
                                <th>";
        // line 29
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "cost", array()), "html", null, true);
        echo "</th>
                                <th>";
        // line 30
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "size", array()), "html", null, true);
        echo "</th>
                                <th>";
        // line 31
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "color", array()), "html", null, true);
        echo "</th>
                                <th>";
        // line 32
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "provider", array()), "html", null, true);
        echo "</th>
                            </tr>
                            </thead>
                            <tbody>
                            ";
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["products"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 37
            echo "                            <tr>
                                <td>
                                    <button type=\"button\"
                                            class=\"btn btn-success btn-circle view-button\"
                                            data-id=\"";
            // line 41
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "id", array()), "html", null, true);
            echo "\"
                                            data-toggle=\"modal\" data-target=\"#viewMovementsModal\"
                                            title=\"";
            // line 43
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "see", array()), "html", null, true);
            echo "\">
                                        <i class=\"fa fa-search\"></i>
                                    </button>
                                    <button type=\"button\"
                                            class=\"btn btn-info btn-circle increase-stock-button\"
                                            data-id=\"";
            // line 48
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "id", array()), "html", null, true);
            echo "\" data-toggle=\"modal\" data-target=\"#movementModal\"
                                            title=\"";
            // line 49
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "new_movement", array()), "html", null, true);
            echo "\">
                                        <i class=\"fa fa-arrows-v\"></i>
                                    </button>
                                </td>
                                <td>
                                    <button type=\"button\"
                                            class=\"btn btn-default btn-circle discount-button\"
                                            data-id=\"";
            // line 56
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "id", array()), "html", null, true);
            echo "\" data-toggle=\"modal\" data-target=\"#addDiscountModal\"
                                            title=\"";
            // line 57
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "discounts", array()), "html", null, true);
            echo "\">
                                        <i class=\"fa fa-shopping-cart\"></i>
                                    </button>
                                    <button type=\"button\"
                                            class=\"btn btn-default btn-circle category-button\"
                                            data-id=\"";
            // line 62
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "id", array()), "html", null, true);
            echo "\" data-toggle=\"modal\" data-target=\"#chooseCategoryModal\"
                                            title=\"";
            // line 63
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "categories", array()), "html", null, true);
            echo "\">
                                        <i class=\"fa fa-sitemap\"></i>
                                    </button>
                                    <button type=\"button\"
                                            class=\"btn btn-warning btn-circle edit-button\"
                                            data-id=\"";
            // line 68
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "id", array()), "html", null, true);
            echo "\" data-toggle=\"modal\" data-target=\"#editProductModal\"
                                            title=\"";
            // line 69
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "edit_label", array()), "html", null, true);
            echo "\"><i class=\"fa fa-pencil-square-o\"></i>
                                    </button>
                                    <button type=\"button\"
                                            class=\"btn btn-danger btn-circle delete-button\"
                                            data-id=\"";
            // line 73
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "id", array()), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "delete_label", array()), "html", null, true);
            echo "\">
                                        <i class=\"fa fa-trash-o\"></i>
                                    </button>
                                </td>
                                <td>";
            // line 77
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "code", array()), "html", null, true);
            echo "</td>
                                <td>";
            // line 78
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "name", array()), "html", null, true);
            echo "</td>
                                <td>";
            // line 79
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "description", array()), "html", null, true);
            echo "</td>
                                <td>";
            // line 80
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "stock", array()), "html", null, true);
            echo "</td>
                                <td>";
            // line 81
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "price", array()), "html", null, true);
            echo "</td>
                                <td>";
            // line 82
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "cost", array()), "html", null, true);
            echo "</td>
                                <td>";
            // line 83
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "size", array()), "html", null, true);
            echo "</td>
                                <td>";
            // line 84
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "color", array()), "html", null, true);
            echo "</td>
                                <td>";
            // line 85
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), $context["product"], "provider", array()), "name", array()), "html", null, true);
            echo "</td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 88
        echo "                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>

                <div class=\"panel-body\">
                    <!-- Button trigger modal -->
                    <!-- Modal add product -->
                    <div class=\"modal fade\" id=\"myModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                        <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <div class=\"modal-header\">
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                                    <h4 class=\"modal-title\" id=\"myModalLabel\">";
        // line 102
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "add_new", array()), "html", null, true);
        echo "</h4>
                                </div>
                                <div class=\"modal-body\">
                                    <form role=\"form\" method=\"POST\" id=\"new-product-form\" action=\"/products/create\">
                                        <div class=\"form-group\">
                                            <label>";
        // line 107
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "code", array()), "html", null, true);
        echo "</label>
                                            <input name=\"code\" autocomplete=\"off\" required class=\"form-control\"
                                                   placeholder=\"";
        // line 109
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "code_help", array()), "html", null, true);
        echo "\">
                                        </div>

                                        <div class=\"form-group\">
                                            <label>";
        // line 113
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "name", array()), "html", null, true);
        echo "</label>
                                            <input name=\"name\" autocomplete=\"off\" required class=\"form-control\"
                                                   placeholder=\"";
        // line 115
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "name_help", array()), "html", null, true);
        echo "\">
                                        </div>

                                        <div class=\"form-group\">
                                            <label>";
        // line 119
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "description", array()), "html", null, true);
        echo "</label>
                                            <input name=\"description\" autocomplete=\"off\" class=\"form-control\" />
                                        </div>

                                        <div class=\"form-group\">
                                            <label>";
        // line 124
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "stock", array()), "html", null, true);
        echo "</label>
                                            <input name=\"stock\" required autocomplete=\"off\" class=\"form-control\" type=\"number\"
                                                   placeholder=\"";
        // line 126
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "stock_help", array()), "html", null, true);
        echo "\">
                                        </div>

                                        <div class=\"form-group\">
                                            <label>";
        // line 130
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "price", array()), "html", null, true);
        echo "</label>
                                            <input name=\"price\" class=\"form-control\" autocomplete=\"off\" required type=\"number\" step=\"0.01\">
                                        </div>

                                        <div class=\"form-group\">
                                            <label>";
        // line 135
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "cost", array()), "html", null, true);
        echo "</label>
                                            <input name=\"cost\" class=\"form-control\" required autocomplete=\"off\" type=\"number\" step=\"0.01\">
                                        </div>

                                        <div class=\"form-group\">
                                            <label>";
        // line 140
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "color", array()), "html", null, true);
        echo "</label>
                                            <input name=\"color\" required class=\"form-control\">
                                        </div>

                                        <div class=\"form-group\">
                                            <label>";
        // line 145
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "size", array()), "html", null, true);
        echo "</label>
                                            <input name=\"size\" class=\"form-control\" type=\"number\">
                                        </div>

                                        <div class=\"form-group\">
                                            <div class=\"checkbox\">
                                                <label>";
        // line 151
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "visible", array()), "html", null, true);
        echo "</label>
                                                <select name=\"visible\" id=\"visible\" class=\"form-control\">
                                                    <option value=\"true\" selected>Si</option>
                                                    <option value=\"false\">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class=\"form-group\">
                                            <label>";
        // line 159
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "provider", array()), "html", null, true);
        echo "</label>
                                            <select name=\"provider_id\" id=\"provider-list\" class=\"form-control\">
                                            </select>
                                        </div>
                                        <fieldset>
                                            <button type=\"reset\" class=\"btn btn-default\">";
        // line 164
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "reset", array()), "html", null, true);
        echo "</button>
                                            <button type=\"submit\" class=\"btn btn-primary\">";
        // line 165
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "save", array()), "html", null, true);
        echo "</button>
                                        </fieldset>
                                    </form>
                                </div>

                                <div class=\"modal-footer\">
                                    <button type=\"submit\" class=\"btn btn-default\" data-dismiss=\"modal\">";
        // line 171
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "close", array()), "html", null, true);
        echo "</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Modal edit product -->
                    <div class=\"modal fade\" id=\"editProductModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                        <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <div class=\"modal-header\">
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                                    <h4 class=\"modal-title\" id=\"myModalLabel\">";
        // line 186
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "edit", array()), "html", null, true);
        echo " - <span id=\"edit-product-name\"></span></h4>
                                </div>
                                <div class=\"modal-body\">
                                    <form role=\"form\" method=\"PUT\" id=\"edit-product-form\" action=\"/products/edit/id/\">

                                        <div class=\"form-group\">
                                            <label>";
        // line 192
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "stock", array()), "html", null, true);
        echo "</label>
                                            <input name=\"stock\" required autocomplete=\"off\" class=\"form-control\" type=\"number\"
                                                   id=\"edit-product-stock\"
                                                   placeholder=\"";
        // line 195
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "stock_help", array()), "html", null, true);
        echo "\">
                                        </div>

                                        <div class=\"form-group\">
                                            <label>";
        // line 199
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "price", array()), "html", null, true);
        echo "</label>
                                            <input name=\"price\" id=\"edit-product-price\" class=\"form-control\"
                                                   autocomplete=\"off\" required type=\"number\" step=\"0.01\">
                                        </div>

                                        <div class=\"form-group\">
                                            <div class=\"checkbox\">
                                                <label>";
        // line 206
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "visible", array()), "html", null, true);
        echo "</label>
                                                <select name=\"visible\" id=\"edit-product-visible\" class=\"form-control\">
                                                    <option id=\"edit-product-visible-true\" value=\"true\" selected>Si</option>
                                                    <option id=\"edit-product-visible-false\"  value=\"false\">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input id=\"edit-product-id\" type=\"hidden\"/>
                                        <fieldset>
                                            <button type=\"reset\"  class=\"btn btn-default\">";
        // line 215
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "reset", array()), "html", null, true);
        echo "</button>
                                            <button type=\"submit\" class=\"btn btn-primary\">";
        // line 216
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "save", array()), "html", null, true);
        echo "</button>
                                        </fieldset>
                                    </form>
                                </div>

                                <div class=\"modal-footer\">
                                    <button type=\"submit\" class=\"btn btn-default\" data-dismiss=\"modal\">";
        // line 222
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "close", array()), "html", null, true);
        echo "</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Modal movements -->
                    <div class=\"modal fade\" id=\"movementModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                        <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <div class=\"modal-header\">
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                                    <h4 class=\"modal-title\" id=\"myModalLabel\">";
        // line 237
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "add_new", array()), "html", null, true);
        echo "</h4>
                                </div>
                                <div class=\"modal-body\">
                                    <form id=\"new-movement-form\">
                                        <div class=\"form-group\">
                                            <label>";
        // line 242
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "instruction", array()), "html", null, true);
        echo "</label>
                                            <div class=\"radio\">
                                                <label>
                                                    <input type=\"radio\" name=\"operationRadios\" id=\"operationRadios1\" data-action=\"increase\" />
                                                    ";
        // line 246
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "increment", array()), "html", null, true);
        echo "
                                                </label>
                                            </div>
                                            <div id=\"radio\">
                                                <label>
                                                    <input type=\"radio\" name=\"operationRadios\" id=\"operationRadios2\" checked data-action=\"decrease\"/>
                                                    ";
        // line 252
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "decrement", array()), "html", null, true);
        echo "
                                                </label>
                                            </div>
                                        </div>
                                        <div class=\"form-group\">
                                            <input type=\"number\" class=\"form-control\" id=\"movement-amount\" name=\"amount\" required placeholder=\"";
        // line 257
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "value_description", array()), "html", null, true);
        echo "\"/>
                                        </div>
                                        <fieldset>
                                            <button type=\"reset\" class=\"btn btn-default\">";
        // line 260
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "reset", array()), "html", null, true);
        echo "</button>
                                            <button type=\"submit\" class=\"btn btn-primary\">";
        // line 261
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "save", array()), "html", null, true);
        echo "</button>
                                        </fieldset>
                                    </form>
                                </div>

                                <div class=\"modal-footer\">
                                    <button type=\"submit\" class=\"btn btn-default\" data-dismiss=\"modal\">";
        // line 267
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "close", array()), "html", null, true);
        echo "</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Modal movements -->
                    <div class=\"modal fade\" id=\"viewMovementsModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                        <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <div class=\"modal-header\">
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                                    <h4 class=\"modal-title\" id=\"myModalLabel\">";
        // line 282
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movements", array()), "html", null, true);
        echo "</h4>
                                </div>
                                <div class=\"modal-body\">
                                    <div class=\"table-responsive\">
                                        <table class=\"table table-striped table-bordered table-hover\" id=\"movements-table\">
                                            <thead>
                                            <tr>
                                                <th>";
        // line 289
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "instruction", array()), "html", null, true);
        echo "</th>
                                                <th>";
        // line 290
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "amount", array()), "html", null, true);
        echo "</th>
                                                <th>";
        // line 291
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "movement", array()), "date", array()), "html", null, true);
        echo "</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <nav aria-label=\"Movement navigation\">
                                        <ul class=\"pagination\" id=\"product-movement-pagination\">
                                            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">Previous</a></li>
                                            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1</a></li>
                                            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
                                            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3</a></li>
                                            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">Next</a></li>
                                        </ul>
                                    </nav>
                                </div>

                                <div class=\"modal-footer\">
                                    <button type=\"submit\" class=\"btn btn-default\" data-dismiss=\"modal\">";
        // line 310
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "close", array()), "html", null, true);
        echo "</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Modal categories -->
                    <div class=\"modal fade\" id=\"chooseCategoryModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                        <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <div class=\"modal-header\">
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                                    <h4 class=\"modal-title\" id=\"myModalLabel\">";
        // line 325
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "choose_category", array()), "html", null, true);
        echo "</h4>
                                </div>
                                <div class=\"modal-body\">
                                    <div id=\"category-buttons\" class=\"btn-group\">

                                    </div>
                                </div>

                                <div class=\"modal-footer\">
                                    <button type=\"submit\" class=\"btn btn-default\" data-dismiss=\"modal\">";
        // line 334
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "close", array()), "html", null, true);
        echo "</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Modal discounts -->
                    <div class=\"modal fade\" id=\"addDiscountModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                        <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <div class=\"modal-header\">
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                                    <h4 class=\"modal-title\" id=\"myModalLabel\">";
        // line 349
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "add_discount", array()), "html", null, true);
        echo "</h4>
                                </div>
                                <div class=\"modal-body\">
                                    <div class=\"table-responsive\">
                                        <table class=\"table table-striped table-bordered table-hover\" id=\"discount-table\">
                                            <thead>
                                            <tr>
                                                <th>";
        // line 356
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "discount", array()), "name", array()), "html", null, true);
        echo "</th>
                                                <th>";
        // line 357
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "discount", array()), "code", array()), "html", null, true);
        echo "</th>
                                                <th>Cont.</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class=\"modal-footer\">
                                    <button type=\"submit\" class=\"btn btn-default\" data-dismiss=\"modal\">";
        // line 368
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "close", array()), "html", null, true);
        echo "</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                </div>
                <!-- .panel-body -->
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
";
    }

    // line 387
    public function block_scripts($context, array $blocks = array())
    {
        // line 388
        echo "    ";
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
    <script src=\"/js/products.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "/Products/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  660 => 388,  657 => 387,  635 => 368,  621 => 357,  617 => 356,  607 => 349,  589 => 334,  577 => 325,  559 => 310,  537 => 291,  533 => 290,  529 => 289,  519 => 282,  501 => 267,  492 => 261,  488 => 260,  482 => 257,  474 => 252,  465 => 246,  458 => 242,  450 => 237,  432 => 222,  423 => 216,  419 => 215,  407 => 206,  397 => 199,  390 => 195,  384 => 192,  375 => 186,  357 => 171,  348 => 165,  344 => 164,  336 => 159,  325 => 151,  316 => 145,  308 => 140,  300 => 135,  292 => 130,  285 => 126,  280 => 124,  272 => 119,  265 => 115,  260 => 113,  253 => 109,  248 => 107,  240 => 102,  224 => 88,  215 => 85,  211 => 84,  207 => 83,  203 => 82,  199 => 81,  195 => 80,  191 => 79,  187 => 78,  183 => 77,  174 => 73,  167 => 69,  163 => 68,  155 => 63,  151 => 62,  143 => 57,  139 => 56,  129 => 49,  125 => 48,  117 => 43,  112 => 41,  106 => 37,  102 => 36,  95 => 32,  91 => 31,  87 => 30,  83 => 29,  79 => 28,  75 => 27,  71 => 26,  67 => 25,  63 => 24,  59 => 23,  44 => 11,  36 => 6,  32 => 4,  29 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "/Products/index.html.twig", "/var/www/html/src/Views/Products/index.html.twig");
    }
}
