<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
//$CFG->dbhost    = 'localhost';
//$CFG->dbname    = 'moodle';
//$CFG->dbuser    = 'moodle_eva';
//$CFG->dbpass    = 'm00dle@3v@!';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

$CFG->wwwroot   = 'https://eva.agu.gov.br';
$CFG->dataroot  = '/opt/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

// ********************************************************************************
// Configurações que dependem do ambiente
// ********************************************************************************
$env = 'APPLICATION_ENV';

// NOTA: Ambiente atual deve ser determinado através da variável APPLICATION_ENV.
//       Se não informado, assume que é PRODUÇÃO.
$ambiente = getenv($env) ? getenv($env) : 'production';

$ambPRD = 'production';
$ambHML = 'staging';
$ambDSV = 'development';
// +outros ambientes

$param = array();

$param[$ambPRD]['url']        = 'https://eva.agu.gov.br';
$param[$ambPRD]['db_host']    = '172.18.1.111';
$param[$ambPRD]['db_banco']   = 'moodle_eva_prod';
$param[$ambPRD]['db_usuario'] = 'moodleuser_eva_prod';
$param[$ambPRD]['db_senha']   = 'H_zVpX29mXs$qGauR';
$param[$ambPRD]['apiurl']     = 'http://api-agupessoas.agu.gov.br';
//
//$param[$ambHML]['url']        = 'https://moodleevahom.agu.gov.br';
//$param[$ambHML]['db_host']    = '172.17.24.23';
//$param[$ambHML]['db_banco']   = 'moodle_eva_hom';
//$param[$ambHML]['db_usuario'] = 'root';
//$param[$ambHML]['db_senha']   = '!@gu1720#AL';
//$param[$ambHML]['apiurl']     = 'http://api-pessoashom.agu.gov.br';

//$param[$ambDSV]['url']        = 'https://moodleevadesv.agu.gov.br';
//$param[$ambDSV]['db_host']    = 'localhost';
//$param[$ambDSV]['db_banco']   = 'moodle';
//$param[$ambDSV]['db_usuario'] = 'moodle_eva';
//$param[$ambDSV]['db_senha']   = 'm00dle@3v@!';
//$param[$ambDSV]['apiurl']     = 'http://api-pessoas.agu.gov.br';
// ********************************************************************************

// Configurações do servidor web
$CFG->wwwroot                 = $param[$ambiente]['url'];

// Configurações sobre o banco de dados
$CFG->dbhost                  = $param[$ambiente]['db_host'];
$CFG->dbname                  = $param[$ambiente]['db_banco'];
$CFG->dbuser                  = $param[$ambiente]['db_usuario'];
$CFG->dbpass                  = $param[$ambiente]['db_senha'];

// Configurações sobre o serviço api-pessoas (AGU Pessoas)
$CFG->apiurl                  = $param[$ambiente]['apiurl'];
$CFG->apiversao               = 1;
$CFG->apiparametro	      = 'email';
$CFG->apimetodo               = 'dadospessoa';
//$CFG->apiusuario              = 'eva';
//$CFG->apisenha                = 'eva@123';
//$CFG->apiaplicativo           = 'evaead';

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
$CFG->disableupdateautodeploy = true;
