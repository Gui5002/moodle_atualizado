<?php

/**
 * Consumo do Web Services API-PESSOA
 * @package    core_webservice
 * @copyright  AGU - Advocacia Geral da União ©2016
 * @license    Este código é livre para uso dentro do AGU! Fora deste pode servir como base para estudos futuros.
 */

// Guzzle - Para consumo do serviço
require_once ($CFG->dirroot . '/lib/guzzle/autoload.php');

use GuzzleHttp\Client;

class PessoaAgu
{
    /**
     * Atualiza dados do usuário obtidos do serviço conforme o $emailUsuario.
     * @param string $emailcpfUsuario
     * @return bool
     */
    public function atualizaDadosUsuario($emailUsuario)
    {
        global $DB;

        // Busca dados do usuário, conforme email, via Serviço

        $dadosUsuario = self::buscaDadosUsuarioPorServico($emailUsuario);

        if ($dadosUsuario['sucesso'] != true) {
            return false;
        } else {
            $dados = $dadosUsuario['dadosUsuario'][0];
            if ($dados['situacao'] != '1') {
                return false;
            }
        }

        // Separa nome e sobrenome
        $nomeCompleto = isset($dados['nome_servidor']) ? trim($dados['nome_servidor']) : '';
        $nomeCompleto = str_replace("'", "\'", $nomeCompleto);
        $posicaoEspaco = strpos($nomeCompleto, ' ');

        if ($posicaoEspaco != false) {
            $nome = substr($nomeCompleto, 0, $posicaoEspaco);
            $sobrenome = substr($nomeCompleto, $posicaoEspaco++);
        }

        $nome = trim($nome);
        $sobrenome = trim($sobrenome);
        $cpf = isset($dados['cpf']) ? trim($dados['cpf']) : '';
        $sigla_lotacao = isset($dados['sigla_unidade_lotacao']) ? $dados['sigla_unidade_lotacao'] : '';
        $lotacao = isset($dados['unidade_lotacao']) ? $dados['unidade_lotacao'] : '';
        $sigla_exercicio = isset($dados['sigla_unidade_exercicio']) ? $dados['sigla_unidade_exercicio'] : '';
        $exercicio = isset($dados['unidade_de_exercicio']) ? $dados['unidade_de_exercicio'] : '';
        $cidade = isset($dados['cidade_exercicio']) ? trim($dados['cidade_exercicio']) : '';
        $uf = isset($dados['uf_exercicio']) ? trim($dados['uf_exercicio']) : '';
        $city = $cidade . ' - ' . $uf;
        $siape = isset($dados['matricula_siape']) ? $dados['matricula_siape'] : '';
        $endereco = isset($dados['endereco_exercicio']) ? $dados['endereco_exercicio'] : '';
        $cd_cargo = isset($dados['codigo_cargo']) ? trim($dados['codigo_cargo']) : '';
        $ds_cargo = isset($dados['cargo_funcao']) ? $dados['cargo_funcao'] : '';

        //        $sexo = isset($dados['sexo']) ? $dados['sexo'] : '';
        //        $formacao = isset($dados['formacao']) ? $dados['formacao'] : '';
        //        $situacao = isset($dados['situacao']) ? $dados['situacao'] : '';
        //        $uf = isset($dados['lotacao']['endereco']['uf']['sigla']) ? $dados['lotacao']['endereco']['uf']['sigla'] : '';
        //        $funcao = isset($dados['funcaoComissionada']['descricao']) ? $dados['funcaoComissionada']['descricao'] : '';
        //        $escolaridade = isset($dados['escolaridade']) ? $dados['escolaridade'] : 0;
        //        $lotacaoEndereco = isset($dados['lotacao']['endereco']['endereco']) ? $dados['lotacao']['endereco']['endereco'] : '';
        //        $valores = array($cpf, $sexo, $escolaridade, $formacao, $situacao, $uf, $siape, $cargo, $funcao, $lotacao);

        $usuarioExiste = <<<SQL
                    SELECT id FROM mdl_user WHERE cpf = '{$cpf}'
            SQL;
        // Verifica se a tabela possui o usuario de acordo com o email. (Retorna 1 se existir)
        $existe = $DB->record_exists_sql($usuarioExiste);

        if ($existe !== 1) {
            // Atualiza os dados do servidor
            $sql = '';
            $sql .= 'UPDATE ';
            $sql .= 'mdl_user ';
            $sql .= 'SET ';
            $sql .= "firstname = '" . $nome . "',";
            $sql .= "lastname = '" . $sobrenome . "',";
            $sql .= "city = '" . $city . "',";
            $sql .= "address = '" . $endereco . "',";
            $sql .= "cd_cargo = '" . $cd_cargo . "',";
            $sql .= "ds_cargo = '" . $ds_cargo . "',";
            //            $sql .= 'description = \''.$ds_cargo.'\',';
            $sql .= "siglalotacao = '" . $sigla_lotacao . "',";
            $sql .= "lotacao = '" . $lotacao . "',";
            $sql .= "siglaexercicio = '" . $sigla_exercicio . "',";
            $sql .= "exercicio = '" . $exercicio . "',";
            $sql .= "siape = '" . $siape . "',";
            $sql .= "cpf = '" . $cpf . "'";
            $sql .= 'WHERE ';
            $sql .= "email = '" . $emailUsuario . "' ";

            $DB->execute($sql);

            return true;
        }
        return false;
    }

    /**
     * Consome o serviço via biblioteca Guzzle para obter os dados do usuário.
     * @param string $emailUsuario
     * @return bool
     */
    private function buscaDadosUsuarioPorServico($emailUsuario)
    {
        global $CFG;

        $apiUrl = $CFG->apiurl;  // http://api-pessoas.agu.gov.br
        $apiVersao = $CFG->apiversao;  // 1
        $apiParam = $CFG->apiparametro;  // email
        $apiMetodo = $CFG->apimetodo;  // servidor

        $retorno['sucesso'] = false;
        $retorno['mensagem'] = 'Erro ao buscar dados do usuario, via serviço.';
        $retorno['dadosUsuario'] = null;

        // Definições
        $paramAdicional = '/api/v' . $apiVersao . '/' . $apiMetodo . '/' . $apiParam . '/' . $emailUsuario;

        try {
            $cliente = new Client(['base_uri' => $apiUrl, 'verify' => false]);
            $requisicao = $cliente->get($paramAdicional);
            $resposta = $requisicao->getBody()->getContents();
            $dados = json_decode($resposta, true);
            $dadosUsuario = $dados['data'];
        } catch (\Exception $e) {
            $msgErro = '';
            $msgErro .= '<!-- ' . PHP_EOL;
            $msgErro .= 'Erro no Serviço:' . $apiUrl . $paramAdicional . PHP_EOL;
            $msgErro .= 'Nº: ' . $e->getCode() . PHP_EOL;
            $msgErro .= 'Descrição: ' . $e->getMessage() . PHP_EOL;
            $msgErro .= ' -->';

            // Mensagem de erro, propositalmente, não apresentada
            $retorno['mensagem'] = $msgErro;

            return $retorno;
        }

        $retorno['sucesso'] = true;
        $retorno['mensagem'] = '';
        $retorno['dadosUsuario'] = $dadosUsuario;

        return $retorno;
    }
}
