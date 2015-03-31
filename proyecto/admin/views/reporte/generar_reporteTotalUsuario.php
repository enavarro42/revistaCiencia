<?php
    header("Content-type: image/png");
    ob_start();



if(isset($this->result1)){
  $chart = new PieChart(350, 240);

  $chart->getPlot()->getPalette()->setPieColor(array(
    new Color(0, 90, 255),
    new Color(255, 0, 0),
    new Color(63, 223, 0),
    new Color(255, 252, 0),
    new Color(255, 60, 0),
    new Color(78, 0, 255),
  ));

  $dataSet = new XYDataSet();

  for( $i = 0; $i<count($this->result1); $i++){

    $dataSet->addPoint(new Point($this->result1[$i]["nombre"], $this->result1[$i]["cantidad"]));

  }

  $chart->setDataSet($dataSet);

  $chart->setTitle("Total de Usuarios por Áreas");
  $chart->render($this->url_img . "/chart/grafico1.png");
}

  // grafico 2

if(isset($this->result2)){
  $chart = new PieChart(350, 240);

  $chart->getPlot()->getPalette()->setPieColor(array(
    new Color(0, 90, 255),
    new Color(255, 0, 0),
    new Color(63, 223, 0),
    new Color(255, 252, 0),
    new Color(255, 60, 0),
    new Color(78, 0, 255),
  ));

  $dataSet = new XYDataSet();

  for( $i = 0; $i<count($this->result2); $i++){

    $dataSet->addPoint(new Point($this->result2[$i]["rol"], $this->result2[$i]["cantidad"]));

  }

  $chart->setDataSet($dataSet);

  $chart->setTitle("Total de Personas por Roles");
  $chart->render($this->url_img . "/chart/grafico2.png");
}


//grafica 3
if(isset($this->result3)){
  $chart = new VerticalBarChart(700, 300);

  $dataSet = new XYDataSet();
  for( $i = 0; $i<count($this->result3); $i++){
    $dataSet->addPoint(new Point($this->result3[$i]["estatus"], $this->result3[$i]["cantidad"]));
  }

  $chart->setDataSet($dataSet);

  $chart->setTitle("Cantidad de Manuscritos por Estatus");
  $chart->render($this->url_img . "/chart/grafico3.png");
}

?>



<style>
th {
    padding-top: 10px;
    padding-bottom: 10px;
}
</style>


<page backcolor="#FEFEFE" backimgx="center" backimgy="bottom" backimgw="100%" backtop="0" backbottom="30mm" footer="date;heure;page" style="font-size: 12pt">

  <bookmark title="Lettre" level="0" ></bookmark>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 14px">
        <tr>
            <td style="width: 20%; text-align: center;">
              <img src="<?php echo $this->url_img; ?>logoLUZ.png" alt="Logo">
            </td>
            <td style="width: 60%; color: #444444; text-align: center; font-size: 16px;">
                UNIVERSIDAD DEL ZULIA<br />
                FACULTAD EXPERIMENTAL DE CIENCIAS<br />
                REVISTAS ARBITRADAS
            </td>
            <td style="width: 20%; text-align: center;">
                <img src="<?php echo $this->url_img; ?>logofec.png" alt="Logo">
            </td>
        </tr>
    </table>
    <br>

    <h3 style="text-align:center;"><?php echo $this->titulo; ?></h3>
    <?php if(isset($this->result1)){ ?>
      <div id="grafica1" style="width: 50%; text-align: center; display:inline;"><img src="<?php echo $this->url_img . "/chart/grafico1.png"; ?>" alt=""></div>
    <?php } ?>

    <?php if(isset($this->result2)){ ?>
      <div id="grafica2" style="width: 50%; text-align: center;  display:inline;"><img src="<?php echo $this->url_img . "/chart/grafico2.png"; ?>" alt=""></div>
    <?php } ?>


    <?php if(isset($this->result3)){ ?>
      <div id="grafica3" style="width: 100%; text-align: left;"><img src="<?php echo $this->url_img . "/chart/grafico3.png"; ?>" alt=""></div>
    <?php } ?>

</page>

<?php

    $content = ob_get_clean();

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'es');
        $html2pdf->pdf->SetDisplayMode('fullpage');
//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('reporte_total_usuario.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

?>