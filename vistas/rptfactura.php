<?php
require_once "../config/Conexion.php";
require_once "../fpdf186/fpdf.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('ID de factura inválido');
}

// Cabecera
$sqlCab = "SELECT f.id, f.fecha, c.cedula, c.nombre, c.telefono, c.direccion
           FROM factura f
           JOIN cliente c ON c.cedula = f.cedulaCliente
           WHERE f.id = ?";
$stmt = $conexion->prepare($sqlCab);
$stmt->bind_param("i", $id);
$stmt->execute();
$cab = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$cab) {
    die('Factura no encontrada');
}

// Detalle
$sqlDet = "SELECT d.idProducto, p.nombre AS producto, d.cantidad,
                  p.precio AS precio,
                  (d.cantidad * p.precio) AS subtotal
           FROM detalle_factura d
           JOIN producto p ON p.id = d.idProducto
           WHERE d.idFactura = ?
           ORDER BY d.id";

$stmt = $conexion->prepare($sqlDet);
$stmt->bind_param("i", $id);
$stmt->execute();
$det = $stmt->get_result();

// PDF
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, utf8_decode('Factura (Maestro-Detalle)'), 0, 1, 'C');
        $this->Ln(2);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    }
}
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);

// Cabecera factura/cliente
$pdf->Cell(40, 7, 'Factura #:', 0, 0);
$pdf->Cell(60, 7, $cab['id'], 0, 1);
$pdf->Cell(40, 7, 'Fecha:', 0, 0);
$pdf->Cell(60, 7, $cab['fecha'], 0, 1);
$pdf->Cell(40, 7, 'Cliente:', 0, 0);
$pdf->Cell(120, 7, utf8_decode($cab['nombre']) . " (" . $cab['cedula'] . ")", 0, 1);
$pdf->Cell(40, 7, 'Telefono:', 0, 0);
$pdf->Cell(60, 7, utf8_decode($cab['telefono']), 0, 1);
$pdf->Cell(40, 7, 'Direccion:', 0, 0);
$pdf->MultiCell(0, 7, utf8_decode($cab['direccion']));
$pdf->Ln(2);

// Detalle
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(25, 8, 'Codigo', 1, 0, 'C', true);
$pdf->Cell(90, 8, 'Producto', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Cant.', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Precio', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Subtotal', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$total = 0;
while ($r = $det->fetch_assoc()) {
    $pdf->Cell(25, 7, $r['idProducto'], 1, 0, 'C');
    $pdf->Cell(90, 7, utf8_decode($r['producto']), 1, 0);
    $pdf->Cell(20, 7, number_format($r['cantidad'], 0), 1, 0, 'R');
    $pdf->Cell(25, 7, number_format($r['precio'], 2), 1, 0, 'R');
    $pdf->Cell(25, 7, number_format($r['subtotal'], 2), 1, 1, 'R');
    $total += (float) $r['subtotal'];
}

// Total
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(160, 8, 'TOTAL', 1, 0, 'R');
$pdf->Cell(25, 8, number_format($total, 2), 1, 1, 'R');

$pdf->Output('I', "Factura_$id.pdf");
