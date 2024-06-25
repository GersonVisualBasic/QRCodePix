<!DOCTYPE html>
<html>

<head>
	<title>QRCode - Pix Copia e Cola</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f9f9f9;
		}

		.container {
			width: 80%;
			margin: 40px auto;
			padding: 20px;
			background-color: #fff;
			border: 1px solid #ddd;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}

		.form-group {
			margin-bottom: 20px;
		}

		.label {
			display: block;
			margin-bottom: 10px;
		}

		.input {
			width: 98.5%;
			padding: 10px;
			border: 1px solid #ccc;
		}

		.button {
			background-color: #4CAF50;
			color: #fff;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
		}

		.button:hover {
			background-color: #3e8e41;
		}
	</style>
</head>

<body>
	<div class="container">
		<center><h2>QRCode - Pix Copia e Cola</h2></center>
		<form>
			<div class="form-group">
				<label class="label" for="chavePix"><b>Chave Pix:</b></label>
				<input type="text" id="chavePix" class="input" required>
			</div>
			<div class="form-group">
				<label class="label" for="jurosMora"><b>Juros Mora %:</b></label>
				<input type="text" id="jurosMora" class="input" placeholder="Ex: 0.26" value="0.26">
			</div>
			<div class="form-group">
				<label class="label" for="multa"><b>Multa %:</b></label>
				<input type="text" id="multa" class="input" placeholder="Ex: 2.00" value="2.00">
			</div>
			<div class="form-group">
				<label class="label" for="valor"><b>Valor:</b></label>
				<input type="text" id="valor" class="input" placeholder="Ex: 50.00" required>
			</div>
			<div class="form-group">
				<label class="label" for="descricao"><b>Descrição:</b></label>
				<input type="text" id="descricao" class="input" required>
			</div>

			<div class="form-group">
				<label class="label" for="vencimento"><b>Vencimento:</b></label>
				<input type="date" id="vencimento" class="input" placeholder="DD/MM/AAAA" required>
			</div>

			<div class="form-group">
				<label class="label" for="validade"><b>Validade:</b></label>
				<input type="date" id="validade" class="input" placeholder="DD/MM/AAAA" required>
			</div>

			<button class="button" type="submit">Gerar</button>
		</form>
		<br />
		<div id="result"></div>
	</div>

	<script>
		const form = document.querySelector('form');
		form.addEventListener('submit', (e) => {
			e.preventDefault();
			const chavePix = document.querySelector('#chavePix').value;
			const valor = document.querySelector('#valor').value;
			const descricao = document.querySelector('#descricao').value;
			const vencimento = document.querySelector('#vencimento').value;
			const jurosMora = document.querySelector('#jurosMora').value;
			const multa = document.querySelector('#multa').value;
			const validade = document.querySelector('#validade').value;

			fetch('generate_qr_code.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: `chavePix=${chavePix}&valor=${valor}&descricao=${descricao}&vencimento=${vencimento}&jurosMora=${jurosMora}&multa=${multa}&validade=${validade}`
				})
				.then(response => response.text())
				.then((result) => {
					document.getElementById('result').innerHTML = result;
				})
				.catch((error) => {
					console.error('Error:', error);
				});
		});
	</script>
</body>

</html>