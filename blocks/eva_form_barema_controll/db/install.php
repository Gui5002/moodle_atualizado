<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_block_eva_form_barema_controll_install()
{
    global $DB;
    $dbman = $DB->get_manager();

    // === Adiciona campos personalizados à tabela user ===
    $table = new xmldb_table('user');
    $fields = [
        new xmldb_field('siape', XMLDB_TYPE_CHAR, '20'),
        new xmldb_field('ds_cargo', XMLDB_TYPE_CHAR, '100'),
        new xmldb_field('cpf', XMLDB_TYPE_CHAR, '20'),
        new xmldb_field('lotacao', XMLDB_TYPE_CHAR, '100'),
        new xmldb_field('siglaexercicio', XMLDB_TYPE_CHAR, '10'),
        new xmldb_field('exercicio', XMLDB_TYPE_CHAR, '10'),
    ];
    foreach ($fields as $field) {
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    // === Cria tabela mdl_user_cargo se não existir ===
    $cargo_table = new xmldb_table('user_cargo');
    if (!$dbman->table_exists($cargo_table)) {
        $cargo_table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $cargo_table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $cargo_table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $dbman->create_table($cargo_table);

        // Inserir cargos iniciais
        $cargos = [
            [1, 'Procurador da Fazenda Nacional'],
            [2, 'Procurador do Banco Central'],
            [3, 'Servidor administrativo PGFN'],
            [4, 'Estágiarios'],
            [5, 'Outros']
        ];

        foreach ($cargos as $cargo) {
            $record = (object) [
                'id' => $cargo[0],
                'name' => $cargo[1]
            ];
            $DB->insert_record('user_cargo', $record);
        }
    }
    // === Executa views SQL se houver ===
    $sqlfile = __DIR__ . '/views.sql';
    if (file_exists($sqlfile)) {
        $sql = file_get_contents($sqlfile);
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $statement) {
            try {
                if (!empty($statement)) {
                    $DB->execute($statement);
                }
            } catch (Exception $e) {
                debugging('Erro ao executar view: ' . $e->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }
}