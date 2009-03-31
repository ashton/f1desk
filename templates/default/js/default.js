var templateDir = 'templates/default/';

/**
 *  OBJECT CONTAIN GLOBALS OBJECTS AND METHODS FROM THE DEFAULT TEMPLATE
 */
var baseActions = {

  'animateReload': function(ID, action) {
    var reload = gID('reload' + ID);
    if (reload !== null) {
      if (action == 'start') {
        reload.src= 'templates/default/images/animated-reload.gif';
      } else {
        reload.src = 'templates/default/images/btn_reload.png';
      }
    }
  },

  'toogleArrow': function(StArrow, StDivContent, ForceAction) {
    var action;
    var arrow = gID(StArrow);
    var element = gID(StDivContent);

    if (ForceAction === undefined) {
      action = Visibility.toogleView(element);
    } else if (ForceAction == 'hide') {
      action = Visibility.hide(element);
    } else {
      action = Visibility.show(element);
    }

    if (action == 'hide') {
      arrow.src= 'templates/default/images/arrow_show.gif';
      arrow.alt = 'Show';
    } else {
      arrow.src = 'templates/default/images/arrow_hide.gif';
      arrow.alt = 'Hide';
    }
  }

};

/**
 *  OBJECT CONTAIN METHODS FROM HOME TEMPLATE
 */
var Home = {

  '_doLoading': function( formName, action ){
    setStyle(  gID(formName+'Loading'),  {
      'visibility': ( action=='hide' )?'hidden':'visible'
    });
  },

  'editCannedResponse': function() {
    var editForm = gID("cannedForm");
    if(editForm.elements['IDCanned'].value == ""){
      flowWindow.alert(i18n.noCannedSelected); return false;
    }
    this._doLoading( 'canned','show' );
    var content = {
      'action':'edit',
      'IDCannedResponse': editForm.elements['IDCanned'].value,
      'StTitle': editForm.elements['StTitle'].value,
      'TxMessage': editForm.elements['TxCannedResponse'].value
    };
    var tParams = {
      'enqueue':1,
      'method':'post',
      'content':content,
      'okCallBack': function(htmlEdited){
        gID('cannedTR'+content.IDCannedResponse).innerHTML = htmlEdited;
        Home._doLoading( 'canned','hide' );
        baseActions.toogleArrow( 'cannedArrow', 'cannedBoxEditAreaContent', 'hide');
      }
    };
    var tUrl = 'cannedResponsesAction.php';
    xhr.makeRequest('editCannedResponse',tUrl,tParams);
  },

  'editNote': function() {
    var editForm = gID("noteForm");
    if(editForm.elements['IDNote'].value == ""){
      flowWindow.alert(i18n.noNoteSelected); return false;
    }
    this._doLoading( 'note','show' );
    var content = {
      'action':'edit',
      'IDNote': editForm.elements['IDNote'].value,
      'StTitle': editForm.elements['StTitle'].value,
      'TxNote': editForm.elements['TxNote'].value
    };
    var tParams = {
      'enqueue':1,
      'method':'post',
      'content':content,
      'okCallBack': function( htmlEdited ){
        gID('noteTR'+content.IDNote).innerHTML = htmlEdited;
        Home._doLoading( 'note','hide' );
        baseActions.toogleArrow( 'noteArrow', 'noteBoxEditAreaContent', 'hide');
      }
    };
    var tUrl = 'notesAction.php';
    xhr.makeRequest('editNote',tUrl,tParams);
  },

  'newCannedResponse': function() {
    this._doLoading( 'canned','show' );
    var editForm = gID("cannedForm");
    var content = {
      'action':'insert',
      'IDCannedResponse': 'autoincrement',
      'StTitle': editForm.elements['StTitle'].value,
      'TxMessage': editForm.elements['TxCannedResponse'].value
    };
    var tParams = {
      'enqueue':1,
      'method':'post',
      'content':content,
      'okCallBack': function( htmlCallBack ){
        gID('cannedTable').getElementsByTagName('tbody')[0].innerHTML += htmlCallBack;
        /*Testando se exista a coluna "nao ha respostas"*/
        var noCanned = gID('noCanned');
        if( noCanned ){  removeElements(noCanned);  }
        baseActions.toogleArrow( 'cannedArrow', 'cannedBoxEditAreaContent', 'hide');
        Home._doLoading( 'canned','hide' );
      }
    };
    var tUrl = 'cannedResponsesAction.php';
    xhr.makeRequest('newCannedResponse',tUrl,tParams);
  },

  'newNote': function() {
    this._doLoading( 'note', 'show' );
    var editForm = gID("noteForm");
    var content = {
      'action':'insert',
      'IDNote': 'autoincrement',
      'StTitle': editForm.elements['StTitle'].value,
      'TxNote': editForm.elements['TxNote'].value
    };
    var tParams = {
      'enqueue':1,
      'method':'post',
      'content':content,
      'okCallBack': function( htmlCallBack ){
        gID('noteTable').getElementsByTagName('tbody')[0].innerHTML += htmlCallBack;
        /*Testando se exista a coluna "nao ha respostas"*/
        var noNote = gID( 'noNote' );
        if( noNote ){  removeElements(noNote);  }
        baseActions.toogleArrow( 'noteArrow', 'noteBoxEditAreaContent', 'hide');
        Home._doLoading( 'note','hide' );
      }
    };
    var tUrl = 'notesAction.php';
    xhr.makeRequest('newNote',tUrl,tParams);
  },

  'removeBookmark': function(IDTicket) {
    if(!IDTicket){
      flowWindow.alert(i18n.noBookmarkID);
    }
    var tFunction = function(opt) {
      if (opt == 1) {
        Home._doLoading( 'bookmark','show' );
        var tParams = {
          'enqueue':1,
          'method':'post',
          'content':{
            'action':'remove',
            'IDTicket': IDTicket,
          },
          'okCallBack': function(returnedValue){
            if(returnedValue == 'error'){
              flowWindow.alert(i18n.wrongBookmarkID+IDTicket);
            } else {
              removeElements( gID('bookmarkTR'+IDTicket) );
              if( gID('bookmarkTable').getElementsByTagName('TR').length == 0){
                gID('bookmarkTable').appendChild( createElement('TR',{'id':'noBookmark'},
                  createElement('TD',{
                    'colspan':'3',  'align':'center'
                  },i18n.noBookmark)
                ) );
              }
              Home._doLoading( 'bookmark','hide' );
            }
          }
        };
        var tUrl = 'bookmarkAction.php';
        xhr.makeRequest('removeBookmark',tUrl,tParams);
      }
    };
    flowWindow.confirm(i18n.deleteBookmark,tFunction);
  },

  'removeCannedResponse': function(IDCannedResponse) {
    if(!IDCannedResponse){
      flowWindow.alert(i18n.noCannedID);
    }
    var tFunction = function(opt) {
      if (opt == 1) {
        Home._doLoading( 'canned','show' );
        var tParams = {
          'enqueue':1,
          'method':'post',
          'content':{
            'action':'remove',
            'IDCannedResponse': IDCannedResponse,
          },
          'okCallBack': function(returnedValue){
            if(returnedValue == 'error'){
              flowWindow.alert(i18n.wrongCannedID+IDCannedResponse);
            } else {
              removeElements( gID('cannedTR'+IDCannedResponse) );
              if( gID('cannedTable').getElementsByTagName('TR').length == 0){
                gID('cannedTable').appendChild( createElement('TR',{'id':'noCanned'},
                  createElement('TD',{
                    'colspan':'2',  'align':'center'
                  },i18n.noCanned)
                ) );
              }
              Home._doLoading( 'canned','hide' );
              baseActions.toogleArrow( 'cannedArrow', 'cannedBoxEditAreaContent', 'hide');
            }
          }
        };
        var tUrl = 'cannedResponsesAction.php';
        xhr.makeRequest('removeCannedResponse',tUrl,tParams);
      }
    }
    flowWindow.confirm(i18n.deleteCanned,tFunction);
  },

  'removeNote': function(IDNote) {
    if(!IDNote){
      flowWindow.alert(i18n.noNoteID);
    }
    var tFunction = function(opt) {
      if (opt == 1) {
        Home._doLoading( 'note','show' );
        var tParams = {
          'enqueue':1,
          'method':'post',
          'content':{
            'action':'remove',
            'IDNote': IDNote,
          },
          'okCallBack': function(returnedValue){
            if(returnedValue == 'error'){
              flowWindow.alert(i18n.wrongNoteID+IDNote);
            } else {
              removeElements( gID('noteTR'+IDNote) );
              if( gID('noteTable').getElementsByTagName('TR').length == 0){
                gID('noteTable').appendChild( createElement('TR',{'id':'noNote'},
                  createElement('TD',{
                    'colspan':'3',  'align':'center'
                  },i18n.noNote)
                ) );
              }
              Home._doLoading( 'note','hide' );
              baseActions.toogleArrow( 'noteArrow', 'noteBoxEditAreaContent', 'hide');
            }
          }
        };
        var tUrl = 'notesAction.php';
        xhr.makeRequest('removeNote',tUrl,tParams);
      }
    };
    flowWindow.confirm(i18n.deleteNote,tFunction);
  },

  'startCreatingElement': function(StElement) {
    var editForm = gID(StElement + "Form");
    for (var aux = 0; aux < editForm.elements.length; aux++) {
      editForm.elements[aux].value = "";
    }
    gID(StElement+'FormButton').value = "Criar";
    baseActions.toogleArrow( StElement + 'Arrow', StElement+'BoxEditAreaContent', 'show');
  },

  'startDataEdit': function() {
    baseActions.toogleArrow('dataArrow', 'dataBoxEditAreaContent');
    var dataForm = gID('dataForm');
    dataForm.elements['StDataName'].value = unescape(gID('StDataName').value);
    dataForm.elements['StDataEmail'].value = unescape(gID('StDataEmail').value);
    dataForm.elements['StDataNotify'][gID('StDataNotify').value].checked = 'true';
    dataForm.elements['TxDataHeader'].value = unescape(gID('TxDataHeader').value);
    dataForm.elements['TxDataSign'].value = unescape(gID('TxDataSign').value);
  },

  'startEditElement': function(formName, IDMessage) {
    baseActions.toogleArrow( formName+'Arrow', formName+'BoxEditAreaContent', 'show');
    var editForm = gID(formName + 'Form');
    if( formName == 'canned' ){
      editForm.elements['IDCanned'].value = IDMessage;  /*ID*/
      editForm.elements['StTitle'].value = unescape(gID('StCannedTitle'+IDMessage).value);  /*StTitle*/
      editForm.elements['TxCannedResponse'].value = unescape(gID('TxCannedResponse'+IDMessage).value);  /*TxMessage*/
    } else if( formName == 'note' ){
      editForm.elements['IDNote'].value = IDMessage;  /*ID*/
      editForm.elements['StTitle'].value = unescape(gID('StNoteTitle'+IDMessage).value);  /*StTitle*/
      editForm.elements['TxNote'].value = unescape(gID('TxNote'+IDMessage).value);  /*TxMessage*/
    }
    gID(formName + 'FormButton').value = "Editar";
  },

  'submitForm': function (formName, action) {
    if(!action || !formName){
      flowWindow.alert(i18n.noAction); return false;
    } else {
      if( formName == 'canned' ){  // if form is cannedForm
        switch (action){
          case "Editar":  // if action is edit
            Home.editCannedResponse();
          break;
          case "Criar": // if action is create
            Home.newCannedResponse();
          break;
        }
      } else if( formName == 'note' ){ // if form is noteForm
        switch (action){
          case "Editar": // if action is edit
            Home.editNote();
          break;
          case "Criar": // if action is create
            Home.newNote();
          break;
        }
      }
    }
  },

  'updateInformations': function() {
    this._doLoading('data','show');  baseActions.toogleArrow('dataArrow', 'dataBoxEditAreaContent','hide');
    var dataForm = gID('dataForm');
    var content = {
      'StName': dataForm.elements['StDataName'].value,
      'StEmail': dataForm.elements['StDataEmail'].value,
      'BoNotify': (dataForm.elements['StDataNotify'][0].checked)?0:1,
      'TxHeader': dataForm.elements['TxDataHeader'].value,
      'TxSign': dataForm.elements['TxDataSign'].value
    };
    var tParams = {
      'enqueue':1,
      'method':'post',
      'content':content,
      'okCallBack': function( requestResponse ){
        if(requestResponse == 'sucess'){
          var dataForm = gID('dataForm');
          var TxHeader = dataForm.elements['TxDataHeader'].value;
          var TxSign = dataForm.elements['TxDataSign'].value;
          /*Update TD's*/
          gID('StDataNameTD').getElementsByTagName('pre')[0].innerHTML = dataForm.elements['StDataName'].value;
          gID('StDataEmailTD').getElementsByTagName('pre')[0].innerHTML = dataForm.elements['StDataEmail'].value;
          gID('StDataNotifyTD').getElementsByTagName('pre')[0].innerHTML = (dataForm.elements['StDataNotify'][0].checked)?'Não':'Sim';
          gID('TxDataHeaderTD').getElementsByTagName('pre')[0].innerHTML = (!TxHeader)?'<i>'+i18n.empty+'</i>':TxHeader;
          gID('TxDataSignTD').getElementsByTagName('pre')[0].innerHTML = (!TxSign)?'<i>'+i18n.empty+'</i>':TxSign;
          /*Update Hiddens*/
          gID('StDataName').value = escape(dataForm.elements['StDataName'].value);
          gID('StDataEmail').value = escape(dataForm.elements['StDataEmail'].value);
          gID('StDataNotify').value = (dataForm.elements['StDataNotify'][0].checked)?'0':'1';
          gID('TxDataHeader').value = escape(TxHeader);
          gID('TxDataSign').value = escape(TxSign);
          Home._doLoading('data','hide');
        } else {
           flowWindow.alert(i18n.updateError);
        }
      }
    };
    var tUrl = 'userData.submit.php';
    xhr.makeRequest('newCannedResponse',tUrl,tParams);
  }

};

/**
 *  OBJECT CONTAIN METHODS FROM WRITING TEMPLATE
 */
var Writing = {

  'addReader': function(IDSupporter,StName) {
    for(i in IDSupporter) {
      if(!gID('pR'+IDSupporter[i])) { // Se ainda nao existe
        /*Adicionando o ID no campo hidden*/
        if(! gID('ArReaders')) {
          gID('formCreate').appendChild( createElement('input',{
            'type':'hidden',
            'name':'ArReaders',
            'id':'ArReaders',
            'value':IDSupporter[i]
          }) );
        } else {
          gID('ArReaders').value += ',' + IDSupporter[i];
        }
        /*Agora, adicionando o nome à lista de exibição*/
        gID('addedReaders').appendChild( createElement('p',{
          'id':'pR'+IDSupporter[i],
          'style':'margin:0;padding:0;padding-bottom:5px;'
        }) );
        var aLink = createElement('a',{'href':'javascript:void(0);','onclick':"Writing.removeSupporter('Readers',"+"'pR"+IDSupporter[i]+"');"});
            aLink.appendChild( createElement('img',{
              'src':'templates/default/images/button_cancel.png',
              'style':'vertical-align:middle;padding-right:5px;'
            }) );
        var span = createElement('span',{'id':'respondto'+IDSupporter[i],'class':'supporterName'})
            span.appendChild( createTextNode(StName[i]) );
        gID('pR'+IDSupporter[i]).appendChild(aLink);  gID('pR'+IDSupporter[i]).appendChild(span);
        gID('addedReaders').className = '';
      }
    }
  },

  'addRecipient': function(IDSupporter,StName) {
    for(i in IDSupporter) {
      if(!gID('p'+IDSupporter[i])) { // Se ainda nao existe
        /*Adicionando o ID no campo hidden*/
        if(! gID('ArRecipients')) {
          gID('formCreate').appendChild( createElement('input',{
            'type':'hidden',
            'name':'ArRecipients',
            'id':'ArRecipients',
            'value':IDSupporter[i]
          }) );
        } else {
          gID('ArRecipients').value += ',' + IDSupporter[i];
        }
        /*Agora, adicionando o nome à lista de exibição*/
        gID('addedRecipients').appendChild( createElement('p',{
          'id':'p'+IDSupporter[i],
          'style':'margin:0;padding:0;padding-bottom:5px;'
        }) );
        var aLink = createElement('a',{'href':'javascript:void(0);','onclick':"Writing.removeSupporter('Recipients',"+"'p"+IDSupporter[i]+"');"})
            aLink.appendChild( createElement('img',{
              'src':'templates/default/images/button_cancel.png',
              'style':'vertical-align:middle;padding-right:5px;'
            }) );
        var span = createElement('span',{'id':'respondto'+IDSupporter[i],'class':'supporterName'})
            span.appendChild( createTextNode(StName[i]) );
        gID('p'+IDSupporter[i]).appendChild(aLink);  gID('p'+IDSupporter[i]).appendChild(span);
        gID('addedRecipients').className = '';
      }
    }
  },

  'attachTicket': function(IDTicket) {
    Ticket.attachTicket(IDTicket);
  },

  'checkAdd': function(Type) {
    var combo = gID("supporters"); IDSupporter=[]; StName=[]; max=combo.options.length;
    for(var i=0;i<max;i++) {
      if(combo[i].selected == true) {
        IDSupporter.push(combo[i].value);
        StName.push(combo[i].innerHTML);
      }
    }
    if(combo.selectedIndex > -1) {
      if(Type == 'Recipients')  Writing.addRecipient(IDSupporter,StName);
      else  Writing.addReader(IDSupporter,StName);
    } else {
      flowWindow.alert(i18n.noSupporter);
    }
  },

  'createTicketSubmit': function() {
    if(gID('IDRecipient')[gID('IDRecipient').selectedIndex].value == 'null'
        && (! gID('ArRecipients') || gID('ArRecipients').value == '')) {
      gID('sendTo').appendChild( createElement('p',{'id':'Error','style':'color: red'}, [createTextNode(i18n.noRecipient)] ) );
      return false;
    }
  },

  'listSupporters': function(Type) {
    var tParams = {
      'method':'get',
      'okCallBack':function(response) {
        var flowParams = new flowWindow.flowParams();
        with(flowParams) {
          width = 410;  height = 260;
          innerHTML = response; TBStyle.Caption = i18n.addSupporter;
        }
        Flow.open(flowParams);
      }
    };
    xhr.makeRequest('Add Supporters','listSupporters.php',tParams);
    top.Type = Type;
  },

  'removeSupporter': function(Type,ID) {
    var creentIDs = gID('Ar'+Type).value.split(','); var newIDs=[];
    var deleteID = (Type == 'Recipients') ? ID.replace(/p/,'') : ID.replace(/pR/,'');
    for(var aux =0; aux<creentIDs.length; aux++)
      if(creentIDs[aux] != deleteID)  newIDs[newIDs.length] = creentIDs[aux];
    gID('Ar'+Type).value = newIDs.join(','); removeElements(gID(ID));
    if(Type == 'Recipients') {
      if(gID('addedRecipients').getElementsByTagName('p').length == 0){
        gID('addedRecipients').className = 'Invisible';
        removeElements(hidden);
      }
    } else {
      if(gID('addedReaders').getElementsByTagName('p').length == 0) {
        gID('addedReaders').className = 'Invisible';
        removeElements(hidden);
      }
    }
  }

};


/**
 *  OBJECT CONTAIN METHODS FROM TICKET LIST TEMPLATE
 */
var Ticket = {

  'addCannedResponse': function(IDDepartment,IDSupporter) {
    var Responses = gID('cannedAnswers');
    var TxMessage = Responses[Responses.selectedIndex].value
    gID('TxMessage').value += br2nl(unescape(TxMessage)) + '\n';
    return false;
  },

  'attachTicket': function(IDTicket) {
    var flowParams = new flowWindow.flowParams();
    with(flowParams) {
      width = 350;  height = 175;
      innerHTML = '#'; Window = 'prompt';
      TBStyle.Caption = "Anexando chamado"; WindowStyle.Caption = "Informe o número do chamado a ser anexado";
      EventFuncs = {
        'Prompt': function(IDAttached) {
          IDAttached = IDAttached.replace(/[#]|[^0-9]/g,'');
          if(IDAttached == '') { return false; }
          if(typeof(IDTicket) == 'undefined') {
            if(!gID('ArAttached'))
              gID('formCreate').appendChild( createElement('input',{
                'type':'hidden',
                'id':'ArAttached',
                'name':'ArAttached',
                'value':IDAttached
              }) );
            else
              gID('ArAttached').value += ',' + IDAttached;
            gID('AttachedTickets').appendChild( createElement('p',{
              'id':'attachedTickets',
              'style':'margin:0;padding:0;padding-bottom:5px;'
            },[ createElement('span',{'id':'ticket','class':'supporterName'}, [createTextNode('#'+IDAttached)] ) ]) );
            gID('AttachedTickets').className = '';
          } else {
            var tParams = {
              'method':'post',
              'content': {
                'IDTicket':IDTicket,
                'IDAttached':IDAttached,
                'StAction':'attach',
              },
              'okCallBack':function(response) {
                if(response == 'ok') {
                  Ticket.refreshTicket(IDTicket);
                } else {
                  flowWindow.alert(response);
                }
              }
            };
            xhr.makeRequest('Attach Ticket','ticketActions.php',tParams);
          }
        }
      };
    }
    var ID = Flow.open(flowParams);
  },

  'bookmarkTicket': function(IDSupporter, IDTicket) {
    var tParams = {
      'method':'post',
      'content': {
        'IDSupporter':IDSupporter,
        'IDTicket':IDTicket,
        'StAction':'bookmark',
      },
      'okCallBack':function(response) {
        if(response == 'ok') {
          Ticket.reloadTicketList('bookmark',true, 'show');
        } else {
          flowWindow.alert(response);
        }
      }
    };
    xhr.makeRequest('Bookmark Ticket','ticketActions.php',tParams);
  },

  'changeDepartment': function(IDTicket, IDDepartment) {
    var tParams = {
      'method':'post',
      'content': {
        'IDDepartment':IDDepartment,
        'IDTicket':IDTicket,
        'StAction':'change'
      },
      'okCallBack':function(response) {
        if(response == 'ok') {
          Ticket.refreshTicket(IDTicket);
          Ticket.reloadTicketList(IDDepartment,true, 'show');
        } else {
          flowWindow.alert(response);
        }
      }
    };
    xhr.makeRequest('Change Department','ticketActions.php',tParams);
  },

  'findTicket': function(IDTicket) {
    var Ticket = gID('ticket1');
    if (Ticket && Ticket.parentNode.className.indexOf == 'notRead') {
      var TicketTable = Ticket.parentNode.parentNode.parentNode.id;
      var ID = TicketTable.split('ticketTable');
      Ticket.reloadTicketList(ID[1]);
    }
  },

  'ignoreTicket': function(IDSupporter,IDTicket) {
    var tParams = {
      'method':'post',
      'content': {
        'IDSupporter':IDSupporter,
        'IDTicket':IDTicket,
        'StAction':'ignore',
      },
      'okCallBack':function(response) {
        if(response == 'ok') {
          var department = gID('IDDepartment').value;
          Ticket.reloadTicketList('ignored',true,'show');
          Ticket.reloadTicketList(department,false, 'show');
          Ticket.refreshTicket(IDTicket);
        } else {
          flowWindow.alert(response);
        }
      }
    };
    tFunction = function(opt) {
      if(opt == 1) {
        xhr.makeRequest('Ignore Ticket','ticketActions.php',tParams);
      }
    }
    flowWindow.confirm(i18n.ignoreCall,tFunction);
  },

  'initialized' : [],

  'insertTickets': function(IDDepartment, HTMLTickets) {
    var departmentContent = gID( 'departmentContent' + IDDepartment );
    removeChilds(departmentContent);
    appendHTML(HTMLTickets, departmentContent);
  },

  'orderTicketList': function(ItTD, tableID) {
    var tBody = gID(tableID).getElementsByTagName('TBODY')[0];
    var toIterate = tBody.getElementsByTagName('TR');
    var tdsValues = new Array();
    for (var aux = 0; aux < toIterate.length; aux++){
      tdsValues[aux] = new Array();
      tdsValues[aux][0] = toIterate[aux].getElementsByTagName('TD')[ItTD].innerHTML.toLowerCase();
      tdsValues[aux][1] = toIterate[aux];
    }
    while (toIterate.length != 0){
      removeElements(toIterate[0]);
    }
    tdsValues = tdsValues.sort();
    for (var aux =0; aux < tdsValues.length; aux++){
      tBody.appendChild(tdsValues[aux][1]);
    }
  },

  'refreshNotReadCount': function(IDDepartment) {
    if (IDDepartment == 'closed' || IDDepartment == 'ignored') {
      return false;
    }
    var count = 0;  var table = gID('ticketTable' + IDDepartment);
    var tbody = gTN('tbody',table)[0];   var trs = gTN('tr',tbody);
    for (var i=0; i < trs.length; i++) {
      if (trs[i].className.indexOf('notRead') !== -1) {
        count += 1;
      }
    }
    var element = gID('notReadCount' + IDDepartment);
    removeChilds(element);  element.appendChild( createTextNode( count ) );
  },

  'refreshTicket': function(IDTicket) {
    var tParams = {
      'enqueue':1,
      'method':'post',
      'content':{'IDTicket':IDTicket},
      'startCallBack':function() {
        baseActions.animateReload( 'Header', 'start' );
      },
      'okCallBack':function(returnedValue) {
        baseActions.animateReload( 'Header', 'stop' );
        var contentDisplay = gID('contentDisplay'); removeChilds(contentDisplay);
        appendHTML(returnedValue, contentDisplay);
      }
    };
    var tUrl = 'ticketDetails.php';
    xhr.makeRequest('refreshTicket',tUrl,tParams);
    return true;
  },

  'reloadTicketList': function(IDDepartment, First, Force) {
    var tParams = {
      'enqueue':1,
      'returnType':'txt',
      'method':'post',
      'content':{'IDDepartment':IDDepartment},
      'startCallBack' : function() {
        baseActions.animateReload( IDDepartment, 'start' );
      },
      'okCallBack':function(HTMLTickets) {
        baseActions.animateReload( IDDepartment, 'stop' );
        Ticket.insertTickets(IDDepartment, HTMLTickets);
        Ticket.refreshNotReadCount( IDDepartment );
        if (First === true) { baseActions.toogleArrow("arrow"+IDDepartment, 'departmentContent' + IDDepartment, Force); }
      },
      'errCallBack':function(Return) {
        baseActions.toogleArrow("arrow"+IDDepartment, 'departmentContent' + IDDepartment, 'hide');
        baseActions.animateReload( IDDepartment, 'stop' );
      }
    };
    var tUrl = templateDir + 'ticketList.php';
    xhr.makeRequest('showTickets',tUrl,tParams);
  },

  'selectTicket': function(Clicked) {
    var div = gID('contentDepartments');  var table = gTN('table',div);
    for (var i=0; i < table.length; i++) {
      var tbody = gTN('tbody',table[i])[0];
      var trs = gTN('tr',tbody);  var className = '';
      for (var j=0; j < trs.length; j++) {
        if (trs[j].className.indexOf('notRead') !== -1)   className = 'notRead';
        else    className = '';
        if (j % 2 == 0)   trs[j].className = className + ' Alt';
        else  trs[j].className = className;
      }
    }
    Clicked.className = 'Selected';
  },

  'setTicketOwner': function(IDTicket, IDSupporter) {
    var IDDepartment;
    var tParams = {
      'enqueue':1,
      'method':'post',
      'content':{'IDSupporter':IDSupporter, 'IDTicket':IDTicket},
      'okCallBack': function(returnedValue){
        var IDDepartment = gID('IDDepartment').value;
        Ticket.reloadTicketList(IDDepartment,true,'show');
        Ticket.refreshTicket( IDTicket );
      }
    };
    var tUrl = 'setTicketOwner.php';
    xhr.makeRequest('setTicketOwner',tUrl,tParams);
    return true;
  },

  'showDepartmentTickets': function(IDDepartment) {
      baseActions.toogleArrow("arrow"+IDDepartment, 'departmentContent' + IDDepartment);
  },

  'showTicket': function(IDTicket, IDDepartment, Clicked) {
    var tParams = {
      'enqueue':1,
      'method':'post',
      'content':{'IDTicket':IDTicket},
      'startCallBack':function() {
        baseActions.animateReload( IDDepartment, 'start' );
      },
      'okCallBack':function(returnedValue) {
        baseActions.animateReload( IDDepartment, 'stop' );
        var contentDisplay = gID('contentDisplay');   removeChilds(contentDisplay);
        appendHTML(returnedValue, contentDisplay);
        Ticket.selectTicket(Clicked);
        Ticket.refreshNotReadCount( IDDepartment );
        if (IDDepartment == 'bookmark') {
          Ticket.findTicket(IDTicket);
        }
      }
    };
    var tUrl = 'ticketDetails.php';
    xhr.makeRequest('refreshTicket',tUrl,tParams);
    return true;
  },

  'submitTicketForm': function(IDTicket) {
    gID('StMessageType').selectedIndex = 0;
    gID('TxMessage').value = '';  gID('Attachment').value = '';
    Ticket.refreshTicket(IDTicket);
  },

  'unignoreTicket': function(IDSupporter,IDTicket) {
    var tParams = {
      'method':'post',
      'content': {
        'IDSupporter':IDSupporter,
        'IDTicket':IDTicket,
        'StAction':'unignore',
      },
      'okCallBack':function(response) {
        if(response == 'ok') {
          var department = gID('IDDepartment').value;
          Ticket.reloadTicketList('ignored',false,'show');
          Ticket.reloadTicketList(department,false,'show');
          Ticket.refreshTicket(IDTicket);
        } else {
          flowWindow.alert(response);
        }
      }
    };
    xhr.makeRequest('Unignore Ticket','ticketActions.php',tParams);
  }

};

var Search = {

};

var Admin = {

};

var flowWindow = {

  'alert': function(StArg) {
    var flowParams = new this.flowParams();
    with(flowParams) {
      width = 350;
      height = 175;
      TBStyle.Caption = i18n.flowAlertTitle;
      WindowStyle.Caption = '<br>';
      innerHTML = StArg + '<br><br>';
      Window = 'alert';
    }
    var ID = Flow.open(flowParams); return ID;
  },

  'confirm': function(StArg,tFunction) {
    var option = ''; var flowParams = new this.flowParams();
    with(flowParams) {
      width = 350;  height = 175;
      TBStyle.Caption = i18n.flowConfirmTitle;
      WindowStyle.Caption = '<br>';
      innerHTML = StArg + '<br><br>';
      Window = 'confirm';
      if(typeof(tFunction) == 'function') {
        EventFuncs.Confirm = tFunction;
      }
    }
    var ID = Flow.open(flowParams); return ID;
  },

  'flowParams': function(){
    this.y = Positions.getScrollOffSet(gTN('body')[0]).y + 50;
    this.x = Positions.getScrollOffSet(gTN('body')[0]).x + 200;
    this.width=350; this.height=250;
    this.definition='response';  this.innerHTML='TEXTO DA PAGINA';
    this.TB = true; this.Window = 'default';
    this.TBStyle = {
      'BackgroundColor': '#4F6C9C',
      'Color':'#fff',
      'Font':'12px verdana, sans-serif',
      'Image': '',
      'Caption': 'TEXTO CAPTION BARRA DE TITULO'
    };
    this.WindowStyle = {
      'BackgroundColor':'#ECEDEF',
      'BackgroundImage':'',
      'Caption':'TEXTO TITULO DA JANELA'
    };
    this.EventFuncs = {
      'Confirm':function(){ },
      'Prompt':function(){ },
      'Close':'',
      'Max':'',
      'Min':'',
      'Rest':''
    }
  },

  'previewAnswer': function(TxMessage) {
    if(isEmpty(TxMessage)){ flowWindow.alert(i18n.answerPreviewNoAnswer); return false; }
    var  tParams = {
      'method':'post',
      'content':{
        'action':'preview',
        'TxMessage': TxMessage
      },
      'okCallBack':function(response) {
        var flowParams = new flowWindow.flowParams();
        with(flowParams) {
          width = 480;  height = 350; innerHTML = response;
          TBStyle.Caption = i18n.answerPreviewTitle;
        }
        Flow.open(flowParams);
      }
    };
    xhr.makeRequest('preview Ticket','answerTicket.php',tParams);
  },

  'previewCannedResponse': function(StTitle, TxMessage) {
    StTitle = unescape( StTitle );  TxMessage = unescape( TxMessage );
    var flowParams = new this.flowParams();
    with(flowParams){
      innerHTML = ''+
        '<table class="tableTickets">'+
          '<thead>'+
            '<th>'+i18n.cannedTableTitle+'</th>'+
          '</thead>'+
          '<tbody>'+
            '<td class="TicketNumber">'+ StTitle +'</td>'+
          '</tbody>'+
        '</table>'+
        '<br />'+
        '<table class="tableTickets">'+
          '<thead>'+
            '<th>'+i18n.cannedTableMessage+'</th>'+
          '</thead>'+
          '<tbody>'+
            '<td>'+ TxMessage +'</td>'+
          '</tbody>'+
        '</table>';
      TBStyle.Caption = StTitle; width = 550; height = 380;
    }
    var ID = Flow.open(flowParams);
  },

  'previewNote': function(StTitle, TxNote) {
    StTitle = unescape( StTitle );  TxNote = unescape( TxNote );
    var flowParams = new this.flowParams();
    with(flowParams){
      innerHTML = ''+
        '<table class="tableTickets">'+
          '<thead>'+
            '<th>'+i18n.noteTableTitle+'</th>'+
          '</thead>'+
          '<tbody>'+
            '<td class="TicketNumber">'+ StTitle +'</td>'+
          '</tbody>'+
        '</table>'+
        '<br />'+
        '<table class="tableTickets">'+
          '<thead>'+
            '<th>'+i18n.noteTableNote+'</th>'+
          '</thead>'+
          '<tbody>'+
            '<td>'+ TxNote +'</td>'+
          '</tbody>'+
        '</table>';
      TBStyle.Caption = StTitle; width = 550; height = 380;
    }
    var ID = Flow.open(flowParams);
  },

  'previewTicket': function(IDTicket) {
    var tParams = {
      'method':'post',
      'content': {
        'IDTicket':IDTicket,
        'preview':'true'
      },
      'okCallBack':function( ticketHTML ) {
        var flowParams = new flowWindow.flowParams();
        with(flowParams){
          innerHTML = '<span style="padding:10px">'+ticketHTML+'</span>';
          TBStyle.Caption = i18n.ticketPreviewTitle + IDTicket;
          width = 600; height = 450;
        }
        var ID = Flow.open(flowParams);
      }
    };
    xhr.makeRequest('Bookmark Ticket','ticketDetails.php',tParams);
  },

  'prompt': function(StArg, tFunction) {
    var flowParams = new this.flowParams();
    with(flowParams) {
      width = 350;  height = 175; innerHTML = ''; Window = 'prompt';
      if(typeof(tFunction) == 'function') {
        EventFuncs.Prompt = tFunction;
      }
    }
    var ID = Flow.open(windowParams); return ID;
  }

};