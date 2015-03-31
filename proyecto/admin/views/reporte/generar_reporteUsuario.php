<?php

    ob_start();


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


    <h4 style="font-weight:bold; text-align:center;">Reporte de Usuarios</h4>


    <table cellspacing="1" border="1" style="width: 100%; border: 1px solid black; border-collapse: collapse; text-align: center; font-size: 10pt;">
        <tr style="background: #E7E7E7;">
            <th style="width: 50%">Nombre y Apellido</th>
            <th style="width: 25%">Rol</th>
            <th style="width: 25%">&Aacute;rea</th>
        </tr>

        <?php for($i = 0; $i < count($this->reporte); $i++){ ?>
            <tr>
                <td style="width: 50%"><?php echo $this->reporte[$i]["primerNombre"] . " " . $this->reporte[$i]["apellido"]; ?></td>
                <td style="width: 25%"><?php echo $this->reporte[$i]["rol"]; ?></td>
                <td style="width: 25%"><?php echo $this->reporte[$i]["area"]; ?></td>
            </tr>
        <?php } ?>
    </table>



</page>

<?php

    $content = ob_get_clean();

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'es');
        $html2pdf->pdf->SetDisplayMode('fullpage');
//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('reporte_usuario.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

?>