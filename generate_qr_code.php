<?php
include 'pix.php';

if (isset($_POST['chavePix']) && isset($_POST['valor']) && isset($_POST['descricao']) && isset($_POST['vencimento']) && isset($_POST['jurosMora']) && isset($_POST['multa']) && isset($_POST['validade'])) {
	$chavePix = $_POST['chavePix'];
	$valor = $_POST['valor'];
	$descricao = $_POST['descricao'];
	$vencimento = $_POST['vencimento'];
	$jurosMora = $_POST['jurosMora'];
	$multa = $_POST['multa'];
	$validade = $_POST['validade'];

	$C = GeraCopiaCola($chavePix, $valor, $descricao, $vencimento, $jurosMora, $multa, $validade);
	echo '<div style="text-align: center; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">';
	echo '<b>Copia e cola:</b><br>';
	echo '<p>' . $C . '</p>';
	echo '<b>QRCode:</b><br>';
	echo '<img src="' . GeraQRCode($C) . '" width="200" height="200">';
	echo '</div>';
} else {
	echo 'Erro: dados inválidos';
}
