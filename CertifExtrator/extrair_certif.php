<!-- SweetAlert -->


<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?

function output_message($mensagem = "", $icone = "success", $titulo = "Alerta", $textoBotao = "")
{

    if (!empty($mensagem) && !empty($titulo)) {

        $titulo = "title: '$titulo', ";
        $mensagem = "text: '$mensagem', ";
        $icone = "icon: '$icone', ";
        $textoBotao = !empty($textoBotao) ? "button: '$textoBotao', " : "";

        echo "<script language=\"javascript\" type=\"text/javascript\">

			swal({
			  $titulo
			  $mensagem
			  $icone
			  $textoBotao
			});

		</script>";
    } else {

        echo "";
    }
}

$senha = $_POST['senha_certificado'];
$id = $_POST['id_plataforma'];

/// Local Store de Certificados /////

$caminho_arquivo = "/path/extrator/certif/certificado_$id";
$arquivo = '';

/// Verificar se Ja existe o Certificado no Diretorio ///

if (file_exists("$caminho_arquivo.pfx")) {

    $arquivo = "$caminho_arquivo.pfx";
} elseif (file_exists("$caminho_arquivo.p12")) {

    $arquivo = "$caminho_arquivo.p12";
};
if ($_FILES['certificado_digital']['name'] != "") {
    $certificado_digital = isset($_FILES["certificado_digital"]) ? $_FILES["certificado_digital"] : FALSE;


    /// Verifica se a Extenção do arquivo ///
    if ($certificado_digital['type'] != '')

        $up = array('pfx', 'p12');

    $extensao = strtolower(end(explode('.', $_FILES['certificado_digital']['name'])));

    if (array_search($extensao, $up) === false) {

        output_message("Arquivo inválido! O arquivo deve ser do tipo pfx ou p12. Envie outro arquivo", 'error', 'Erro!', '', 0, '');
    } else {
        $nome_arquivo = 'certificado_' . $id . '.' . $extensao;

        $caminho = "/path/extrator/certif/" . $nome_arquivo;

        if ($certificado_digital) {

            //unlink($certificado_digital);

            move_uploaded_file($certificado_digital["tmp_name"], $caminho);
        };



        //// Abre o certificado com a senha informada ///
        openssl_pkcs12_read(file_get_contents($caminho), $cert_info, $senha);

        if ($cert_info['cert'] == '') {

            echo "errou";
            output_message("Senha Incorreta", "error", "", "", 0, "");
            die;
        };

        // Gera os dados para o certificado/chavepublica/chaveprivada //

        $publickey = $cert_info['cert'];
        $privatekey = $cert_info['pkey'];
        @$certf = $cert_info['cert'] . implode('', $cert_info['extracerts']) . $cert_info['pkey'];

        // Verifica o restante das informações //

        $CertDados = openssl_x509_parse(openssl_x509_read($cert_info['cert']));

        // Validade do Certificado

        $validadeCert = date('Y-m-d', $CertDados["validTo_time_t"]);


        /////Cria arquivos PEM //////////
        file_put_contents("../certificados/" . $id . "_certificado.pem", $certf);
        file_put_contents("../certificados/" . $id . "_public.pem", $publickey);
        file_put_contents("../certificados/" . $id . "_priv.pem", $privatekey);
    }
}



//// Exibição de informações e Download dos Arquivos PEM ////
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a href="../certificados/<?= $id ?>_certificado.pem" download>CERTIFICADO</a>
    <a href="../certificados/<?= $id ?>_public.pem" download>CHAVE PUBLICA</a>
    <a href="../certificados/<?= $id ?>_priv.pem" download>CHAVE PRIVADA</a>
    <h1> Validade do certificado : <?= $validadeCert ?> </h1>

    <pre><?= var_dump($CertDados) ?> </pre>
</body>

</html>