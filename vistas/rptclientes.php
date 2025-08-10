<?php
require('MC_Table.php');                 // en /vistas
require_once "../modelos/Cliente.php";   // usa tu modelo

$pdf = new PDF_MC_Table();
$pdf->AddPage();

// Título
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,6,'',0,0,'C');
$pdf->Cell(100,6,'LISTADO DE CLIENTES',1,0,'C');
$pdf->Ln(10);

// Encabezados
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,6,'Cedula',1,0,'C',1);
$pdf->Cell(70,6,'Nombre',1,0,'C',1);
$pdf->Cell(40,6,'Telefono',1,0,'C',1);
$pdf->Ln(6);

// Datos
$Cliente = new Cliente();
$rspta = $Cliente->listar();

$pdf->SetWidths(array(30,70,40));
$pdf->SetFont('Arial','',10);

while($reg = $rspta->fetch_object()){
  $pdf->Row(array($reg->cedula, $reg->nombre, $reg->telefono));
}

$pdf->Output();
