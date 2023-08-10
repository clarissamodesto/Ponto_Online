<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x=ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./styles/stylecadastro.css">
    <title>Painel de Cadastro</title>
</head>
<body>
    <div class="container">
        <div class="form-image">
            
        </div>
        <div class="form">
            <form action="backcadastro.php" method="post" id="cadastroForm">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastre-se</h1>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="name">Nome</label>
                        <input id="name" type="name" name="name" placeholder="Primeiro e último nome" required>
                    </div>

                    <div class="input-box">
                        <label for="number">CPF</label>
                        <input id="number" type="text" name="number" placeholder="XXXXXXXXXXX" onblur="ValidaCPF()" required>
                    </div>

                    <div class="input-box">
                        <label for="password">Senha</label>
                        <input id="password" type="password" name="password" placeholder="Digite sua Senha" required>
                    </div>

                    <div class="input-box">
                        <label for="Confirmpassword">Confirme sua Senha</label>
                        <input id="Confirmpassword" type="password" name="Confirmpassword" placeholder="Digite sua Senha" required>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="login-button">
                        <button type="button" id="submitButton"><a>Continuar</a></button>
                        <button><a href="frontpontos.php">Entrar</a></button>
                    </div>
                    <div class="infos">
                        <?php
                        if(isset($_GET['message']))
                        {
                            $message = $_GET['message'];
                            if ($message == 1){
                                echo "<script>alert('Cadastro realizado com sucesso!');</script>";
                            }
                            if ($message == 2){
                                echo "<script>alert('Erro ao cadastrar. Tente novamente.');</script>";
                            }
                            if ($message == 3){
                                echo "<script>alert('As senhas não coincidem. Tente novamente.');</script>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Adicionar evento de clique ao botão
        document.getElementById("submitButton").addEventListener("click", function() {
            // Enviar o formulário quando o botão for clicado
            document.getElementById("cadastroForm").submit();
        });
        // Teste CPF
        function TestaCPF(strCPF) {
            var Soma;
            var Resto;
            Soma = 0;
        if (strCPF == "00000000000") return false;

        for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
        Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11))  Resto = 0;
            if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

        Soma = 0;
            for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11))  Resto = 0;
            if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
            return true;
            }
        
        function ValidaCPF() {
            let strCPF = document.getElementById("number").value;
            if (!TestaCPF(strCPF)) {
                alert("CPF Inválido: "+strCPF);
                document.getElementById("number").value = ""; // Limpa o campo de CPF caso seja inválido
            }
        }
    </script>
</body>
</html>