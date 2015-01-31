<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Bienvenido [ <?php echo $_SESSION["user"]; ?> ]</h2>
            <div id="grafica1" class="col-lg-6"></div>
            <div id="grafica2" class="col-lg-6"></div>
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
        data.addRows([<?php echo $this->usuario_area; ?>]);

        // Set chart options
        var options = {'title':'<?php echo $this->titulo_g1; ?>',
        				'is3D':true,
                       'width':500,
                       'height':500};
 
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
        data.addRows([<?php echo $this->persona_rol; ?>]);

        // Set chart options
        var options = {title:'<?php echo $this->titulo_g2; ?>',
                pieHole: 0.4,
                       width:500,
                       height:500};
 
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('grafica2'));
 
        chart.draw(data, options);
      }

    </script>

</div>
<!-- /#page-wrapper -->