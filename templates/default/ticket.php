<?
  /*default language*/
  handleLanguage(__FILE__);
  #
  # concatenate arrow's ID and Content's ID with this UID
  #
  $uid = uniqid();
?>
<!--[TICKET HEADER]-->
<div id='ticketHeader'>
  <div id="ticketTitle">
    <img id='reloadHeader' class='menuRefresh Right' onclick='refreshCall( <?= $IDTicket ?> )' src='<?= TEMPLATEDIR ?>images/btn_reload.png' alt='Reload' />
  	<img alt="Ticket" id='arrowHeader<?=$uid?>' src="<?= TEMPLATEDIR ?>images/arrow_hide.gif" onclick='toogleArrow( this.id, "ticketContent<?=$uid?>")' class='menuArrow'/>
  	<span><?= $StTitle ?></span>
  </div>


  <div id="ticketContent<?=$uid?>">
  	<table class='tableTickets'>
      <thead>
        <tr>
          <th><?=TICKET_HEADER_ID?></th>
          <th><?=TICKET_HEADER_DATE?></th>
          <th><?=TICKET_HEADER_STATUS?></th>
          <th><?=TICKET_HEADER_SUPPORTER?></th>
          <th><?=TICKET_HEADER_DEPARTMENT?></th>
          <?php if (TemplateHandler::IsSupporter()): ?>
          <th colspan='3'><?=TICKET_HEADER_ACTIONS?></th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class='TicketNumber'>#<?= $IDTicket ?></td>
          <td><?= $DtOpened ?></td>
          <td><?= $StSituation ?></td>
          <td>
            <?php if (TemplateHandler::IsSupporter()): ?>
            	<select id='StSupporter' onchange='setTicketOwner(<?= $IDTicket ?>, this.value)' class='inputCombo'>
            	  <? foreach ( $ArSupporters as $IDSupporter => $StSupporter ) : ?>
              	  <? if ($ArHeaders['IDSupporter'] != $IDSupporter) : ?>
              	  <option value=<?=$IDSupporter?>><?=$StSupporter?></option>
              		<? else : ?>
              		<option selected='selected' value=<?=$IDSupporter?>><?=$StSupporter?></option>
              		<? endif; ?>
            		<? endforeach; ?>
            	</select>
            <?php else:
              foreach ( $ArSupporters as $IDSupporter => $StSupporter ){
                if ($ArHeaders['IDSupporter'] == $IDSupporter) {
                  ?>
                  <span id="StSupporter"><?=$StSupporter?></span>
                  <?
                }
              }
            endif; ?>
          </td>
          <td>
            <?php if (TemplateHandler::IsSupporter()):?>
            <select id='Department' class='inputCombo' onchange='changeDepartment("<?=$IDTicket?>",this.value)'>
            <?php foreach ($ArDepartments as $ArDepartment): ?>
              <?php if(isset($ArDepartment['SubDepartments'])): ?>
                <?php if ($ArDepartment['IDDepartment'] == $IDDepartment):?>
                <option value='<?=$ArDepartment['IDDepartment']?>' selected><?=$ArDepartment['StDepartment']?></option>
                <?php else: ?>
                <option value='<?=$ArDepartment['IDDepartment']?>'><?=$ArDepartment['StDepartment']?></option>
                <?endif;?>
                <optgroup>
                  <?php foreach ($ArDepartment['SubDepartments'] as $SubDepartments):?>
                    <?php if ($SubDepartments['IDSub'] == $IDDepartment):?>
                    <option value='<?=$SubDepartments['IDSub']?>' selected><?=$SubDepartments['StSub']?></option>
                    <?php else: ?>
                    <option value='<?=$SubDepartments['IDSub']?>'><?=$SubDepartments['StSub']?></option>
                    <?php endif; ?>
                  <?php endforeach;?>
                </optgroup>
              <?php else: ?>
                <?php if ($ArDepartment['IDDepartment'] == $IDDepartment):?>
                <option value='<?=$ArDepartment['IDDepartment']?>' selected><?=$ArDepartment['StDepartment']?></option>
                <?php else:?>
                <option value='<?=$ArDepartment['IDDepartment']?>'><?=$ArDepartment['StDepartment']?></option>
                <?php endif;?>
              <?php endif; ?>
            <?php endforeach; ?>
            </select>
            <?php else:?>
              <?=$ArDepartment['StDepartment']?>
            <?php endif;?>
          </td>
          <?php if (TemplateHandler::IsSupporter()): ?>
          <td>
            <a href='javascript:void(0);' onclick='attachTicket(<?=$IDTicket?>);'>
              <img src='<?= TEMPLATEDIR ?>images/attach.png' alt='Attach Call' title='Attach Call'>
            </a>
          </td>
          <td>
              <?php if (F1DeskUtils::isIgnored(getSessionProp('IDSupporter'),$IDTicket)): ?>
            <a href='javascript:void(0);' onclick='unignoreTicket(<?=getSessionProp('IDSupporter')?>,<?=$IDTicket?>)'>
              <img src='<?= TEMPLATEDIR ?>images/unignore.png' alt='Ignore Call' title='Ignore Call'>
              <?php else: ?>
            <a href='javascript:void(0);' onclick='ignoreTicket(<?=getSessionProp('IDSupporter')?>,<?=$IDTicket?>)'>
              <img src='<?= TEMPLATEDIR ?>images/ignore.png' alt='Ignore Call' title='Ignore Call'>
              <?php endif;?>
            </a>
          </td>
          <td>
            <a href='javascript:void(0);' onclick='bookmarkTicket(<?=getSessionProp('IDSupporter')?>,<?=$IDTicket?>)'>
              <img src='<?= TEMPLATEDIR ?>images/bookmark.png' alt='Bookmark Call' title='Bookmark Call'>
            </a>
          </td>
          <?php endif; ?>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<!--[/TICKET HEADER]-->

<!--[TICKET INFORMATIONS]-->
<div id='ticketInformations' class='defaultBody'>
  <div id='informationsCaption' class='defaultCaption'>
    <img alt="Informations"  id='arrowInformations<?=$uid?>' src="<?= TEMPLATEDIR ?>images/arrow_show.gif"  onclick='toogleArrow( this.id, "informationsContent<?=$uid?>")' class="menuArrow"/>
    <span><?=INFORMATIONS?></span>
  </div>
  <div id='informationsContent<?=$uid?>' class="informationsBox" style="display:none">
    <!--[ATTACHMENT FILES]-->
    <? if (count($ArAttachments)!=0): ?>
    <span><?=INFO_FILES?></span>
    <ul>
      <li>
      <? $i=0; foreach ($ArAttachments as $Attachment): $Attachment = $Attachment[0]; if($i!=0) print ', '; ?>
        <a class="Link" href='download.php?IDAttach=<?=$Attachment['IDAttachment']?>'><?=$Attachment['StFile']?></a>
      <? $i++; endforeach; ?>
      </li>
    </ul>
    <? endif; ?>
    <!--[/ATTACHMENT FILES]-->

    <!--[ATTACHMENT TICKETS]-->
    <? if (count($ArAttachedTickets)!=0): ?>
    <span><?=INFO_TICKETS?></span>
    <ul>
      <li>
      <? $i=0; foreach ($ArAttachedTickets as $AttachedTicket): if($i!=0) print ', '; ?>
        <a class="Link" href='javascript:void(0);' onclick='previewInFlow.Ticket(<?=$AttachedTicket['IDAttachedTicket']?>)'>#<?=$AttachedTicket['IDAttachedTicket']?></a>
      <? $i++; endforeach; ?>
      </li>
    </ul>
    <? endif; ?>
    <!--[/ATTACHMENT TICKETS]-->

    <!--[TICKET DEPARTMENTS]-->
    <? if (count($ArTicketDepartments)!=0): ?>
    <span><?=INFO_DEPARTMENT_SENTTO?></span>
    <ul>
      <li class="Link">
      <? $i=0; foreach ($ArTicketDepartments as $TicketDepartments): if($i!=0) print ', '; ?>
        <?=$TicketDepartments['StDepartment']?>
      <? $i++; endforeach; ?>
      </li>
    </ul>
    <? endif; ?>
    <!--[/TICKET DEPARTMENTS]-->

    <!--[TICKET SUPPORTERS]-->
    <? if (count($ArTicketDestinations)!=0): ?>
    <span><?=INFO_SUPPORTER_SENTTO?></span>
    <ul>
      <li class="Link">
      <? $i=0; foreach ($ArTicketDestinations as $TicketDestination): if($i!=0) print ', '; ?>
        <?=$TicketDestination['StName']?>
      <? $i++; endforeach; ?>
      </li>
    </ul>
    <? endif; ?>
    <!--[/TICKET SUPPORTERS]-->

    <!--[TICKET DEPARTMENTS]-->
    <? if (count($ArTicketDepartmentsReader)!=0): ?>
    <span><?=INFO_DEPARTMENTS_READER?></span>
    <ul>
      <li class="Link">
      <? $i=0; foreach ($ArTicketDepartmentsReader as $TicketDepartmentsReader): if($i!=0) print ', '; ?>
        <?=$TicketDepartmentsReader['StDepartment']?>
      <? $i++; endforeach; ?>
      </li>
    </ul>
    <? endif; ?>
    <!--[/TICKET DEPARTMENTS]-->

    <!--[TICKET SUPPORTERS]-->
    <? if (count($ArTicketReaders)!=0): ?>
    <span><?=INFO_SUPPORTER_READER?></span>
    <ul>
      <li class="Link">
      <? $i=0; foreach ($ArTicketReaders as $TicketReaders): if($i!=0) print ', '; ?>
        <?=$TicketReaders['StName']?>
      <? $i++; endforeach; ?>
      </li>
    </ul>
    <? endif; ?>
    <!--[/TICKET SUPPORTERS]-->

  </div>
</div>
<!--[/TICKET INFORMATIONS]-->

<!--[TICKET HISTORY]-->
<div id='ticketHistory' class='defaultBody'>
  <div id="historyCaption" class='defaultCaption'>
  	<img alt="Ticket"  id='arrowHistory<?=$uid?>' src="<?= TEMPLATEDIR ?>images/arrow_hide.gif"  onclick='toogleArrow( this.id, "historyContent<?=$uid?>")' class="menuArrow"/>
  	<span><?=TICKET_HISTORY?></span>
  </div>

  <div id="historyContent<?=$uid?>" >
    <? foreach ($ArMessages as $ArMessage) : ?>
      <? if (!TemplateHandler::IsSupporter() && $ArMessage['EnMessageType'] == 'INTERNAL') continue; ?>
      <? $DtSended = F1DeskUtils::formatDate('datetime_format',$ArMessage['DtSended']); ?>
      <div class='<?= $ArMessage['StClass'] ?>'>
        <?= MSG_HEAD1 . $DtSended . MSG_HEAD2 . $ArMessage['SentBy'] . MSG_HEAD3 ?>
        <? if (array_key_exists($ArMessage['IDMessage'],$ArAttachments)): ?>
          <?  foreach ($ArAttachments[$ArMessage['IDMessage']] as $Attachment): ?>
              <p><b><?=ATTACHMENT?></b>: <a class='Link' href='download.php?IDAttach=<?=$Attachment['IDAttachment']?>'><?=$Attachment['StFile']?></a></p>
          <?  endforeach; ?>
        <? endif;?>
        <?= $ArMessage['TxMessage'] ?>
      </div>
    <? endforeach ?>
    <?php ?>
  </div>
</div>
<!--[/TICKET HISTORY]-->

<!--[TICKET ANSWER]-->
<?if(!$preview):?>
	<div id='ticketAnswer' class='defaultBody'>
	  <div id='answerCaption' class='defaultCaption'>
	  	<img alt="Ticket" id='arrowAnswer<?=$uid?>' src="<?= TEMPLATEDIR ?>images/arrow_hide.gif" onclick='toogleArrow( this.id, "answerContent<?=$uid?>")' class="menuArrow"/>
	  	<span><?=ANSWER?></span>
	  </div>

	  <div id="answerContent<?=$uid?>" >
	    <form method="POST" id="formAnswer" target="ajaxSubmit" action="answerTicket.php" enctype="multipart/form-data" onsubmit='if(_isEmpty(gID("TxMessage").value)){ flowAlert(default_ptBR.answerPreviewNoAnswer); return false; }'>
	      <div id='messageType' class='Right'>
	    	  <select name='StMessageType' id='StMessageType' class='inputCombo'>
	    				<option value="NORMAL"><?=MSGTYPE_NORMAL?></option>
	    				<?php if (TemplateHandler::IsSupporter()): ?>
	    				<option value="INTERNAL"><?=MSGTYPE_INTERNAL?></option>
	    				<option value="SATISFACTION"><?=MSGTYPE_SATISFACTION?></option>
	    				<?php endif; ?>
	    		</select>
	      </div>

	    	<textarea id='TxMessage' name='TxMessage' cols='65' rows='33' class='answerArea'></textarea>

	      <div id='displayCommands'>
	        <div id='answerOptions'>

	      		<div class='Right' id='answerAttach'>
	      		  <label for='Attachment'><?=ATTACHMENT?>: </label>
	      			<input id='Attachment' name='Attachment' class='inputFile' type="file" value="Anexo" />
	      			<iframe id='ajaxSubmit' name='ajaxSubmit' src='answerTicket.php' class="Invisible"></iframe>
	      		</div>
	    		  <div>
	    		    <input type='hidden' name='IDDepartment' id='IDDepartment' value='<?= $IDDepartment ?>' />
	    		    <input type='hidden' name='IDTicket' id='IDTicket' value='<?= $IDTicket ?>' />
	    		    <? if (TemplateHandler::IsSupporter()) : ?>
	      		    <select class='inputCombo' id='cannedAnswers'>
	              <? if ($ArResponses[0]['IDCannedResponse'] != ''): ?>
	                  <? foreach ($ArResponses as $Response): ?>
	                    <option value="<?=f1desk_escape_string($Response['TxMessage'],true,true);?>"><?=$Response['StTitle']?></option>
	                  <?endforeach; ?>
	              <? else: ?>
	                   <option value='null'><?=NO_ANSWER?></option>
	              <? endif; ?>
	      			  </select>
	      			  <button class='button' onclick='addCannedResponse(); return false;'><?=ADD?></button>
	    			  <? endif; ?>
	    		</div>
	    	</div>

	    	<div>
	    	    <button type="button" class='button' onclick='previewInFlow.Answer(gID("TxMessage").value);'><?=PREVIEW?></button>
    	      <input type="hidden" name="action" value="answer">
	    			<input type='submit' class='button' value='<?=ANSWER?>' name='Responder'>
	    	</div>
	    </div>
	    </form>
	  </div>
	</div>
<?endif;?>
<!--[/TICKET ANSWER]-->