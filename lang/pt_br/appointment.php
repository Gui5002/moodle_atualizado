<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Strings for component 'appointment', language 'pt_br', version '3.11'.
 *
 * @package     appointment
 * @category    string
 * @copyright   1999 Martin Dougiamas and contributors
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['addingappointment'] = 'Adicionar reunião';
$string['addingappointments'] = 'Adicionar reuniões';
$string['addingsession'] = 'Adicionar uma nova seção em {$a}';
$string['addmanageremailaddress'] = 'Adicionar um endereço de email para um gerente';
$string['addmanageremailinstruction'] = 'Você não inseriu anteriormente o endereço de e-mail do seu gerente. Por favor, digite-o abaixo para se inscrever para esta sessão.';
$string['addnewfield'] = 'Adicionar um novo campo personalizado';
$string['addnewfieldlink'] = 'Criar um novo campo personalizado';
$string['addremoveattendees'] = 'Adicionar/remover participantes';
$string['addsession'] = 'Adicionar sessão';
$string['addtimeframe'] = 'Adicionar período de tempo';
$string['advanced'] = 'Avançado';
$string['allowcancellations'] = 'Permitir cancelamento';
$string['allowcancellations_help'] = 'Se marcado, os participantes da sessão poderão cancelar suas inscrições';
$string['allowcancellationsdefault'] = 'Permitir cancelamento padrão';
$string['allowcancellationsdefault_help'] = 'Se marcada, a configuração padrão, ao criar novas sessões para esta atividade, será permitir cancelamentos de inscrição';
$string['allowwaitlist'] = 'Permitir lista de espera';
$string['allowwaitlist_help'] = 'Quando "Permitir lista de espera" for verificado, os estudantes poderão se inscrever para uma sessão de Reunião, mesmo que já esteja cheia.<br />

Quando um estudante se inscreve para uma sessão que já está cheia, ele receberá um e-mail avisando que foi colocado na lista de espera para a sessão e será notificado quando uma reserva estiver disponível.';
$string['alreadysignedup'] = 'Você já se inscreveu para esta atividade de Reunião.';
$string['appointment'] = 'Reunião';
$string['appointment:addattendees'] = 'Acrescentar os participantes a uma sessão de Reunião';
$string['appointment:addinstance'] = 'Adicionar instância';
$string['appointment:configurecancellation'] = 'Permitir a configuração de cancelamentos de inscrição, ao adicionar/editar uma atividade de reunião.';
$string['appointment:editsessions'] = 'Adicionar, editar, copiar e apagar sessões de Reunião';
$string['appointment:managecustomfields'] = 'Gerenciar campos personalizados para a sessão de Reunião.';
$string['appointment:overbook'] = 'Inscreva-se em sessões completas que não tenham lista de espera ativada.';
$string['appointment:removeattendees'] = 'Retirar os participantes de uma sessão de Reunião';
$string['appointment:signup'] = 'Inscreva-se para uma sessão';
$string['appointment:takeattendance'] = 'Assistir';
$string['appointment:view'] = 'Visualizar Atividades e sessões de Reunião';
$string['appointment:viewattendees'] = 'Visualizar lista de presença e participantes';
$string['appointment:viewcancellations'] = 'Visualizar cancelamentos';
$string['appointmentactivity'] = 'Atividade de Reunião';
$string['appointmentcustomfields'] = 'Campos personalizados de Reunião';
$string['appointmentname'] = 'Nome da Reunião';
$string['appointments'] = 'Reuniões';
$string['appointmentsession'] = 'Sessão de Reunião';
$string['appointmentsessions'] = 'Sessões de Reunião';
$string['appointmentsettings'] = 'Configurações de Reunião';
$string['approvalreqd'] = 'Aprovação necessária';
$string['approve'] = 'Aprovar';
$string['book'] = 'Livro';
$string['bookingcancelled'] = 'Sua Reserva foi cancelada';
$string['bookingcompleted'] = 'Sua Reserva foi concluída';
$string['bookingfull'] = 'Cheio';
$string['bookingopen'] = 'Aberto';
$string['break'] = 'Pausa';
$string['calendaroptions'] = 'Opções do calendário';
$string['cancel'] = 'Cancelar';
$string['cancelbooking'] = 'Cancelar reseva';
$string['cancelbookingfor'] = 'Cancelar reserva para {$a}';
$string['cancellation'] = 'Cancelamento';
$string['cancellationconfirm'] = 'Tem certeza de que deseja cancelar sua reserva para esta sessão?';
$string['cancellationinstrmngr'] = '# Aviso para o gerente';
$string['cancellationinstrmngr_help'] = 'Quando **Enviar aviso ao gerente** está marcado, o texto no campo **Aviso ao gerente** é enviado ao gerente de um estudante informando que ele cancelou uma reserva de compromisso.';
$string['cancellationmessage'] = 'Mensagem de cancelamento';
$string['cancellationmessage_help'] = 'Esta mensagem é enviada sempre que os usuários cancelam sua reserva para uma sessão.';
$string['cancellations'] = 'Cancelamentos';
$string['cancellationsent'] = 'Você deve receber imediatamente um e-mail de cancelamento.';
$string['cancellationsentmgr'] = 'Você e seu gerente devem receber imediatamente um e-mail de cancelamento.';
$string['cancellationstablesummary'] = 'Lista de pessoas que cancelaram suas inscrições na sessão.';
$string['cancelreason'] = 'Razão para cancelamento';
$string['cannotapproveatcapacity'] = 'Você não pode aprovar mais participantes, pois esta sessão está cheia.';
$string['cannotsignupsessioninprogress'] = 'Você não pode se inscrever, esta sessão está em andamento';
$string['cannotsignupsessionover'] = 'Você não pode se inscrever, esta sessão acabou.';
$string['capacity'] = 'Capacidade';
$string['capacity_help'] = '**Capacidade** é o número de vagas disponíveis em uma sessão.

Quando uma sessão de compromisso atinge a capacidade, os detalhes da sessão não aparecem na página do curso. Os detalhes aparecerão em cinza na página \'Visualizar todas as sessões\' e o estudante não poderá se inscrever na sessão.
&nbsp;';
$string['changemanageremailaddress'] = 'Alterar endereço de e-mail do gerente';
$string['changemanageremailinstruction'] = 'Insira o endereço de e-mail do seu gerente atual abaixo.';
$string['clearall'] = 'Limpar todos';
$string['closed'] = 'Fechado';
$string['completionbooked'] = 'O estudante deve agendar um horário para concluir esta atividade';
$string['completiondetail:booked'] = 'Agendar um horário';
$string['confirm'] = 'Confirmar';
$string['confirmation'] = 'Confirmação';
$string['confirmationinstrmngr'] = '# Aviso para o gerente';
$string['confirmationinstrmngr_help'] = 'Quando a opção "Enviar aviso ao gerente" está marcada, o texto no campo "Aviso ao gerente" é enviado a um gerente informando que um funcionário se inscreveu para uma sessão de compromisso.';
$string['confirmationmessage'] = 'Mensagem de confirmação';
$string['confirmationmessage_help'] = 'Esta mensagem é enviada sempre que os usuários se inscrevem em uma sessão.';
$string['confirmationsent'] = 'Você deve receber imediatamente um e-mail de confirmação.';
$string['confirmationsentmgr'] = 'Você e seu gerente devem receber imediatamente um e-mail de confirmação.';
$string['confirmcancelbooking'] = 'Confirmar cancelamento';
$string['confirmmanageremailaddress'] = 'Confirmar endereço de e-mail do gerente';
$string['confirmmanageremailaddressquestion'] = '<b>{$a}</b> ainda é o endereço de e-mail do seu gerente?';
$string['confirmmanageremailinstruction1'] = 'Você inseriu anteriormente o seguinte como endereço de e-mail do seu gerente:';
$string['confirmmanageremailinstruction2'] = 'Este ainda é o endereço de e-mail do seu gerente?';
$string['continuetologin'] = 'Clique em continuar para entrar';
$string['copy'] = 'Copiar';
$string['cost'] = 'Custo';
$string['costheading'] = 'Custo da sessão';
$string['courseresetsessions'] = 'Remover todas as sessões';
$string['courseresetsignups'] = 'Remover todas as inscrições de sessão';
$string['crontask'] = 'Tarefas de manutenção da reunião';
$string['currentstatus'] = 'Status atual';
$string['customfieldsheading'] = 'Campos de sessão personalizados';
$string['customisednotifications'] = 'Notificações personalizadas';
$string['date'] = 'Data';
$string['dateadd'] = 'Adicionar nova data';
$string['dateremove'] = 'Remover esta data';
$string['datesignedup'] = 'Data de inscrição';
$string['datetext'] = 'Você foi adicionado nessa data';
$string['decidelater'] = 'Decidir depois';
$string['decline'] = 'Rejeitar';
$string['delete'] = 'Excluir';
$string['deleteall'] = 'Excluir tudo';
$string['deleteappointment'] = 'Excluir reunião';
$string['deleteappointmentconfirm'] = 'Tem certeza de que deseja excluir esta reunião e todas as suas reservas?';
$string['deletedate'] = 'Excluir data';
$string['deletesession'] = 'Excluir sessão';
$string['deletetimeframe'] = 'Excluir calendário';
$string['deletingsession'] = 'Excluindo sessão em {$a}';
$string['description'] = 'Texto de introdução';
$string['description_help'] = '**Descrição** é exibida quando um estudante se inscreve em uma sessão da reunião.

A **Descrição** também é exibida no calendário de treinamento.';
$string['details'] = 'Detalhes';
$string['details_help'] = 'Os detalhes são monitorados por sessão.
Se o texto for preenchido no campo de detalhes, o texto de detalhes será exibido na página de inscrição do usuário.
Por padrão, o texto de detalhes também aparece nas mensagens de e-mail de confirmação, lembrete, lista de espera e cancelamento.';
$string['discountcode'] = 'Código de desconto';
$string['discountcode_help'] = 'Código de desconto é o código necessário para que o custo de desconto seja rastreado para o treinamento de um membro da equipe.
Se o funcionário não inserir o código de desconto, o custo normal aparecerá no registro de treinamento.';
$string['discountcodelearner'] = 'Código de desconto';
$string['discountcodelearner_help'] = 'Se você souber o código de desconto, insira-o aqui. Se você deixar este campo em branco, será cobrado o custo normal deste evento';
$string['discountcost'] = 'Custo de desconto';
$string['discountcost_help'] = 'O custo do desconto é o valor em dólares cobrado dos membros da equipe que possuem um ID de membro.
Se um membro da equipe inserir um ID de membro ao se inscrever em uma sessão, o custo do desconto aparecerá na coluna de custo no boletim de notas do curso.';
$string['due'] = 'vencimento';
$string['duplicate'] = 'Duplicar';
$string['duplicateappointment'] = 'Duplicar reunião';
$string['duration'] = 'Duração';
$string['duration_help'] = '**Duração** é a duração total do treinamento em horas.
Por exemplo:
* "2 horas" é inserido como **2** ou **2:00**
* "1 hora e 30 minutos" é inserido como **1:30**
* "45 minutos" é inserido como **0:45**
* "20 minutos" é inserido como **0:20**.

Se o treinamento ocorrer em dois ou mais períodos de tempo, a duração será o total combinado.';
$string['early'] = '{$a} antecipado';
$string['edit'] = 'Editar';
$string['editappointment'] = 'Editar reunião';
$string['editingappointment'] = 'Editando reunião';
$string['editingsession'] = 'Editando sessão em {$a}';
$string['editsession'] = 'Editar sessão';
$string['email:instrmngr'] = 'Notificação para o gerente';
$string['email:message'] = 'Mensagem';
$string['email:subject'] = 'Assunto';
$string['emailmanager'] = 'Enviar notificação ao gerente';
$string['emailmanagercancellation'] = '# Enviar notificação ao gerente';
$string['emailmanagercancellation_help'] = 'Quando a opção "Enviar notificação ao gerente" estiver marcada, um e-mail será enviado ao gerente do estudante informando que a reserva da reunião foi cancelada.';
$string['emailmanagerconfirmation'] = '# Enviar notificação para o gerente';
$string['emailmanagerconfirmation_help'] = 'Quando "Enviar notificação ao gerente" estiver marcado, um e-mail de confirmação será enviado ao gerente do estudante quando o estudante se inscrever para uma sessão da reunião.';
$string['emailmanagerreminder'] = '# Enviar notificação ao gerente';
$string['emailmanagerreminder_help'] = 'Quando "Enviar notificação ao gerente" estiver marcado, uma mensagem de lembrete será enviada ao gerente do estudante alguns dias antes da data de início da sessão da reunião.';
$string['empty'] = 'Vazio';
$string['emptylocation'] = 'A localização não foi preenchida';
$string['enablewaitlist'] = 'Habilitar lista de espera';
$string['endtime'] = 'Encerramento';
$string['enrolled'] = 'inscrito';
$string['entitiyappointment'] = 'Reunião';
$string['entitiysession'] = 'Sessão';
$string['error:addalreadysignedupattendee'] = '{$a} já está inscrito para esta atividade de reunião.';
$string['error:addattendee'] = 'Não foi possível adicionar {$a} à sessão.';
$string['error:appointmentfailedupdatestatus'] = 'A reunião falhou ao atualizar o status do usuário';
$string['error:cancelbooking'] = 'Ocorreu um problema ao cancelar sua reserva';
$string['error:cancellationsnotallowed'] = 'Você não tem permissão para cancelar esta inscrição.';
$string['error:cannotconfigurecustomfields'] = 'Você não tem permissão para configurar campos personalizados.';
$string['error:cannotemailmanager'] = 'Enviou um e-mail de lembrete para o envio de ID {$a->submissionid} para o usuário {$a->userid}, mas não foi possível enviar a mensagem para o endereço de e-mail do gerente do usuário ({$a->manageremail}).';
$string['error:cannotemailuser'] = 'Não foi possível enviar e-mail para o envio de ID {$a->submissionid} para o usuário {$a->userid} ({$a->useremail}).';
$string['error:cannotsendconfirmationmanager'] = 'Uma mensagem de confirmação foi enviada para sua conta de e-mail, mas ocorreu um problema ao enviar a mensagem de confirmação para o endereço de e-mail de seu gerente.';
$string['error:cannotsendconfirmationthirdparty'] = 'Uma mensagem de confirmação foi enviada para sua conta de e-mail e para a conta de e-mail de seu gerente, mas ocorreu um problema ao enviar a mensagem de confirmação para o endereço de e-mail do terceiro.';
$string['error:cannotsendconfirmationuser'] = 'Ocorreu um problema ao enviar a mensagem de confirmação para sua conta de e-mail.';
$string['error:cannotsendconfirmationusermanager'] = 'Não foi possível enviar uma mensagem de confirmação para seu endereço de e-mail e para o endereço de e-mail de seu gerente.';
$string['error:cannotsendrequestmanager'] = 'Ocorreu um problema ao enviar a mensagem de solicitação de inscrição para a conta de e-mail do seu gerente.';
$string['error:cannotsendrequestuser'] = 'Ocorreu um problema ao enviar a mensagem de solicitação de inscrição para sua conta de e-mail.';
$string['error:canttakeattendanceforunstartedsession'] = 'Não é possível atender a uma sessão que ainda não começou.';
$string['error:couldnotaddfield'] = 'Não foi possível adicionar o campo de sessão personalizado.';
$string['error:couldnotaddsession'] = 'Não foi possível adicionar a sessão';
$string['error:couldnotaddtrainer'] = 'Não foi possível salvar o novo instrutor da sessão de reunião';
$string['error:couldnotcopysession'] = 'Não foi possível copiar a sessão';
$string['error:couldnotdeletefield'] = 'Não foi possível excluir o campo de sessão personalizado';
$string['error:couldnotdeletesession'] = 'Não foi possível excluir a sessão';
$string['error:couldnotdeletetrainer'] = 'Não foi possível excluir um instrutor de sessão de reunião';
$string['error:couldnotfindsession'] = 'Não foi possível encontrar a sessão inserida recentemente';
$string['error:couldnotsavecustomfield'] = 'Não foi possível salvar o campo personalizado';
$string['error:couldnotupdateappointmentrecord'] = 'Não foi possível atualizar o registro de inscrição da reunião no banco de dados';
$string['error:couldnotupdatecalendar'] = 'Não foi possível atualizar o evento da sessão no calendário.';
$string['error:couldnotupdatefield'] = 'Não foi possível atualizar o campo personalizado de sessão.';
$string['error:couldnotupdatemanageremail'] = 'Não foi possível atualizar o endereço de e-mail do gerente.';
$string['error:couldnotupdatesession'] = 'Não foi possível atualizar a sessão';
$string['error:coursemisconfigured'] = 'O curso está configurado incorretamente';
$string['error:eventoccurred'] = 'Você não pode cancelar um evento que já ocorreu.';
$string['error:invaliduserid'] = 'ID de usuário inválido';
$string['error:manageremailaddressmissing'] = 'Atualmente você não está atribuído como um gerente no sistema. Entre em contato com o administrador do site.';
$string['error:sessionsplitexceeds'] = 'O tempo parcial da sessão excede a duração da sessão.';
$string['error:sessionstartafterend'] = 'O horário de início da sessão é posterior ao horário de término da sessão.';
$string['errorcannoteditsessions'] = 'Você não tem permissão para editar sessões';
$string['errorcannotviewappointment'] = 'Você não tem permissão para visualizar este compromisso';
$string['errorcannotviewattendees'] = 'Você não tem permissão para ver os participantes';
$string['eventaddsession'] = 'Sessão adicionada';
$string['eventapproverequests'] = 'Solicitações de aprovação de sessão';
$string['eventattendancetaken'] = 'Participação na sessão realizada';
$string['eventattendeesviewed'] = 'Participantes da sessão visualizados';
$string['eventcancelbooking'] = 'Sessão reserva cancelada';
$string['eventcourseappointmentviewed'] = 'Instâncias de agendamento de curso visualizadas';
$string['eventcoursemoduleviewed'] = 'Instância do módulo de agendamento visualizada';
$string['eventdeletesession'] = 'Sessão excluída';
$string['eventsignup'] = 'Inscrição na sessão';
$string['eventupdatesession'] = 'Sessão atualizada';
$string['full'] = 'A data está ocupada';
$string['fullfilter'] = 'Completa';
$string['goback'] = 'Voltar';
$string['icalendarheading'] = 'Anexos iCalendar';
$string['joinwaitlist'] = 'Entrar na lista de espera';
$string['location'] = 'Localização';
$string['managecustomfields'] = 'Gerenciar campos personalizados';
$string['modulename_help'] = 'O agendamento é um módulo de atividades para os professores acompanharem as atividades presenciais.

Ele permite que o professor configure um sistema de reserva para uma ou mais sessões presenciais ou em sala de aula, para os estudantes se inscreverem.

Este módulo de atividades é adequado para sessões individuais ou atividades multisessões, como seminários.

As sessões podem ter diversas datas e horários em uma única atividade de Compromisso e podem ser criadas em massa usando os intervalos de tempo especificados na configuração.

A atividade permite o acompanhamento de frequência.';
$string['modulename_link'] = 'Compromissos';
$string['modulenameplural'] = 'Compromissos';
$string['multipleappointments'] = 'Múltiplos compromissos';
$string['noactionableunapprovedrequests'] = 'Nenhuma solicitação não aprovada sem ação';
$string['remindermessage'] = 'Mensagem de lembrete';
$string['remindermessage_help'] = 'Esta mensagem é enviada alguns dias antes da data de início de uma sessão.';
$string['reminderperiod'] = 'Dias antes da mensagem ser enviada';
$string['reminderperiod_help'] = 'A mensagem de lembrete será enviada alguns dias antes do início da sessão.';
$string['requestmessage'] = 'Mensagem de solicitação';
