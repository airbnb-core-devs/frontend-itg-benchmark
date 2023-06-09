<h4 class="mb-4">Produtos</h4>
<a href="{{route('category.discount.show')}}" class="btn btn-info">Aplicar desconto a uma categoria</a>
<a href="{{route('admin.createProduct')}}" class="btn btn-info">Adicionar um produto</a>
<div id="dashboardProducts"><br>
    <div id="string_filter_div_products"></div>
    <div id="string_filter_name_div_products"></div>
    <div id="number_range_filter_div_products"></div>
</div>
<div style="text-align:center!important;" id="products_table"></div>

    <script type="text/javascript">
    
        var domain = document.location.host;
        if (domain == "www.doomus.com.br" || domain == "doomus.com.br") {
            domain = "https://www.doomus.com.br/public";
        } else {
            domain = "http://localhost:8000";
        }
        var analyticsProducts = {!! $dadosChart['products'] !!};
        google.charts.load('current', {'packages':['table', 'controls']});
        google.charts.setOnLoadCallback(drawTable);
        function drawTable() {
            var data = new google.visualization.arrayToDataTable(analyticsProducts);
            data.addColumn('string', 'Editar');
            var dashboard = new google.visualization.Dashboard(document.querySelector('#dashboardProducts'));
            function confirmDelete(){
                event.preventDefault();
                            if(confirm("Você tem certeza disso?")){window.location.href = "/admin/product/analytics.id/destroy"}
            }
            console.log(data);
            for(var i = 0; i < data.getNumberOfRows(); i++){
                var product_id = analyticsProducts[i+1][0];
                data.setCell(i, 5, "<a href=" + domain + "/admin/product/" + product_id + "/edit" + "><i class='fas fa-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;"
                + "<a href=" + domain + "/admin/product/" + product_id + "/destroy" + "><i class='fas fa-trash-alt'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;"
                + "<a href=" + domain + "/admin/product/" + product_id + "/desconto" + " class='btn btn-info btn-sm'>Aplicar desconto</a>");
            }
            var stringFilterProducts = new google.visualization.ControlWrapper({
                controlType: 'StringFilter',
                containerId: 'string_filter_div_products',
                options: {
                    filterColumnIndex: 0
                }
            });
            
            var stringFilterNameProducts = new google.visualization.ControlWrapper({
                controlType: 'StringFilter',
                containerId: 'string_filter_name_div_products',
                options: {
                    filterColumnIndex: 1
                }
            });
            var numberRangeFilterProducts = new google.visualization.ControlWrapper({
                controlType: 'NumberRangeFilter',
                containerId: 'number_range_filter_div_products',
                options: {
                    filterColumnIndex: 3,
                    minValue: 0,
                    maxValue: 1000,
                    ui: {
                        label: 'Valor'
                    }
                }
            });
            var table = new google.visualization.ChartWrapper({
                chartType: 'Table',
                containerId: 'products_table',
                options: {
                    allowHtml: true,
                    showRowNumber: true,
                    width: '100%',
                    height: '100%'
                }
            });
            var formatter = new google.visualization.NumberFormat(
                {prefix: 'R$'});
            formatter.format(data, 3);
            dashboard.bind([stringFilterProducts, stringFilterNameProducts, numberRangeFilterProducts], [table]);
            dashboard.draw(data);
        }
    </script>