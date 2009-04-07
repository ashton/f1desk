<?php require_once(dirname(__FILE__).'/../../../main.php');?>
<div id='adminWrapper'>
  <div id='createSupporter' class='Left'>
    <h3>Cadastrar Atendente</h3>
    <div id='newSupporterData' class='Left'>
      Nome:
      <p><input type='text' id='StName' name='StName' class='inputCombo' /></p>
      Email:
      <p><input type='text' id='StName' name='StEmail' class='inputCombo' /></p>
      Senha:
      <p><input type='password' id='StPassword' name='StPassword' class='inputCombo' /></p>
      <p>
        <button class='button'>Cadastrar</button>
        <button class='button'>Limpar</button>
      </p>
    </div>
    <div id='SupporterDepartment' class='Left'>
      Departamento:
      <p>
        <select id='IDDepartment' name='IDDepartment'>
          <option value='1'>Suporte</option>
          <option value='2'>Financeiro</option>
          <option value='3'>Comercial</option>
          <option value='4'>Qualidade</option>
          <option value='5'>Desenvolvimento</option>
          <option value='6'>Infra-Estrutura</option>
        </select>
        <a href='javascript:void(0);' onclick='Admin.addDepartment'><img src='templates/default/images/add.png'></a>
        <p>
          <fieldset id='Departments'>
            <legend>Departamentos atribu&iacute;dos</legend>
            <span>N&atilde;o h&aacute; departamentos atribu&iacute;dos</span>
          </fieldset>
        </p>
      </p>
    </div>
  </div>
  <div id='manageSupporter' class='Left'>
      <h3>Gerenciar Usu&aacute;rios</h3>
      <div id='menuTitle' class='departmentRows adminDptRows'>
        <img id='arrow' class='menuArrow' src='templates/default/images/arrow_show.gif' alt='Show' />
        <span class='TxPadrao'>Suporte</span>
      </div>
      <div id='menuTitle' class='departmentRows adminDptRows'>
        <img id='arrow' class='menuArrow' src='templates/default/images/arrow_show.gif' alt='Show' />
        <span class='TxPadrao'>Comercial</span>
      </div>
      <div id='menuTitle' class='departmentRows adminDptRows'>
        <img id='arrow' class='menuArrow' src='templates/default/images/arrow_show.gif' alt='Show' />
        <span class='TxPadrao'>Financeiro</span>
      </div>
  </div>
</div>