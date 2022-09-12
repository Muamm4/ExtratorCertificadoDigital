<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado digital</title>
</head>

<body>
    <form action="extrair_certif.php" method="POST" enctype="multipart/form-data">
        <label>Senha do certificado:</label> <br>
        <input type="password" name="senha_certificado" class="form-group" style="width: 33%;" required> <br>
        <label>Id de identificação</label> <br>
        <input type="text" name="id_plataforma" class="form-group" style="width: 33%;" required><br>
        <label>Arquivo Certificado Digital (.pfx / .p12)</label>
        <input type="file" class="btn fundoCorPrincipal" name="certificado_digital" style="color: #fff; margin-top: 15px; margin-bottom: 35px;"> <br>

        <input type="submit" class="btn btn-success" style="color: #fff; margin-bottom: 15px;" value="EXTRAIR">
    </form>
</body>

</html>