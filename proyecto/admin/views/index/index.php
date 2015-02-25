<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Bienvenido [ <?php echo $_SESSION["user"]; ?> ]</h2>
            <div id="cargando"><img src="<?php echo $_layoutParams['root'] . "/image/loading.gif"; ?>" width="16px" height="16px"></img> Cargando...</div>
            <div id="grafica1" class="col-lg-6"></div>
            <div id="grafica2" class="col-lg-6"></div>
            <div id="grafica3" class="col-lg-12"></div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->


    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart1);

      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart1() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([<?php if(isset($this->usuario_area)) echo $this->usuario_area; ?>]);

        // Set chart options
        var options = {title:'<?php if(isset($this->usuario_area)) echo $this->titulo_g1; ?>',
        				is3D:true,
                       width:500,
                       height:300};
 
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('grafica1'));
 
        chart.draw(data, options);
      }

      google.setOnLoadCallback(drawChart2);

      function drawChart2() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([<?php if(isset($this->persona_rol)) echo $this->persona_rol; ?>]);

        // Set chart options
        var options = {title:'<?php if(isset($this->persona_rol)) echo $this->titulo_g2; ?>',
                pieHole: 0.4,
                       width:500,
                       height:300};
 
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('grafica2'));
 
        chart.draw(data, options);
      }

      google.setOnLoadCallback(drawChart3);

      function drawChart3() {

            var data = google.visualization.arrayToDataTable([<?php if(isset($this->manuscrito_estatus)) echo $this->manuscrito_estatus; ?>]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                             { calc: "stringify",
                               sourceColumn: 1,
                               type: "string",
                               role: "annotation" },
                             2]);

            var options = {
              title: "<?php if(isset($this->manuscrito_estatus)) echo $this->titulo_g3; ?>",
              width: 800,
              height: 600,
              bar: {groupWidth: "95%"},
              legend: { position: "none" },
            };
            var chart = new google.visualization.ColumnChart(document.getElementById("grafica3"));
            chart.draw(view, options);
            $("#cargando").html("");
      }





    </script>

</div>
<!-- /#page-wrapper -->