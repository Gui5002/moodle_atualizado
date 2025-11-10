<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language strings for the evadeclaration module
 *
 * @package    mod
 * @subpackage evadeclaration
 * @copyright  Renata C Neves 2024 <xangay@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['modulename'] = 'Declaração EVA';
$string['modulenameplural'] = 'declarações EVA';
$string['pluginname'] = 'Declaração EVA';
$string['viewdeclarationviews'] = '{$a} declarações emitidas';
$string['summaryofattempts'] = 'Resumo das declaraçãos emitidas';
$string['issued'] = 'emitidas';
$string['coursegrade'] = 'Nota do curso';
$string['getdeclaration'] = 'Obtenha sua declaração';
$string['awardedto'] = 'Obtido para';
$string['receiveddate'] = 'Data de Criação';
$string['grade'] = 'Nota';
$string['code'] = 'Código';
$string['report'] = 'Relatório';
$string['opendownload'] = 'Pressione o botão abaixo para salvar a sua declaração no seu computador.';
$string['openemail'] = 'Pressione o botão abaixo e sua declaração será enviada por email.';
$string['openwindow'] = 'Pressione o botão abaixo para visualizar a sua declaração em uma nova tela.';
$string['hours'] = 'horas';
$string['keywords'] = 'declaration, course, pdf, moodle';
$string['pluginadministration'] = 'Administração de declarações';
$string['deletissueddeclarations'] = 'Remover as declarações emitidas';
$string['nodeclarationsissued'] = 'Nenhum declaração emitida';
// Form.
$string['declarationname'] = 'Nome da declaração';
$string['declarationimage'] = 'Arquivo de Imagem da declaração';
$string['declarationtext'] = 'Texto da declaração';
$string['declarationtextx'] = 'Posição Horizontal do texto da declaração';
$string['declarationtexty'] = 'Posição Vertical do texto da declaração';
$string['height'] = 'Altura da declaração';
$string['width'] = 'Largura da declaração';
$string['coursename'] = 'Nome alternativo do curso';
$string['intro'] = 'Introdução';
$string['printoutcome'] = 'Imprimir resultado (Outcome)';
$string['printdate'] = 'Tipo de data da declaração';
// Second Page.
$string['secondpageoptions'] = 'Página de Verso da declaração';
$string['enablesecondpage'] = 'Ativar o verso da declaração';
$string['enablesecondpage_help'] = 'Ativa a edição do verso da declaração, se estiver desativado, somente o código QR da declaração será impresso (se código QR estiver ativo)';
$string['secondimage'] = 'Imagem do verso do declaration';
$string['secondimage_help'] = 'Esta figura será usada no verso da declaração';
$string['secondpagetext'] = 'Texto do verso da declaração';
$string['secondpagex'] = 'Posição Horizontal do texto do verso';
$string['secondpagey'] = 'Posição Vertical do texto do verso';
$string['secondtextposition'] = 'Posição do texto do verso';
$string['secondtextposition_help'] = 'As coordenadas XY (em milímetros) do texto do verso';
// QR Code.
$string['printqrcode'] = 'Imprimir o QR Code da declaração';
$string['printqrcode_help'] = 'Habilita ou desabilita a impressão do QR Code da declaração';
$string['codex'] = 'Posição Horizontal do QR Code da declaração';
$string['codey'] = 'Posição Vertical do QR Code da declaração';
$string['qrcodeposition'] = 'Posicionamento do QR Code da declaração';
$string['qrcodeposition_help'] = 'Essas são as coordenadas XY (em milímetros) do QR Code da declaração';
$string['defaultcodex'] = 'Posição Horizontal padrão do QR code da declaração';
$string['defaultcodey'] = 'Posição Vertical padrão do QR code da declaração';
// Date options.
$string['issueddate'] = 'Data da emissão';
$string['completiondate'] = 'Data do fim do curso';
$string['datefmt'] = 'Formato da Data';
// Date format options.
$string['userdateformat'] = 'Formato definido pelas definições do usuário';
$string['printgrade'] = 'Tipo de nota da declaração';
$string['gradefmt'] = 'Formato da nota';
// Grade format options.
$string['gradeletter'] = 'Nota por Conceito';
$string['gradepercent'] = 'Nota por percentual';
$string['gradepoints'] = 'Nota por pontos';
$string['coursetimereq'] = 'Minutos mínimos de participação no curso';
$string['emailteachers'] = 'Enviar email para os Professores';
$string['emailothers'] = 'Enviar email para outros';
$string['emailfrom'] = 'Nome alternativo do remetente do email';
$string['delivery'] = 'Envio';
// Delivery options.
$string['openbrowser'] = 'Visualizar em uma nova janela';
$string['download'] = 'Forçar o download';
$string['emaildeclaration'] = 'por Email';
$string['nodelivering'] = 'Sem envio, o usuário vai receber a declaração por outros meios';
// Form options help text.
$string['declarationname_help'] = 'Nome da declaração';
$string['declarationtext_help'] = 'Este é o texto que será usado na declaração, algums marcadores especiais serão substituidos por variáveis da declaração, como o nome do curos, nome do estudante, notas...
Os marcadores são:

<ul>
<li>{USERNAME} -> Nome completo do aluno</li>
<li>{COURSENAME} -> Nome compledo do curso (ou o que estiver definido em "Nome Alternativo do Curso")</li>
<li>{GRADE} -> Nota formatada</li>
<li>{DATE} -> Data formatada</li>
<li>{OUTCOME} -> Resultados (Outcomes)</li>
<li>{TEACHERS} -> Lista de professores</li>
<li>{IDNUMBER} -> User id number</li>
<li>{FIRSTNAME} -> Nome</li>
<li>{LASTNAME} -> Sobrenome</li>
<li>{EMAIL} -> E-mail</li>
<li>{CPF} -> CPF</li>
<li>{SKYPE} -> Skype</li>
<li>{YAHOO} -> Yahoo messenger</li>
<li>{AIM} -> AIM</li>
<li>{MSN} -> MSN</li>
<li>{PHONE1} -> 1° Número de telefone</li>
<li>{PHONE2} -> 2° Número de telefone</li>
<li>{INSTITUTION} -> Instituição</li>
<li>{DEPARTMENT} -> Departamento</li>
<li>{ADDRESS} -> Endereço</li>
<li>{CITY} -> Cidade</li>
<li>{COUNTRY} -> País</li>
<li>{URL} -> Home-page</li>
<li>{declarationCODE} -> Texto do código da declaração</li>
<li>{USERROLENAME} -> Nome do papel do usuário no curso</li>
<li>{TIMESTART} -> Data de inscrição do usuário no curso</li>
<li>{USERIMAGE} -> Imagem do perfil do usuário</li>
<li>{USERRESULTS} -> Resultados (notas) dos usuarios de outras atividades do curso</li>
<li>{PROFILE_xxxx} -> Campos personalizados</li>
</ul>
<p>
Para usar campos personalizados deve usar o prefixo "PROFILE_", por exemplo, se criar um campo com a abreviação (shortname) de aniversario, então deve-se usar o marcador
"PROFILE_ANIVERSARIO"
O texto pode ser um HTML básico, com fontes básicas do HTLM, tabelas, mas evitar o uso de posicionamento.</p>';


$string['textposition'] = 'Posicionamento do Texto da declaração';
$string['textposition_help'] = 'Essas são as coordenadas XY (em milímetros) do texto da declaração';
$string['size'] = 'Tamanho da declaração';
$string['size_help'] = 'Esse é o tamanho, Largura e Altura (em milímetros) da declaração, o padrão é A4 paisagem';
$string['coursename_help'] = 'Nome Alternativo do curso.<br>
<p>O nome alternativo do curso irá substituir o nome do curso no textmark
<pre>{COURSENAME}</pre>, e também no nome do arquivo gerado para download.</p>
<p>Muito útil quando um curso tem um nome, mas na declaração deveria parecer outro nome.</p>
<br><br>
<p>Por exemplo:<br><br>
<pre>Nome do curso: "Trabalho de final de curso"</pre><br>
<pre>Nome alternativo "Português Instrumental"</pre><br><br>
<p>Então, no texto de declaração, a textmark <pre>{COURSENAME}</pre> vai ser substituída por
<pre>"Português Instrumental"</pre>, e o nome do arquivo da declaração será
<pre>portuges_instrumental-<NOME_DA_DECLARAÇÂO>.pdf</pre></p>';
$string['declarationimage_help'] = 'Esta figura será usada na declaração';

$string['printoutcome_help'] = 'Você pode escolher qualquer resultado (outcome) definido neste curso. Será impresso o nome do resultado e o resultado recebido  pelo usuário. Um exemplo poderia ser: Resultado: Proficiente.';

$string['printdate_help'] = 'Esta é a data que será impressa, você pose escolher entre a data que o aluno completou o curso, ou a data de emissão da declaração.
Também pode-se escolher a data que uma decla atividade foi corrigida, se em algum dos casos o aluno não tiver a data, então a data de emissão será usada.';
$string['datefmt_help'] = 'Coloque um formato de data válido aceito pelo PHP (<a href="http://www.php.net/manual/en/function.strftime.php"> Date Formats</a>). ou deixe-o em branco para usar o valor de formatação padrão definido pela a configuração de idioma do usuário.';
$string['printgrade_help'] = 'Pode-se escolher a nota que será impressa na declaração, esta pode ser a nota final do curso ou a nota em uma atividade.';
$string['gradefmt_help'] = 'Pode-se escolher o formato da nota que são:<br>
<ul>
<li>Nota por percentual: Imprime a nota como um percentual.</li>
<li>Nota pot pontos: Imprime a nota por pontos, o valor da nota tirada.</li>
<li>Nota por conceito: Imprime o conceito relacionado a nota obtida (A, A+, B, ...).</li>
</ul>';

$string['coursetimereq_help'] = 'Coloque o tempo mínimo de participação (em minutos) que um aluno deve ter para conseguir obter a declaração';
$string['emailteachers_help'] = 'Quando habilitado, os professores recebem os emails toda vez que um aluno emitir um declaração.';
$string['emailothers_help'] = 'Digite os endereços de emails que vão receber o alerta de emissão de declaração.';
$string['emailfrom_help'] = 'Nome a ser usado como remetente dos email enviadas';
$string['delivery_help'] = 'Escolha como a declaração deve ser entregue aos alunos:<br>
<ul>
<li>Visualizar em uma nova janela: Abre uma nova janela no navegador do aluno contendo a declaração.</li>
<li>Forçar o download: Abre uma janela de download de arquivo para o aluno salvar em seu computador.</li>
<li>por Email: Envia a declaração para o email do aluno, e abre a declaração em uma nova janela do navegador.</li>
</ul><p>
Depois que estudante emite sua declaração, se ele clicar na atividade declaração aparecerá a data de emissão da declaração e ele poderá revisar odeclaração emitida</p>';


// Form Sections.
$string['issueoptions'] = 'Opções de Emissão';
$string['designoptions'] = 'Opções de Design';

// Emails text.
$string['emailstudentsubject'] = 'sua declaração do curso {$a->course}';
$string['emailstudenttext'] = '
Olá {$a->username},

	Segue em anexo a declaração do curso: {$a->course}.


ESTA É UMA MENSAGEM AUTOMÁTICA, NÃO RESPONDA POR FAVOR';

$string['emailteachermail'] = '
{$a->student} recebeu a declaração: \'{$a->declaration}\' para o curso
{$a->course}.

Você pode vê-lo aqui:

    {$a->url}';

$string['emailteachermailhtml'] = '
{$a->student} recebeu a declaração: \'<i>{$a->declaration}</i>\'
para o curso {$a->course}.

Você pode vê-lo aqui:

    <a href="{$a->url}">declaration Report</a>.';

    // Admin settings page.
$string['defaultwidth'] = 'Largura Padrão';
$string['defaultheight'] = 'Altura Padrão';
$string['defaultdeclarationtextx'] = 'Posição Horizontal padrão do texto da declaração';
$string['defaultdeclarationtexty'] = 'Posição Vertical padrão do texto da declaração';

// Erros.
$string['filenotfound'] = 'Arquivo não encontrado';
$string['cantdeleteissue'] = 'Ocorreu um erro ao remover as declarações emitidas';
$string['requiredtimenotmet'] = 'Você precisa ter ao menos {$a->requiredtime} minutos nesse curso para emitir este declaração';

// Settings.
$string['decllifetime'] = 'Manter as declarações emitidas por: (em Meses)';
$string['decllifetime_help'] = 'Está opção especifica por quanto tempo deve ser guardado um declaração emitida. declaraçãos emitidas mais velhos que o tempo determinado nesta opção será automaticamente removidos.';
$string['neverdeleteoption'] = 'Nunca remover';

$string['variablesoptions'] = 'Outras Opções';
$string['getdeclaration'] = 'Obter a declaração';
$string['verifydeclaration'] = 'Verificar declaração';

$string['qrcodefirstpage'] = 'Imprimir o código QR na primeira página';
$string['qrcodefirstpage_help'] = 'Imprime o código QR na primeira página';

// Tabs String.
$string['standardview'] = 'Emitir um declaração de teste';
$string['issuedview'] = 'declaraçãos emitidas';
$string['bulkview'] = 'Operações em lote';

$string['cantissue'] = 'a declaração não pode ser emitida pois o usuário não atigiu a meta da atividade';

// Bulk Texts.
$string['onepdf'] = 'Download de um único arquivo pdf com todos as declarações';
$string['multipdf'] = 'Download de um arquivo zip com os pdfs dos declidicados';
$string['sendtoemail'] = 'Enviar as declarações para o email do usuário';
$string['showusers'] = 'Mostrar';
$string['completedusers'] = 'Usuários que atingiram os objetivos definidos';
$string['allusers'] = 'Todos os usuários';
$string['bulkaction'] = 'Escolha a operação em lote';
$string['bulkbuttonlabel'] = 'Enviar';
$string['emailsent'] = 'Os emails foram enviadas';

$string['issueddownload'] = 'declaração [id: {$a}] baixado';
$string['defaultperpage'] = 'Por página';
$string['defaultperpage_help'] = 'Quantidade de declaraçãos exibidos por página (Max. 200)';
$string['declarationverification'] = 'Verificação da declaração';
$string['invalidcode'] = 'Código da declaração é inválido';

// For Capabilities.
$string['evadeclaration:addinstance'] = "Adicionar uma atividade Declaração EVA";
$string['evadeclaration:manage'] = "Gerenciar uma atividade Declaração EVA";
$string['evadeclaration:view'] = "Visualizar um Declaração EVA";

$string['usercontextnotfound'] = 'Contexto de usuário não encontrado';
$string['usernotfound'] = 'Usuário não encontrado';
$string['coursenotfound'] = 'Curso não encontrado';
$string['issueddeclarationnotfound'] = 'declaração não encontrado';
$string['awardedsubject'] = 'Notificão de obtenção de declaração: {$a->declaration} emitida para {$a->student}';
$string['declarationnot'] = 'Simple declaration instance not found';
$string['modulename_help'] = 'A atividade Declaração EVA possibilita que seja criada declaraçãos costumisáveis que podem ser emitidas pelos participantes que completaram os requerimentos espicificados.';
$string['timestartdatefmt'] = 'Formato da data de inicio da inscrição';
$string['timestartdatefmt_help'] = 'Coloque um formato de data válido aceito pelo PHP (<a href="http://www.php.net/manual/pt_BR/function.strftime.php">Formato de datas</a>). ou deixe-o em branco para usar o valor de formatação padrão definido pela a configuração de idioma do usuário.';
$string['declarationcopy'] = 'CÓPIA';
$string['upgradeerror'] = 'Erro durante o upgrade $a';
$string['notreceived'] = 'declaração não emitida';

// Verify envent.
$string['eventdeclaration_verified'] = 'declaração verificado';
$string['eventdeclaration_verified_description'] = 'O usuário com o id {$a->userid} verificou a declaração com o id {$a->declarationid}, emetido para o usuário com id {$a->decliticate_userid}.';

$string['deleteall'] = "Remover todos";
$string['deleteselected'] = "Remover Selecionados";
