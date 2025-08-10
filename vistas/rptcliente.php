<?php
require_once "../config/Conexion.php";
require_once "../fpdf186/fpdf.php";

$ced = $_GET['cedula'] ?? '';
if ($ced === '') { die('Cédula inválida'); }

$stmt = $conexion->prepare("SELECT cedula, nombre, telefono, direccion FROM cliente WHERE cedula = ?");
$stmt->bind_param("s", $ced);
$stmt->execute();
$cli = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$cli){ die('Cliente no encontrado'); }

class PDF extends FPDF {
  function Header(){
    $this->SetFont('Arial','B',14);
    $this->Cell(0,8,utf8_decode('Ficha de Cliente'),0,1,'C');
    $this->Ln(2);
  }
  function Footer(){
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
  }
}
$pdf = new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$pdf->Cell(40,8,'Cedula:',0,0);    $pdf->Cell(0,8,$cli['cedula'],0,1);
$pdf->Cell(40,8,'Nombre:',0,0);    $pdf->Cell(0,8,utf8_decode($cli['nombre']),0,1);
$pdf->Cell(40,8,'Telefono:',0,0);  $pdf->Cell(0,8,utf8_decode($cli['telefono']),0,1);
$pdf->Cell(40,8,'Direccion:',0,0); $pdf->MultiCell(0,8,utf8_decode($cli['direccion']));

$pdf->Output('I',"Cliente_{$cli['cedula']}.pdf");
