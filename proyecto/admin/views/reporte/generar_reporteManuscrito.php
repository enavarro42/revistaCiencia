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


    <h4 style="font-weight:bold; text-align:center;">Reporte de Manuscritos</h4>


    <table cellspacing="1" border="1" style="width: 100%; border: 1px solid black; border-collapse: collapse; text-align: center; font-size: 10pt;">
        <tr style="background: #E7E7E7;">
            <th style="width: 8%">C&oacute;digo</th>
            <th style="width: 60%">T&iacute;tulo</th>
            <th style="width: 14%">Revista</th>
            <th style="width: 14%">Fecha</th>
        </tr>

        <?php for($i = 0; $i < count($this->result); $i++){ ?>
            <tr>
                <td style="width: 8%"><?php echo $this->result[$i]["id_manuscrito"]; ?></td>
                <td style="width: 60%"><?php echo $this->result[$i]["titulo"]; ?></td>
                <td style="width: 14%"><?php echo $this->result[$i]["nombre"]; ?></td>
                <td style="width: 14%"><?php echo $this->result[$i]["fecha"]; ?></td>
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