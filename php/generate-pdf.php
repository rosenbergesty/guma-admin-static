<?php
require('lib/fpdf/fpdf.php');


class PDF extends FPDF
{
// Page header
function Header()
{
    $this->SetFont('Times','',8);

    // Logo
    $this->setX(180);
    $this->Cell(10,10,'123456',0,2,'R');

    $this->setX(10);
    $this->Cell(10,4,'Guma Corp.', 0, 0);
    $this->setX(180);
    $this->Cell(10,4,'Date: 01/01/2001', 0, 2, 'R');
    $this->setX(10);
    $this->Cell(10,4,'Roll-off Containers', 0, 0);
    $this->setX(180);
    $this->Cell(10,4,'3:12', 0, 2, 'R');
    $this->setX(10);
    $this->Cell(10,4,'302 Plymouth St.', 0, 2);
    $this->Cell(10,4,'Brooklyn, NY 11201', 0, 2);
    $this->Cell(10,4,'718-858-9805 Fax 718-522-0073', 0, 2);

    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->setY(50);
$pdf->setX(10);
$pdf->Cell(13,10,'Driver: ',0,0 );
$pdf->SetFont('Times','U',12);
$pdf->Cell(20,10,'John Doe',0,2);
$pdf->SetFont('Times','',12);
$pdf->setX(10);
$pdf->Cell(9,10,'Site:',0,0 );
$pdf->SetFont('Times','U',12);
$pdf->Cell(20,10,'123 Somewhere St. Brooklyn, NY',0,2);
$pdf->SetFont('Times','',12);
$pdf->setX(10);
$pdf->Cell(33,10,'Container Number:',0,0 );
$pdf->SetFont('Times','U',12);
$pdf->Cell(20,10,'123',0,2);
$pdf->Output();
?>