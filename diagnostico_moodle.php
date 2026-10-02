<?php
/**
 * Script de Diagnóstico Completo para Atualização do Moodle
 * Coloque este ficheiro em: C:\wamp64\www\moodle\diagnostico_moodle.php
 * Aceda via browser em: http://localhost/moodle/diagnostico_moodle.php
 */

@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

echo "<h1>Relatório de Diagnóstico do Moodle</h1>";
echo "<hr>";

// 1. Diagnóstico do Ambiente PHP
echo "<h2>1. Ambiente PHP</h2>";
echo "Versão atual do PHP: <b>" . phpversion() . "</b><br>";
echo "Extensão MySQLi carregada: " . (extension_loaded('mysqli<b>') ? '<span style="color:green;">SIM</span>' : '<span style="color:red;">NÃO (Obrigatório ativar no WampServer)</span>') . "<br>";
echo "Extensão PDO MySQL carregada: " . (extension_loaded('pdo_mysql') ? '<span style="color:green;">SIM</span>' : '<span style="color:orange;">NÃO</span>') . "<br>";

// 2. Teste do Arquivo config.php
echo "<h2>2. Ficheiro de Configuração (config.php)</h2>";
$config_path = __DIR__ . '/config.php';
if (file_exists($config_path)) {
    echo "Ficheiro `config.php` encontrado.<br>";
    include($config_path);
    echo "Variável \$CFG->dbhost configurada como: <b>" . (isset($CFG->dbhost) ?$CFG->dbhost : 'Não definida') . "</b><br>";
    echo "Variável \$CFG->dbname configurada como: <b>" . (isset($CFG->dbname) ?$CFG->dbname : 'Não definida') . "</b><br>";
    echo "Caminho do Dataroot: <b>" . (isset($CFG->dataroot) ?$CFG->dataroot : 'Não definido') . "</b><br>";
} else {
    echo "<span style='color:red;'>Erro Crítico: Ficheiro `config.php` não foi encontrado na raiz!</span><br>";
    exit;
}

// 3. Teste de Conexão Direta ao Banco de Dados
echo "<h2>3. Teste de Conexão com o Banco de Dados</h2>";
$dbhost = isset($CFG->dbhost) ?$CFG->dbhost : '127.0.0.1';
$dbuser = isset($CFG->dbuser) ? $CFG->dbuser : 'root';$dbpass = isset($CFG->dbpass) ?$CFG->dbpass : '';
$dbname = isset($CFG->dbname) ? $CFG->dbname : 'moodle';$dbport = isset($CFG->dbport) ? (int)$CFG->dbport : 3306;

$conn = mysqli_init();
@$conexao = mysqli_real_connect($conn,$dbhost, $dbuser,$dbpass, $dbname,$dbport);

if ($conexao) {
    echo "<span style='color:green; font-weight:bold;'>Sucesso: Conexão com o banco de dados '$dbname' estabelecida corretamente!</span><br>";
    
    // Verificar versão do Moodle na base de dados vs Código
    $res = mysqli_query($conn, "SELECT value FROM {$CFG->prefix}config WHERE name = 'version'");
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $db_version =$row['value'];
        echo "Versão registrada na Base de Dados (tabela mdl_config): <b>{$db_version}</b><br>";
    } else {
        echo "<span style='color:red;'>Não foi possível ler a tabela de configuração do Moodle (verifique o prefixo das tabelas).</span><br>";
    }
    
    // Verificar versão no código atual
    if (file_exists(__DIR__ . '/version.php')) {
        include(__DIR__ . '/version.php');
        echo "Versão presente no ficheiro `version.php` do código: <b>{$version}</b><br>";
        
        if (isset($db_version)) {
            if ($version >$db_version) {
                echo "<p style='color:blue;'>Status: O código é <b>SUPERIOR</b> ao banco de dados. O upgrade está pronto para correr.</p>";
            } elseif ($version <$db_version) {
                echo "<p style='color:red;'>Status de Bloqueio: O código atual é <b>ANTERIOR</b> ao da versão deste banco de dados! (Exatamente o erro de bloqueio de segurança).</p>";
            } else {
                echo "<p style='color:green;'>Status: O código e a base de dados estão sincronizados na mesma versão.</p>";
            }
        }
    }
    
    mysqli_close($conn);
} else {
    echo "<span style='color:red; font-weight:bold;'>Falha na Conexão MySQL:</span> " . mysqli_connect_error() . "<br>";
    echo "<p><b>Detalhe do erro:</b> O servidor rejeitou a conexão. Certifique-se de que o WampServer está ativo e o utilizador root possui permissões compatíveis com o PHP 7.0.</p>";
}

// 4. Verificação de Permissões no Dataroot
echo "<h2>4. Verificação da Diretoria de Dados (Dataroot)</h2>";
if (isset($CFG->dataroot)) {
    if (is_dir($CFG->dataroot)) {
        echo "A pasta dataroot existe fisicamente.<br>";
        if (is_writable($CFG->dataroot)) {
            echo "<span style='color:green;'>A pasta tem permissões de escrita corretas.</span><br>";
        } else {
            echo "<span style='color:red;'>Aviso: A pasta dataroot NÃO tem permissões de escrita para o servidor web.</span><br>";
        }
    } else {
        echo "<span style='color:red;'>Erro: O diretório dataroot configurado não existe ou não está acessível no caminho especificado.</span><br>";
    }
}
?>