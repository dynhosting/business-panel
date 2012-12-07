<?php require_once "../../../wp-load.php";
global $wpdb;
switch($_REQUEST["import_to"])
{
	case "pdf":
		require('dompdf/dompdf_config.inc.php');
		require_once("views/import/to_pdf.php");
				
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$dompdf->stream("sample.pdf");
		#echo $html;
	break;
}