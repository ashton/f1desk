<?php

abstract class F1DeskUtils {

	private static $DBHandler;

	private static function getDBinstance(){
		if ( ! self::$DBHandler instanceof DBHandler ) {
			self::$DBHandler = new DBHandler();
		}
		return self::$DBHandler;
	}

	/**
   * Create a new category
   *
   * @param string  $StName category name
   * @return integer affected rows
   */
  public static function createCategory($StName) {
    $StTblName = DBPREFIX . 'Category';
    $ArFields = array('StCategory');
    $ArValues = array($StName);
    self::getDBinstance();
    $ItReturn = self::$DBHandler->insertIntoTable($StTblName,$ArFields,$ArValues);

    return $ItReturn;
  }

  /**
   * Create a new priority
   *
   * @param string  $StName priority name
   * @return integer affected rows
   */
  public static function createPriority($StName) {
    $StTblName = DBPREFIX . 'Priority';
    $ArFields = array('StPriority');
    $ArValues = array($StName);
    self::getDBinstance();
    $ItReturn = self::$DBHandler->insertIntoTable($StTblName,$ArFields,$ArValues);

    return $ItReturn;
  }

  /**
   * Create a new Unit
   *
   * @param string  $StName  Unit's name
   * @param integer $ArPermissions  Array with the Unit's permissions
   * @return unknown
   */
  public static function createUnit($StName,$ArPermissions) {
    $StTblName = DBPREFIX . 'Unit';
    $ArFields = array_keys($ArPermissions);
    array_unshift($ArFields,'StUnit');
    self::getDBinstance();
    $ItReturn = self::$DBHandler->insertIntoTable($StTblName,$ArFields,$ArPermissions);
    return $ItReturn;
  }

  /**
   * creates a new department
   *
   * @param string $StName Department's name
   * @param string $StDescription Department's description
   * @param string $TxSign Department's sign
   * @param string $IDParent ID of parent department case is subdepartment
   * @return unknown
   */
  public static function createDepartment($StName, $StDescription, $TxSign ='', $IDParent = null) {
    $StTblName = DBPREFIX . 'Department';
    $ArFields = array('StDescription','StDepartment','TxSign');
    $ArValues = array($StDescription,$StName,$TxSign);
    self::getDBinstance();
    self::$DBHandler->insertIntoTable($StTblName,$ArFields,$ArValues);
    $IDDepartment = self::$DBHandler->getID();
    # if is a subdpartment

    if (! is_null($IDParent)) {
      $StTblName = DBPREFIX . 'SubDepartment';
      $ArFields = array('IDSubDepartment','IDDepartment');
      $ArValues = array($IDDepartment,$IDParent);
      self::$DBHandler->insertIntoTable($StTblName,$ArFields,$ArValues);
    }
    return $IDDepartment;
  }

  /**
   * Create a new Rate
   *
   * @param string $StName
   * @return integer  affected rows
   */
  public static function createRate($StName) {
    $StTblName = DBPREFIX . 'Rate';
    $ArFields = array('StRate');
    $ArValues = array($StName);
    self::getDBinstance();
    $Itreturn = self::$DBHandler->insertIntoTable($StTblName,$ArFields,$ArValues);

    return $Itreturn;
  }

  /**
   * Creates a new Ticket type
   *
   * @param string $StType
   * @return integer  affected rows
   */
  public static function createTicketType($StType){
    $StTblName = DBPREFIX . 'Type';
    $ArFields = array('StType');
    $ArValues = array($StType);
    self::getDBinstance();
    $Itreturn = self::$DBHandler->insertIntoTable($StTblName,$ArFields,$ArValues);

    return $Itreturn;
  }


  /**
   * get all departmentes
   *
   * @param int $IDUser
   * @return array
   *
   * @author Dimitri Lameri <dimitri@digirati.com.br>
   */
  public static function getDepartments( $IDUser ) {
    $ArDepartments = array();

    $StSQL = '
SELECT
	D.IDDepartment, D.StDepartment
FROM
	' . DBPREFIX . 'Department D
  LEFT JOIN ' . DBPREFIX . 'DepartmentSupporter DS ON (DS.IDDepartment = D.IDDepartment)
  LEFT JOIN ' . DBPREFIX . 'Supporter S ON (S.IDSupporter = DS.IDSupporter)
  LEFT JOIN ' . DBPREFIX . 'User U ON (U.IDUser = S.IDUser)
WHERE
  U.IDUser = ' . $IDUser . '
ORDER BY
	D.StDepartment';

  	self::getDBinstance();
  	self::$DBHandler->execSQL($StSQL);
    $ArResult = self::$DBHandler->getResult("string");

    foreach ($ArResult as $ArQuery){
    	$ArDepartments[$ArQuery['IDDepartment']] = $ArQuery['StDepartment'];
    }

    $ArDepartments['ignored'] = DEPT_IGNORED;
    /*$ArDepartments['bookmark'] = DEPT_BOOKMARK;*/

    return $ArDepartments;
  }

	/**
   * List all existances departments and subdepartments
   *
   * @param int IDUser
   * @return array
   *
   * @author Dimitri Lameri <dimitri@digirati.com.br>
   */
  public static function getSubDepartments( $IDUser ){

    #
    # Getting who is Department and who is SubDepartment
    #
    $StSQL = "
SELECT
  D.IDDepartment, GROUP_CONCAT(SD.IDSubDepartment) as IDSubDepartments
FROM
	".DBPREFIX."SubDepartment SD
  LEFT JOIN ".DBPREFIX."Department D ON (SD.IDDepartment = D.IDDepartment)
  LEFT JOIN ".DBPREFIX."DepartmentSupporter DS ON (DS.IDDepartment = D.IDDepartment)
  LEFT JOIN ".DBPREFIX."Supporter S ON (S.IDSupporter = DS.IDSupporter)
  LEFT JOIN ".DBPREFIX."User U ON (U.IDUser = S.IDUser)
WHERE
  U.IDUser = $IDUser
GROUP BY
  SD.IDDepartment";

    self::getDBinstance();
    self::$DBHandler->execSQL($StSQL);
    $ArSubDepartments = array();
    $ArResult = self::$DBHandler->getResult("string");

    foreach ( $ArResult as $ArDepartment ) {
      $ArSubSeparation = explode(',', $ArDepartment[ 'IDSubDepartments' ]);
      $ArSubDepartments[$ArDepartment['IDDepartment']] = $ArSubSeparation;
    }

    return $ArSubDepartments;

  }

  /**
   * List all existances units
   *
   * @return $ArUnits < array >
   *
   * @author Mario Vitor <mario@digirati.com.br>
   */
  public static function listUnits(){

  	$StSQL = "
SELECT
	IDUnit, StUnit
FROM
	" . DBPREFIX . "Unit";

  	self::getDBinstance(); self::$DBHandler->execSQL($StSQL);
    $ArUnits = self::$DBHandler->getResult("string");
    $ArReturn = array();
    for ( $aux = 0; $aux < count( $ArUnits ); $aux++){
    	$ArReturn[ $ArUnits[ $aux ][ 'IDUnit' ] ] = $ArUnits[ $aux ][ 'StUnit' ];
    }

    return $ArReturn;

  }

  /**
   * Lists All Categories
   *
   * @return array
   */
  public static function listCategories() {
    $StSQL = "
SELECT
  *
FROM
" . DBPREFIX . "Category";
    $ArCategories = self::getDBinstance(); self::$DBHandler->execSQL($StSQL);
    $ArCategories = self::$DBHandler->getresult('string');
    $ArReturn = array();
    for($i=0; $i < count($ArCategories); $i++ ) {
      $ArReturn [ $ArCategories[$i]['IDCategory'] ] = $ArCategories[$i]['StCategory'];
    }
    return $ArReturn;
  }

  /**
   * Lists all priorities
   *
   * @return unknown
   */
  public static function listPriorities() {
   $StSQL = "
SELECT
  *
FROM
". DBPREFIX . "Priority";
    $ArPriority = self::getDBinstance(); self::$DBHandler->execSQL($StSQL);
    $ArPriority = self::$DBHandler->getResult('string');
    $ArReturn = array();
    for($i=0; $i < count($ArPriority); $i++ ) {
      $ArReturn [ $ArPriority[$i]['IDPriority'] ] = $ArPriority[$i]['StPriority'];
    }
    return $ArReturn;
  }

  /**
   * Lists all rates availables
   *
   * @return array
   */
  public static function listRate(){
    $StSQL = "
SELECT
  *
FROM
" . DBPREFIX . "Rate";
    $ArRates = self::getDBinstance(); self::$DBHandler->execSQL($StSQL);
    self::$DBHandler->getResult('string');
    for($i=0; $i < count($ArRates); $i++ ) {
      $ArReturn [ $ArRates[$i]['IDRate'] ] = $ArRates[$i]['StRate'];
    }
    return $ArReturn;
  }

  /**
   * Lists all ticket types availables
   *
   * @return array
   */
  public static function listTicketTypes(){
    $StSQL = "
SELECT
  *
FROM
" . DBPREFIX . "Type";
    $DBHandler = self::getDBinstance();
      $DBHandler->execSQL($StSQL);
    $ArTypes = $DBHandler->getResult('string');
    return $ArTypes;
  }

  /**
   * Check if ticket given is read or not by the supporter given
   *
   * @param integer $IDTicket
   * @return boolean
   */
  public static function isTicketRead($IDTicket, $IDSupporter) {
    $StSQL = "
SELECT
  COUNT(R.IDTicket)
FROM
  " . DBPREFIX . "Read R
LEFT JOIN" . DBPREFIX . "Supporter S ON  (R.IDSupporter = S.IDSupporter)
LEFT JOIN" . DBPREFIX . "Ticket T ON (R.IDTicket = T.IDTicket)
WHERE
  T.IDTicket = $IDTicket
AND
  S.Supporter = $IDSupporter";

    self::getDBinstance();
    self::$DBHandler->execSQL($StSQL);
    $ArResult = self::$DBHandler->getResult('num');

    return ($ArResult[0][0] == 1) ? true : false;
  }

  /**
   * Check if the ticket given is ignored by the supporter given
   *
   * @param unknown_type $IDTicket
   * @param unknown_type $IDSupporter
   * @return unknown
   */
  public static function isTicketIgnored($IDTicket, $IDSupporter) {
    $StSQL = "
SELECT
  COUNT(I.IDTicket)
FROM
  " . DBPREFIX . "Ignored I
LEFT JOIN" . DBPREFIX . "Supporter S ON  (I.IDSupporter = S.IDSupporter)
LEFT JOIN" . DBPREFIX . "Ticket T ON (I.IDTicket = T.IDTicket)
WHERE
  T.IDTicket = $IDTicket
AND
  S.Supporter = $IDSupporter";

    self::getDBinstance();
    self::$DBHandler->execSQL($StSQL);
    $ArResult = self::$DBHandler->getResult('num');

    return ($ArResult[0][0] == 1) ? true : false;
  }

  /**
   * formats the date to the choosen one
   *
   * @param string $StFormat
   * @param date $Date
   *
   * @author Dimitri Lameri <dimitri@digirati.com.br>
   */
  public static function formatDate($StFormat, $Date = false) {
    if ($Date === false){
      $Date = time();
    }
    return date(getOption($StFormat),strtotime($Date));
  }

  /**
   * sorts an array using the id
   *
   * @param array $Array
   * @param int $ID
   * @return array
   */
  public static function sortByID($Array, $ID) {
    $sorttedArray = array();

    foreach ($Array as $Line) {
      $sorttedArray[$Line[$ID]] = $Line;
    }

    ksort($sorttedArray);

    return $sorttedArray;
  }


  /**
   * Check if the supporter given have permisssion about the action given
   *
   * @param str $StAction
   * @param int $IDSupporter
   * @return boolean
   *
   * @author Matheus Ashton <matheus@digirati.com.br>
   */
  public static function getPermission($StAction, $IDSupporter) {
    $DBHandler = self::getDBinstance();
    $StSQL = "
SELECT
  $StAction
FROM
  " . DBPREFIX . "Unit U
LEFT JOIN
  " . DBPREFIX . "Supporter S ON (S.IDUnit = U.IDUnit)
WHERE
  S.IDSupporter = $IDSupporter";
    $DBHandler->execSQL($StSQL);
    $ArResult = $DBHandler->getResult('string');

    return ($ArResult[0][$StAction] == 0) ? false : true;
  }

  /**
   * Get data from User Table with a Supporter/Client ID given
   *
   * @param int  $ID  Supporter/Client ID
   * @param int  $ItType  0 = Supporter, 1 = Client
   * @return array User data
   *
   * @author Matheus Ashton <matheus@digirati.com.br>
   */
  public static function getUserData($ID,$ItType = 0) {
    $StField = ($ItType == 0) ? 'IDSupporter' : 'IDClient';
    $StTable = ($ItType == 0) ? 'Supporter' : 'Client';
    $StSQL = "
SELECT
  U.*
FROM
  " . DBPREFIX . "User U
LEFT JOIN
  " . DBPREFIX . "$StTable ON (U.IDUser = $StTable.IDUser)
WHERE
  $StTable.$StField = $ID";

  $DBHandler = self::getDBinstance();
  $DBHandler->execSQL($StSQL);
  $ArResult = $DBHandler->getResult('string');
    return array_shift($ArResult);
  }
  
	public static function updateUserData( $IDUser, $ArData ){
  	$StTableName = DBPREFIX . "User";
  	$StCondition = "IDUser = $IDUser";
  	$DBHandler = self::getDBinstance();
  	
  	return $DBHandler->updateTable($StTableName, $ArData, $StCondition);
  }

  public static function listCannedResponses($IDSupporter,$IDDepartment = false) {
    $StSQL = "
SELECT
  C.*
FROM
  " . DBPREFIX . "Supporter S
LEFT JOIN
  " . DBPREFIX . "CannedResponse C ON(C.IDSupporter = S.IDSupporter)";

    if ($IDDepartment !== false) {
      $StSQL .= "
LEFT JOIN
  " . DBPREFIX . "DepartmentCannedResponse DCR ON(DCR.IDCannedResponse = C.IDCannedResponse)
LEFT JOIN
  " . DBPREFIX . "Department D ON(DCR.IDDepartment = D.IDDepartment)";
    }

    $StSQL .= "
WHERE
  S.IDSupporter = $IDSupporter";
    if ($IDDepartment !== false) {
      $StSQL .= "
OR
  D.IDDepartment = $IDDepartment";
    }
    $DBHandler = self::getDBinstance();
    $DBHandler->execSQL($StSQL);
    $ArResult = $DBHandler->getResult('string');
    return $ArResult;
  }
  
  public static function createCannedResponse ( $ArData ){
  	$ArFields = array_keys($ArData);
  	$StTableName = DBPREFIX . "CannedResponse";
  	$DBHandler = self::getDBinstance();

  	if ( $DBHandler->insertIntoTable($StTableName, $ArFields, array($ArData)) ) {
  		return $DBHandler->getID();
  	} else {
  		return false;
  	}

  }

  public static function editCannedResponse( $IDCannedResponse, $ArData ){
		$StTableName = DBPREFIX . "CannedResponse";
		$StCondition = "IDCannedResponse = " . $IDCannedResponse;
		$DBHandler = self::getDBinstance();
		
		return $DBHandler->updateTable( $StTableName, $ArData, $StCondition );
  }
  
  public static function removeCannedResponse ( $IDCannedResponse ) {
		$StTableName = DBPREFIX . 'CannedResponse';
		$StCondition = 'IDCannedResponse = ' . $IDCannedResponse;
  	$DBHandler = self::getDBinstance();

 		return $DBHandler->deleteFromTable($StTableName,$StCondition);
  }
  
  public static function listNotes( $IDSupporter ){
  	$StSQL = "
SELECT N.*
FROM
	". DBPREFIX ."Note N
WHERE
	N.IDSupporter = $IDSupporter 
  	";
  	
  	$DBHandler = self::getDBinstance();
    $DBHandler->execSQL($StSQL);
    $ArResult = $DBHandler->getResult('string');
    return $ArResult;
  }
  
  public static function createNote( $ArData ){
  	$ArFields = array_keys($ArData);
  	$StTableName = DBPREFIX . "Note";
  	$DBHandler = self::getDBinstance();

  	if ( $DBHandler->insertIntoTable($StTableName, $ArFields, array($ArData)) ) {
  		return $DBHandler->getID();
  	} else {
  		return false;
  	}
  }
  
  public static function editNote( $IDNote, $ArData ){
		$StTableName = DBPREFIX . "Note";
		$StCondition = "IDNote = " . $IDNote;
		$DBHandler = self::getDBinstance();
		
		return $DBHandler->updateTable( $StTableName, $ArData, $StCondition );
  }
  
  public static function removeNote ( $IDNote ) {
		$StTableName = DBPREFIX . 'Note';
		$StCondition = 'IDNote = ' . $IDNote;
  	$DBHandler = self::getDBinstance();

 		return $DBHandler->deleteFromTable($StTableName,$StCondition);
  }
  
}