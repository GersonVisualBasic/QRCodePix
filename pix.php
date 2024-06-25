<?php

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

require_once(__DIR__ . '/qrcode/autoload.php');

function GeraCopiaCola($Chave, $Valor, $Texto, $Vencimento, $TaxaJurosMora, $Multa, $Validade)
{
    $TaxaJurosMora = empty($TaxaJurosMora) ? 0.00 : $TaxaJurosMora;
    $Multa = empty($Multa) ? 0.00 : $Multa;

    $ValorTotal = CalcularJurosMoraEMulta($Valor, $Vencimento, $TaxaJurosMora, $Multa);

    $Total = number_format($ValorTotal, 2, '.', '');
    $Beneficiario = substr(trim('SEU NOME OU DA EMPRESA'), 0, 25);
    $Cidade = substr(trim('CIDADE '), 0, 15);
    $Identificador = substr(trim('CODIGO DE IDENTIFICACAO'), 0, 25);
    $Descricao = substr(trim($Texto), 0, 25);
    $xVencimento = '';

    $DataAtual = new DateTime();
    $DataValidade = new DateTime($Validade);
    $intervalo = $DataAtual->diff($DataValidade);
    $segundosValidade = $intervalo->days * 24 * 60 * 60 + $intervalo->h * 60 * 60 + $intervalo->i * 60 + $intervalo->s;

    $xChave = '01' . sprintf('%02d', strlen($Chave)) . $Chave;
    $xDescricao = '02' . sprintf('%02d', strlen($Descricao)) . $Descricao;
    $xBCB = '0014BR.GOV.BCB.PIX';

    $Saida = '000201';
    $Saida .= '26' . sprintf('%02d', strlen($xBCB . $xChave . $xDescricao . $xVencimento)) . $xBCB . $xChave . $xDescricao;
    $Saida .= '52040000';
    $Saida .= '5303986';
    $Saida .= '54' . sprintf('%02d', strlen($Total)) . $Total;
    $Saida .= '5802BR';
    $Saida .= '59' . sprintf('%02d', strlen($Beneficiario)) . $Beneficiario;
    $Saida .= '60' . sprintf('%02d', strlen($Cidade)) . $Cidade;
    $Saida .= '62' . sprintf('%02d', strlen($Identificador) + 4) . '05' . sprintf('%02d', strlen($Identificador)) . $Identificador;
    $Saida .= '6304';
    $Saida .= '53' . sprintf('%02d', strlen($segundosValidade)) . $segundosValidade;

    return $Saida . CRC16($Saida);
}

function CRC16($data)
{
    $polynomial = 0x1021;
    $crc = 0xFFFF;

    for ($i = 0; $i < strlen($data); $i++) {
        $crc ^= (ord($data[$i]) << 8);
        for ($j = 0; $j < 8; $j++) {
            if (($crc << 1) & 0x10000) {
                $crc = (($crc << 1) & 0xFFFF) ^ $polynomial;
            } else {
                $crc = ($crc << 1) & 0xFFFF;
            }
        }
    }

    return strtoupper(dechex($crc));
}

function GeraQRCode($S)
{
    $options = new QROptions(['eccLevel' => QRCode::ECC_L, 'outputType' => QRCode::OUTPUT_MARKUP_SVG, 'version' => -1]);
    return (new QRCode($options))->render($S);
}

function GeraQRCodeTamanho($S)
{
    $qrcode = (new QRCode(new QROptions(['eccLevel' => QRCode::ECC_L, 'outputType' => QRCode::OUTPUT_MARKUP_SVG, 'addQuietzone' => false, 'markupDark' => '#000', 'markupLight' => 'transparent', 'version' => -1])));
    return array($qrcode->render($S), count($qrcode->getMatrix($S)->matrix()));
}

function CalcularJurosMoraEMulta($Valor, $Vencimento, $TaxaJurosMora, $Multa)
{
    $DataVencimento = new DateTime($Vencimento);
    $DataAtual = new DateTime();
    $DiasAtraso = $DataAtual->diff($DataVencimento)->days;

    $JurosMora = $Valor * ($TaxaJurosMora / 100) * $DiasAtraso;

    $ValorMulta = $Valor * ($Multa / 100);

    $ValorTotal = $Valor + $JurosMora + $ValorMulta;

    return number_format($ValorTotal, 2, '.', '');
}

