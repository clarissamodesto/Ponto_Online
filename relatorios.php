<!DOCTYPE html>
<html>
<head>
    <title>Relatórios</title>
    <link rel="stylesheet" href="./styles/stylerelatorio.css">
</head>
<body>
    <h1>Relatórios</h1>
    <form method="POST" action="">
        <label for="start_date">Data de início:</label>
        <input type="date" id="start_date" name="start_date" required>

        <label for="end_date">Data de término:</label>
        <input type="date" id="end_date" name="end_date" required>

        <button type="submit">Gerar Relatório</button>
    </form> <br>

    <?php
    // Local de conexão com o banco de dados
    $dbPath = "./DB/db_pontos.db";

    // Conecta ao banco de dados SQLite3
    $db = new SQLite3($dbPath);

    // Verifica se a conexão foi estabelecida com sucesso
    if (!$db) {
        die("<script>alert('Erro ao conectar ao banco de dados.');</script>");
    }

    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se um intervalo de tempo foi selecionado
        if (!empty($_POST["start_date"]) && !empty($_POST["end_date"])) {
            $startDate = $_POST["start_date"];
            $endDate = $_POST["end_date"];

            // Consulta os registros de pontos dentro do intervalo de tempo
            $sql = "SELECT u.nome AS nome, p.cpf AS cpf, p.entrada AS entrada, p.saida AS saida, strftime('%s', p.saida) - strftime('%s', p.entrada) AS duracao FROM pontos AS p INNER JOIN usuarios AS u ON p.cpf = u.cpf WHERE (date(p.entrada) >= :start_date AND date(p.entrada) <= :end_date)";

            // Prepara a consulta SQL
            $stmt = $db->prepare($sql);

            // Verifica se a preparação da consulta foi bem-sucedida
            if ($stmt) {
                // Bind dos parâmetros
                $stmt->bindValue(':start_date', $startDate);
                $stmt->bindValue(':end_date', $endDate);

                // Executar a consulta SQL
                $result = $stmt->execute();

                // Verifica se a execução da consulta foi bem-sucedida
                if ($result) {
                    // Verifica se a consulta retornou algum resultado
                    if ($result->fetchArray()) {
                        // Exibe os registros de pontos em uma tabela
                        echo "<h2>Registros de Pontos:</h2>";
                        echo "<table>";
                        echo "<tr><th>Nome</th><th>CPF</th><th>Entrada</th><th>Saída</th><th>Duração</th></tr>";

                        // Volta para o início do resultado
                        $result->reset();

                        while ($row = $result->fetchArray()) {
                            $nome = $row['nome'];
                            $cpf = $row['cpf'];
                            $entrada = $row['entrada'];
                            $saida = $row['saida'];
                            $duracao = $row['duracao'];

                            // Calcula a duração formatada no formato H:i:s
                            $duracaoFormatada = formatarDuracao($duracao);

                            echo "<tr>";
                            echo "<td>$nome</td><td>$cpf</td><td>$entrada</td><td>$saida</td><td>$duracaoFormatada</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                    } else {
                        echo "<script>alert('Nenhum registro encontrado para o intervalo de tempo selecionado.');</script>";
                    }
                } else {
                    echo "<script>alert('Erro ao executar a consulta SQL para registros de pontos.');</script>";
                }

                // Consulta o total de horas trabalhadas por CPF dentro do intervalo de tempo
                $sqlHours = "SELECT u.nome AS nome, p.cpf AS cpf, SUM(strftime('%s', p.saida) - strftime('%s', p.entrada)) AS duracao_total FROM pontos AS p INNER JOIN usuarios AS u ON p.cpf = u.cpf WHERE (date(p.entrada) >= :start_date AND date(p.entrada) <= :end_date) GROUP BY p.cpf";

                // Prepara a consulta SQL para o cálculo das horas
                $stmtHours = $db->prepare($sqlHours);

                // Verifica se a preparação da consulta foi bem-sucedida
                if ($stmtHours) {
                    // Bind dos parâmetros
                    $stmtHours->bindValue(':start_date', $startDate);
                    $stmtHours->bindValue(':end_date', $endDate);

                    // Executar a consulta SQL para o cálculo das horas
                    $resultHours = $stmtHours->execute();

                    // Verifica se a execução da consulta foi bem-sucedida
                    if ($resultHours) {
                        // Verifica se a consulta retornou algum resultado
                        if ($resultHours->fetchArray()) {
                            // Exibe a tabela com o cálculo das horas trabalhadas
                            echo "<h2>Cálculo de Horas:</h2>";
                            echo "<table>";
                            echo "<tr><th>Nome</th><th>CPF</th><th>Total de Horas</th></tr>";

                            // Volta para o início do resultado
                            $resultHours->reset();

                            while ($row = $resultHours->fetchArray()) {
                                $nome = $row['nome'];
                                $cpf = $row['cpf'];
                                $duracaoTotal = $row['duracao_total'];

                                // Calcula a duração total formatada no formato H:i:s
                                $duracaoTotalFormatada = formatarDuracao($duracaoTotal);

                                echo "<tr>";
                                echo "<td>$nome</td><td>$cpf</td><td>$duracaoTotalFormatada</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                        } else {
                            echo "<script>alert('Nenhum registro encontrado para o cálculo das horas.');</script>";
                        }
                    } else {
                        echo "<script>alert('Erro ao executar a consulta SQL para o cálculo das horas.');</script>";
                    }
                } else {
                    echo "<script>alert('Erro ao preparar a consulta SQL para o cálculo das horas.');</script>";
                }
            } else {
                echo "<script>alert('Erro ao preparar a consulta SQL para registros de pontos.');</script>";
            }
        } else {
            echo "<script>alert('Por favor, selecione um intervalo de tempo.');</script>";
        }
    }

    // Função para formatar a duração em segundos para o formato H:i:s
    function formatarDuracao($duracao) {
        $horas = floor($duracao / 3600);
        $minutos = floor(($duracao / 60) % 60);
        $segundos = $duracao % 60;

        $duracaoFormatada = sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);

        return $duracaoFormatada;
    }

    // Fecha a conexão com o banco de dados
    $db->close();
    ?>
</body>
</html>
