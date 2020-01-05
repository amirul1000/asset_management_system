<?
define("FPDF_FONTPATH", "{$_SERVER['DOCUMENT_ROOT']}repository/demos/fpdf/fpdf/font/");
require("{$_SERVER['DOCUMENT_ROOT']}repository/demos/fpdf/fpdf/fpdf.php");
require ("./InvoicePDF.class.php");
$pdf = new InvoicePDF();
$pdf->Output();
?>