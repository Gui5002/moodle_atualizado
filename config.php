<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$CFG->debug = (E_ALL | E_STRICT);
$CFG->debugdisplay = 1;

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = '127.0.0.1'; // Alterado para 127.0.0.1 para evitar falhas de socket no Windows
$CFG->dbname    = 'moodle';
$CFG->dbuser    = 'root';
$CFG->dbpass    = '';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);
// 1. Força o ID 2 (seu usuário) a ser Administrador do Site
$CFG->siteadmins = '2';

// 2. Desativa o plugin LDAP globalmente e força autenticação manual
$CFG->auth = 'manual';

// 3. Desativa redirecionamentos automáticos de login
$CFG->alternateloginurl = '';
$CFG->preventexecpath = true;

$CFG->wwwroot   = 'http://localhost/moodle';
$CFG->dataroot  = 'D:\\MOODLEDATA\\moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

// Compatibilidade de plugins modernos com Moodle 3.x
if (!defined('FEATURE_MOD_PURPOSE')) {
    define('FEATURE_MOD_PURPOSE', 'mod_purpose');
    define('MOD_PURPOSE_ADMINISTRATION', 'administration');
    define('MOD_PURPOSE_ASSESSMENT', 'assessment');
    define('MOD_PURPOSE_COLLABORATION', 'collaboration');
    define('MOD_PURPOSE_COMMUNICATION', 'communication');
    define('MOD_PURPOSE_CONTENT', 'content');
    define('MOD_PURPOSE_INTERFACE', 'interface');
    define('MOD_PURPOSE_OTHER', 'other');
}

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!