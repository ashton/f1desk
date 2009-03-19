<?php

require_once('main.php');

/*default*/
handleLanguage(__FILE__);

$IDTicket = $_POST['IDTicket'];
$preview = (isset($_POST['preview']))?true:false;

$TicketHandler = new TicketHandler();
$TicketHandler->setAsRead(getSessionProp('IDUser'),$IDTicket);

$ArHeaders = TemplateHandler::getTicketHeaders($IDTicket);
$ArSupporters = TemplateHandler::listSupporters($IDTicket);
$ArAttachments = TemplateHandler::getAttachments($IDTicket);

if (getSessionProp('isSupporter') == 'true') {
  $BoCreate = F1DeskUtils::getPermission('BoCreateCall',getSessionProp('IDSupporter'));
  if ($BoCreate) {
    $ArDepartments = TemplateHandler::getPublicDepartments(false);
  } else {
    $ArDepartments = TemplateHandler::getDepartments(getSessionProp('IDSupporter'));
  }
} else {
  $ArDepartments = TemplateHandler::getPublicDepartments();
}

#
# Ticket Header
#
$StTitle = $ArHeaders['StTitle'];
$IDTicket = $ArHeaders['IDTicket'];
$IDDepartment = (!empty($ArHeaders['IDDepartment'])) ? $ArHeaders['IDDepartment'] : '';
$StSupporter = (!empty($ArHeaders['StName'])) ? $ArHeaders['StName'] : '';
$StSituation = constant($ArHeaders['StSituation']);

#
# Ticket Info
#
$ArAttachedTickets = TemplateHandler::getAttachedTickets($IDTicket);
$ArTicketsAttached = TemplateHandler::getTicketsAttached($IDTicket);
$ArTicketDepartments = TemplateHandler::getTicketDepartments($IDTicket);
$ArTicketDepartmentsReader = TemplateHandler::getTicketDepartmentsReader($IDTicket);
$ArTicketDestinations = TemplateHandler::getTicketDestination($IDTicket);
$ArTicketReaders = TemplateHandler::getTicketReaders($IDTicket);

$ArDepartment = reset($ArTicketDepartments);

$DtOpened = F1DeskUtils::formatDate('datetime_format',$ArHeaders['DtOpened']);
$StTicketCategory = TemplateHandler::getTicketCategory($IDTicket);
$StTicketPriority = TemplateHandler::getTicketPriority($IDTicket);
$StTicketType = TemplateHandler::getTicketType($IDTicket);

if (TemplateHandler::IsSupporter()) {
  $ArResponses = TemplateHandler::getCannedResponses(getSessionProp('IDSupporter'),$IDDepartment);
}

require_once(TEMPLATEDIR . '/ticket.php');
?>