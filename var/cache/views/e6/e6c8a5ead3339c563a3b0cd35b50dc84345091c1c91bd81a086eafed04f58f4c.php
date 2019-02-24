<?php

/* admin.html.twig */
class __TwigTemplate_4f1190e35d3a926b5141ff5679ea29978bdbd130aa67c56d79c0edd34f8a23b4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("base.html.twig", "admin.html.twig", 1);
        $this->blocks = array(
            'links' => array($this, 'block_links'),
            'body' => array($this, 'block_body'),
            'admin_content' => array($this, 'block_admin_content'),
            'scripts' => array($this, 'block_scripts'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_links($context, array $blocks = array())
    {
        // line 4
        echo "    <!-- Bootstrap Core CSS -->
    <link href=\"/dependencies/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">

    <link href=\"/dependencies/bootstrap/css/datepicker.css\" rel=\"stylesheet\">

    <!-- MetisMenu CSS -->
    <link href=\"/dependencies/metisMenu/metisMenu.min.css\" rel=\"stylesheet\">

    <!-- DataTables CSS -->
    <link href=\"/dependencies/datatables-plugins/dataTables.bootstrap.css\" rel=\"stylesheet\">

    <!-- DataTables Responsive CSS -->
    <link href=\"/dependencies/datatables-responsive/dataTables.responsive.css\" rel=\"stylesheet\">

    <!-- Custom CSS -->
    <link href=\"/dist/css/sb-admin-2.min.css\" rel=\"stylesheet\">

    <!-- stock-manager css -->
    <link href=\"/dist/css/stock-manager.css\" rel=\"stylesheet\">
";
    }

    // line 25
    public function block_body($context, array $blocks = array())
    {
        // line 26
        echo "    <!-- Custom Fonts -->
    <div id=\"wrapper\">
        <nav class=\"navbar navbar-default navbar-static-top\" role=\"navigation\" style=\"margin-bottom: 0\">
            <div class=\"navbar-header\">
                <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\">
                    <span class=\"sr-only\">Toggle navigation</span>
                    <span class=\"icon-bar\"></span>
                    <span class=\"icon-bar\"></span>
                    <span class=\"icon-bar\"></span>
                </button>
                <a class=\"navbar-brand\" href=\"/products\"><img src=\"/img/logo.png\"></a>
            </div>
            <!-- /.navbar-header -->

            <ul class=\"nav navbar-top-links navbar-right\">
                <!-- /.dropdown -->
                <li class=\"dropdown\">
                    <a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">
                        <i class=\"fa fa-user fa-fw\"></i> <i class=\"fa fa-caret-down\"></i>
                    </a>
                    <ul class=\"dropdown-menu dropdown-user\">
                        <li><a href=\"#\" data-toggle=\"modal\" data-target=\"#aboutModal\"><i class=\"fa fa-user fa-fw\"></i> ";
        // line 47
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "about", array()), "html", null, true);
        echo "</a>
                        </li>
                        <!--li><a href=\"#\"><i class=\"fa fa-gear fa-fw\"></i> Settings</a>
                        </li-->
                        <li class=\"divider\"></li>
                        <li><a href=\"/login/destroy\"><i class=\"fa fa-sign-out fa-fw\"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class=\"navbar-default sidebar\" role=\"navigation\">
                <div class=\"sidebar-nav navbar-collapse\">
                    <ul class=\"nav\" id=\"side-menu\">
                        <li>
                            <a href=\"/graphs\"><i class=\"fa fa-dashboard fa-fw\"></i> ";
        // line 65
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "dashboard", array()), "html", null, true);
        echo "</a>
                        </li>
                        <li>
                            <a href=\"/products\"><i class=\"fa fa-bar-chart-o fa-fw\"></i> ";
        // line 68
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "products", array()), "html", null, true);
        echo "</a>
                        </li>
                        <li>
                            <a href=\"/providers\"><i class=\"fa fa-table fa-fw\"></i> ";
        // line 71
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "providers", array()), "html", null, true);
        echo "</a>
                        </li>
                        <li>
                            <a href=\"/categories\"><i class=\"fa fa-sitemap fa-fw\"></i> ";
        // line 74
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "categories", array()), "html", null, true);
        echo "</a>
                        </li>
                        <li>
                            <a href=\"/productbundles\"><i class=\"fa fa-reorder fa-fw\"></i> ";
        // line 77
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "productbundles", array()), "html", null, true);
        echo "</a>
                        </li>
                        <li>
                            <a href=\"/discounts\"><i class=\"fa fa-shopping-cart fa-fw\"></i> ";
        // line 80
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "discounts", array()), "html", null, true);
        echo "</a>
                        </li
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id=\"page-wrapper\">
            <section>
                ";
        // line 91
        $this->displayBlock('admin_content', $context, $blocks);
        // line 92
        echo "            </section>
        </div>

        <!-- Modal -->
        <div class=\"modal fade\" id=\"aboutModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                    </div>
                    <div class=\"modal-body\">
                        <p>";
        // line 103
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "about_me", array()), "html", null, true);
        echo "</p>
                        <p><a href=\"";
        // line 104
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "linkedin", array()), "html", null, true);
        echo "\">linkedin</a></p>
                    </div>

                    <div class=\"modal-footer\">
                        <button type=\"submit\" class=\"btn btn-default\" data-dismiss=\"modal\">";
        // line 108
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
";
    }

    // line 91
    public function block_admin_content($context, array $blocks = array())
    {
    }

    // line 119
    public function block_scripts($context, array $blocks = array())
    {
        // line 120
        echo "    <link href=\"/dependencies/font-awesome/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\">
    <!-- jQuery -->
    <script src=\"/dependencies/jquery/jquery.min.js\"></script>
    <script src=\"/dependencies/moment/moment.js\"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src=\"/dependencies/bootstrap/js/bootstrap.min.js\"></script>
    <script src=\"/dependencies/bootstrap/js/datepicker.js\"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src=\"/dependencies/metisMenu/metisMenu.min.js\"></script>

    <!-- DataTables JavaScript -->
    <script src=\"/dependencies/datatables/js/jquery.dataTables.min.js\"></script>
    <script src=\"/dependencies/datatables-plugins/dataTables.bootstrap.min.js\"></script>
    <script src=\"/dependencies/datatables-responsive/dataTables.responsive.js\"></script>

    <!-- Custom Theme JavaScript -->
    <script src=\"/dist/js/sb-admin-2.js\"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        \$(document).ready(function() {
            \$('#dataTables-example').DataTable({
                responsive: true,
                \"scrollCollapse\": true,
                \"paging\":         false,
                language: {
                    \"sProcessing\": \"";
        // line 148
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "processing", array()), "html", null, true);
        echo "\",
                    \"sLengthMenu\": \"";
        // line 149
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "showing", array()), "html", null, true);
        echo "\",
                    \"sZeroRecords\": \"";
        // line 150
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "zero_records", array()), "html", null, true);
        echo "\",
                    \"sInfo\": \"";
        // line 151
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "info", array()), "html", null, true);
        echo "\",
                    \"sInfoEmpty\": \"";
        // line 152
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "info_empty", array()), "html", null, true);
        echo "\",
                    \"sInfoFiltered\": \"";
        // line 153
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "total_filtered", array()), "html", null, true);
        echo "\",
                    \"sSearch\": \"";
        // line 154
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "search", array()), "html", null, true);
        echo "\",
                    \"oPaginate\": {
                        \"sFirst\":    \"";
        // line 156
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "first", array()), "html", null, true);
        echo "\",
                        \"sLast\":     \"";
        // line 157
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "last", array()), "html", null, true);
        echo "\",
                        \"sNext\":     \"";
        // line 158
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "next", array()), "html", null, true);
        echo "\",
                        \"sPrevious\": \"";
        // line 159
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["locale"] ?? null), "product", array()), "table", array()), "previous", array()), "html", null, true);
        echo "\"
                    },
                    \"sUrl\": \"\"
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "admin.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  267 => 159,  263 => 158,  259 => 157,  255 => 156,  250 => 154,  246 => 153,  242 => 152,  238 => 151,  234 => 150,  230 => 149,  226 => 148,  196 => 120,  193 => 119,  188 => 91,  174 => 108,  167 => 104,  163 => 103,  150 => 92,  148 => 91,  134 => 80,  128 => 77,  122 => 74,  116 => 71,  110 => 68,  104 => 65,  83 => 47,  60 => 26,  57 => 25,  34 => 4,  31 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin.html.twig", "/var/www/html/src/Views/admin.html.twig");
    }
}
