MANUAL DE INSTALAÇÃO DO BLOCO TRAINING SUGGESTION
==================================================

Passo 1 -> Jogue a pasta do bloco na pasta de blocos dentro do servidor requisitado.
Passo 2 -> Vá até a página de administração do moodle e instale o bloco conforme o moodle for avançando
Passo 3 -> Com o bloco instalado, abra o arquivo ddl.training_suggestion.sql e execute as duas últimas linhas para alteração da tabela mdl_eva_training_suggestion e mdl_eva_superior_organ
    -> ALTER TABLE moodle.mdl_eva_training_suggestion ADD dt_suggestion TIMESTAMP DEFAULT current_timestamp() NOT NULL;
    -> ALTER TABLE moodle.mdl_eva_superior_organ ADD dt_registry TIMESTAMP DEFAULT current_timestamp() NOT NULL;
Passo 4 -> Agora rode todos os INSERTS que estão no arquivo ddl.training_suggestion.sql para inserir todos os órgãos.

Com isso o bloco está instalado, agora basta informar ao moodle onde ele deve ser visualizado.
