<?php
/**
 * webEdition CMS
 *
 * $Rev$
 * $Author$
 * $Date$
 *
 * This source is part of webEdition CMS. webEdition CMS is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionCMS/License.txt
 *
 * @category   webEdition
 * @package    webEdition_listview
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */


include_once($_SERVER['DOCUMENT_ROOT'].'/webEdition/we/include/we_db.inc.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/webEdition/we/include/we_db_tools.inc.php');
if (defined('CUSTOMER_FILTER_TABLE')) {
	include_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we_modules/customer/weDocumentCustomerFilter.class.php');

}

/*
	This array stores listviews

*/

$GLOBALS['we_listviews_array'] = array();

/**
* class    listviewBase
* @desc    This is the base class for all webEdition listviews.
*
*/

class listviewBase{

	var $DB_WE;            /* Main DB Object */
	var $name;             /* name of listview */
	var $rows = -1;        /* Number of rows */
	var $cols = 0;        /* Number of cols */
	var $start = 0;        /* Where to start output */
	var $search = '';      /* search words */
	var $offset = 0;       /* start offset of first page */
	var $order = '';       /* Order string */
	var $desc = false;     /* set to true, if order should be descendend */
	var $cats = '';        /* category string */
	var $catOr = false;    /* set to true if it should be an 'OR condition' e.g. categories='value1' OR categories='value2' */
	var $anz_all = 0;      /* total number of matches */
	var $anz = 0;          /* number of rows in page */
	var $workspaceID = ''; /* commaseperated string of id's of workspace */
	var $count = 0;        /* internal counter */
	var $Record = array(); /* array to store results */
	var $ClassName = 'listviewBase'; /* Name of class */
	var $close_a = true;   /* close </a> when endtag used */
	var $customerFilterType = 'off'; // shall we control customer-filter?
	var $BlockInside = false; // set to true if listview is inside a block
	var $calendar_struct=array();
	var $id = '';


	/**
	 * listviewBase()
	 * @desc    constructor of class
	 *
	 * @param   name         string - name of listview
	 * @param   rows         integer - number of rows to display per page
	 * @param   offset       integer - start offset of first page
	 * @param   order        string - field name(s) to order by
	 * @param   desc         boolean - set to true, if order should be descendend
	 * @param   cats         string - comma separated categories
	 * @param   catOr        boolean - set to true if it should be an "OR condition"
	 * @param   workspaceID  string - commaseperated string of id's of workspace
	 * @param   cols   		  integer - to display a table this is the number of cols
	 *
	 */
	function listviewBase($name='0', $rows=999999999, $offset=0, $order='', $desc=false, $cats='', $catOr=false, $workspaceID='0', $cols=0, $calendar='', $datefield='', $date='',$weekstart='', $categoryids='', $customerFilterType='all', $id=''){

		/* triggers scheduler */
		if(defined('SCHEDULE_TABLE') && !isset($GLOBALS['scheduler_already_triggered'])){
			$GLOBALS['scheduler_already_triggered'] = 1;
			trigger_schedule();
		}
		$this->name = $name;
		$this->search = ((!isset($_REQUEST['we_lv_search_'.$this->name])) && (isset($_REQUEST['we_from_search_'.$this->name]))) ?  '���������' : isset($_REQUEST['we_lv_search_'.$this->name]) ? $_REQUEST['we_lv_search_'.$this->name] : '';
		$this->search = str_replace('"','',str_replace('\\"','',trim($this->search)));
		$this->DB_WE = new DB_WE;
		$this->rows = $cols ? ($rows * $cols) : $rows;
		$this->cols=(($cols=='' && ($calendar=='month' || $calendar=='month_table'))?7:$cols);
		$this->offset = abs($offset);
		$this->start = (isset($_REQUEST['we_lv_start_'.$this->name]) && $_REQUEST['we_lv_start_'.$this->name]) ? abs($_REQUEST['we_lv_start_'.$this->name]) : 0;
		if($this->start == 0) $this->start += $this->offset;
		$this->order = $order;
		$this->desc = $desc;
		$this->cats = trim($cats);
		$this->categoryids = trim($categoryids);
		$this->catOr = $catOr;
		$this->workspaceID = $workspaceID ? $workspaceID : '';
		$this->customerFilterType = $customerFilterType;
		$this->id = $id;

		$this->calendar_struct=array(
			'calendar'=>$calendar,
			'defaultDate'=>'',
			'date'=>-1,
			'calendarCount'=>'',
			'datefield'=>'',
			'start_date'=>'',
			'end_date'=>'',
			'storage'=>array(),
			'forceFetch'=>false,
			'count'=>0,
			'weekstart'=>0
		);
		if($calendar!=''){
			$this->calendar_struct['datefield']=$datefield!='' ? $datefield : '###Published###';
			$this->calendar_struct['defaultDate']=($date==''?time():strtotime($date));
			if($weekstart!=''){
				$wdays=array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
				$match=array_search($weekstart,$wdays);
				if($match!==false) $this->calendar_struct['weekstart']=$match;
			}


		}

	}

	function getIdQuery($fieldname) {
		if ($this->id) {
			return " AND $fieldname IN(".$this->id.") ";
		}
		return "";
	}

	/**
	 * next_record()
	 * @desc    selects next row and returns true. if no more row exists, it returns false
	 *
	 *
	 */
	function next_record(){
		// overwrite
		if($this->calendar_struct['calendar']!=''){
			$this->calendar_struct['calendarCount']++;
			$this->calendar_struct['count']=$this->count;
			$this->calendar_struct['forceFetch']=false;
			$calendarCount=$this->calendar_struct['calendarCount'];
			if(($calendarCount>0 || ($this->calendar_struct['calendar']=='day' && $calendarCount>=0)) && $calendarCount <= $this->calendar_struct['numofentries']){
				if($this->calendar_struct['date']<0) $this->calendar_struct['date']=$this->calendar_struct['defaultDate'];
				$date=$this->calendar_struct['date'];
				$month=date('m',$date);
				$year=date('Y',$date);
				$day=date('j',$date);
				if($this->calendar_struct['calendar']=='year'){
					$date=mktime(0,0,0,$calendarCount,$day,$year);
					$start_date=mktime(0,0,0,$calendarCount,$day,$year);
					$end_date=mktime(23,59,59,$calendarCount,getNumberOfDays($calendarCount,$year),$year);
				}
				else if($this->calendar_struct['calendar']=='day'){
					$date=mktime($calendarCount,0,0,$month,$day,$year);
					$start_date=mktime($calendarCount,0,0,$month,$day,$year);
					$end_date=mktime($calendarCount,59,59,$month,$day,$year);
				}
				else{
					$date=mktime(0,0,0,$month,$calendarCount,$year);
					$start_date=mktime(0,0,0,$month,$calendarCount,$year);
					$end_date=mktime(23,59,59,$month,$calendarCount,$year);
				}
				$month=date('m',$date);
				$day=date('j',$date);

				$this->calendar_struct['date']=$date;
				$this->calendar_struct['date_human']=date('Y-m-d',$date);
				$this->calendar_struct['day_human']=$day;
				$this->calendar_struct['month_human']=$month;
				$this->calendar_struct['year_human']=$year;
				$this->calendar_struct['start_date']=$start_date;
				$this->calendar_struct['end_date']=$end_date;

				foreach ($this->calendar_struct['storage'] as $k=>$v){
					if($v>=$start_date && $v<=$end_date){
						$found=array_search($k,$this->IDs);
						if($found!==false){
							$this->calendar_struct['forceFetch']=true;
							$this->calendar_struct['count']=$found;
						}
					}
				}
			}
			if($calendarCount > $this->calendar_struct['numofentries']){
				$this->calendar_struct['date']=0;
			}
			if(!$this->calendar_struct['forceFetch']) $this->count++;
			$this->Record = array();
		}
	}

	/**
	 * f()
	 * @desc    returns content of a field
	 *
	 * @param   key  string - name of field to return
	 *
	 */
	abstract function f($key);

	/**
	 * hasNextPage()
	 * @desc    returns true if next page exists, otherwise it returns false
	 *
	 * @param   name  string - name of listview
	 *
	 */
	function hasNextPage($parentEnd=false){
		if(isset($this->calendar_struct['calendar']) && $this->calendar_struct['calendar']!='') return true;
		if($parentEnd && isset($_REQUEST['we_lv_pend_'.$this->name])){
			return (($this->start + $this->anz) < $_REQUEST['we_lv_pend_'.$this->name]);
		}
		return (($this->start + $this->anz) < $this->anz_all);
	}

	/**
	 * hasPrevPage()
	 * @desc    returns true if previous page exists, otherwise it returns false
	 *
	 * @param   name  string - name of listview
	 *
	 */
	function hasPrevPage($parentStart=false){
		if(isset($this->calendar_struct['calendar']) && $this->calendar_struct['calendar']!='') return true;
		if($parentStart && isset($_REQUEST['we_lv_pstart_'.$this->name])){
			return (abs($this->start) != $_REQUEST['we_lv_pstart_'.$this->name]);
		}
		return (abs($this->start) != abs($this->offset));
	}

	/**
	 * getBackLink()
	 * @desc    gets the HTML Code for the back link
	 *
	 * @param   attribs  array - array with HTML attributes
	 *
	 */
	function getBackLink($attribs){

	    $only = we_getTagAttribute('only', $attribs, '');
			$urlID = we_getTagAttribute('id', $attribs, '');
		if(isset($this->calendar_struct['calendar']) && $this->calendar_struct['calendar']!=''){

			$month=$this->calendar_struct['month_human'];
			$day=$this->calendar_struct['day_human'];
			$year=$this->calendar_struct['year_human'];
			$newdate=$year.'-'.$month.'-'.$day;
			if($this->calendar_struct['calendar']=='month' || $this->calendar_struct['calendar']=='month_table'){
				if($month<=1){
					$month=12;
					$year--;
				}
				else $month--;
				$newdate=$year.'-'.$month.'-1';
			}
			else if($this->calendar_struct['calendar']=='year'){
				$year--;
				$newdate=$year.'-'.$month.'-'.$day;
			}
			else if($this->calendar_struct['calendar']=='day'){
				$day--;
				if($day<1){
					$month--;
					if($month<=1){
						$month=12;
						$year--;
					}
					$day = getNumberOfDays($month,$year);
				}
				$newdate=$year.'-'.$month.'-'.$day;
			}
			$attribs['href'] = we_tag('url',array('id'=>($urlID?$urlID:'top'))).'?'. htmlspecialchars(listviewBase::we_makeQueryString('we_lv_calendar_'.$this->name.'='.$this->calendar_struct['calendar'].'&we_lv_datefield_'.$this->name.'='.$this->calendar_struct['datefield'].'&we_lv_date_'.$this->name.'='.$newdate));
			if($only){
			    $this->close_a = false;
			    return (isset($attribs[$only]) ? $attribs[$only] : '');
			} else {
                return getHtmlTag('a', $attribs, '', false, true);
			}
		}
		else if($this->hasPrevPage()){

			$foo = $this->start - $this->rows;
			$attribs['href'] = we_tag('url',array('id'=>($urlID?$urlID:'top'))).'?'. htmlspecialchars(listviewBase::we_makeQueryString('we_lv_start_'.$this->name.'='.$foo));

			if($only){
			    $this->close_a = false;
			    return (isset($attribs[$only]) ? $attribs[$only] : '');
			} else {
                return getHtmlTag('a', $attribs, '', false, true);
			}
		}else{
			return '';
		}
	}

	function we_makeQueryString($queryString='',$filter='') {
		$usedKeys = array();
		if($filter){
		    $filterArr = explode(',',$filter);
		} else {
		    $filterArr = array();
		}
		array_push($filterArr,'edit_object');
		array_push($filterArr,'edit_document');
		array_push($filterArr,'we_editObject_ID');
		array_push($filterArr,'we_editDocument_ID');
		array_push($filterArr,'we_transaction');
		if($queryString) {
			$foo = explode('&',$queryString);
			$queryString = '';
			for($i=0;$i<sizeof($foo);$i++) {
				list($key,$val) = explode('=',$foo[$i]);
				array_push($usedKeys,$key);
				$queryString .= $key.'='.rawurlencode($val).'&';
			}
			$queryString = rtrim($queryString,'&');
		}
		$url_tail = '';
		if(isset($_GET)) {
			foreach($_GET as $key => $val){
				if ((!in_array($key,$usedKeys)) && (!in_array($key,$filterArr)) && (strpos($key,"we_ui_")!==0)) {
					if (is_array($val)) {
						foreach($val as $ikey => $ival){
							$url_tail .= "$key"."[".$ikey."]=". rawurlencode($ival) ."&";
						}
					} else {
						$url_tail .= "$key=".rawurlencode($val)."&";
					}
				}
			}
		}
		if(isset($_POST)) {
			foreach($_POST as $key => $val){
				if ((!in_array($key,$usedKeys)) && (!in_array($key,$filterArr)) && (strpos($key,"we_ui_")!==0)) {
					if (is_array($val)) {
						foreach($val as $ikey => $ival){
							$url_tail .= "$key"."[".$ikey."]=". rawurlencode($ival) ."&";
						}
					} else {
						$url_tail .= "$key=".rawurlencode($val)."&";
					}
				}
			}
		}
		$url_tail .= $queryString;
		$url_tail = rtrim($url_tail,'&');
		return $url_tail;
	}

	/**
	 * getNextLink()
	 * @desc    gets the HTML Code for the next link
	 *
	 * @param   attribs  array - array with HTML attributes
	 *
	 */
	function getNextLink($attribs){

	    $only = we_getTagAttribute("only", $attribs, "");
			$urlID = we_getTagAttribute("id", $attribs, "");
		if(isset($this->calendar_struct["calendar"]) && $this->calendar_struct["calendar"]!=""){

			$month=$this->calendar_struct["month_human"];
			$day=$this->calendar_struct["day_human"];
			$year=$this->calendar_struct["year_human"];
			$newdate=$year."-".$month."-".$day;
			if($this->calendar_struct["calendar"]=="month" || $this->calendar_struct["calendar"]=="month_table"){
				if($month>=12){
					$month=1;
					$year++;
				}
				else $month++;
				$newdate=$year."-".$month."-1";
			}
			else if($this->calendar_struct["calendar"]=="year"){
				$year++;
				$newdate=$year."-".$month."-".$day;
			}
			else if($this->calendar_struct["calendar"]=="day"){
				$day++;
				$numd = getNumberOfDays($month,$year);
				if($day>$numd){
					$day = 1;
					$month++;
				}
				if($month>=12){
					$month=1;
					$year++;
				}
				$newdate=$year."-".$month."-".$day;
			}
			$attribs["href"] = we_tag('url',array('id'=>($urlID?$urlID:'top'))).'?'. htmlspecialchars(listviewBase::we_makeQueryString("we_lv_calendar_".$this->name."=".$this->calendar_struct["calendar"]."&we_lv_datefield_".$this->name."=".$this->calendar_struct["datefield"]."&we_lv_date_".$this->name."=$newdate"));
			if($only){
			    $this->close_a = false;
			    return (isset($attribs[$only]) ? $attribs[$only] : "");
			} else {
                return getHtmlTag("a", $attribs, "", false, true);
			}
		}
		else if($this->hasNextPage()){

			$foo = $this->start + $this->rows;
			$attribs["href"] = we_tag('url',array('id'=>($urlID?$urlID:'top'))).'?'. htmlspecialchars(listviewBase::we_makeQueryString("we_lv_start_".$this->name."=$foo"));
			if($only){
			    $this->close_a = false;
			    return (isset($attribs[$only]) ? $attribs[$only] : "");
			} else {
                return getHtmlTag("a", $attribs, "", false, true);
            }

		}else{
			return "";
		}
	}


	function shouldPrintEndTR(){
		if($this->cols){
			return ( (($this->count) % $this->cols) == 0) || ($this->count == $this->anz);
		}
		return false;
	}

	function shouldPrintStartTR(){
		if($this->cols){
			return (($this->count-1) % $this->cols) == 0;
		}
		return false;
	}

	function tdEmpty(){
		return ($this->count > $this->anz);
	}
	function adjustRows(){
		if ($this->cols && $this->anz_all) {
			// Bugfix #1715 und auch #4965
			$_rows = floor($this->anz_all / $this->cols);
			$_rest = ($this->anz_all % $this->cols);
			$_add = $_rest ? $this->cols - $_rest : 0;
			$this->rows = min($this->rows, $_rows+$_add);
		}
	}

	function getCalendarField($calendar,$type){
		$out="";

		switch($type){
			case "day":
				if($calendar=="day") $out='print date("j",$GLOBALS["lv"]->calendar_struct["defaultDate"]);';
				else $out='if($GLOBALS["lv"]->calendar_struct["date"]>0) print date("j",$GLOBALS["lv"]->calendar_struct["date"]);';
			break;
			case "dayname":
			case "dayname_long":
				$out='print g_l("date","[day][long][".date("w",($GLOBALS["lv"]->calendar_struct["date"]>0 ? $GLOBALS["lv"]->calendar_struct["date"] : $GLOBALS["lv"]->calendar_struct["defaultDate"]))."]");';
			break;
			case "dayname_short":
				$out='print g_l("date","[day][short][".date("w",($GLOBALS["lv"]->calendar_struct["date"]>0 ? $GLOBALS["lv"]->calendar_struct["date"] : $GLOBALS["lv"]->calendar_struct["defaultDate"])."]");';
			break;
			case "month":
				$out='print date("m",($GLOBALS["lv"]->calendar_struct["date"]>0 ? $GLOBALS["lv"]->calendar_struct["date"] : $GLOBALS["lv"]->calendar_struct["defaultDate"]));';
			break;
			case "monthname":
			case "monthname_long":
				$out='print g_l("date","[month][long][".(int)(date("n",($GLOBALS["lv"]->calendar_struct["date"]>0 ? $GLOBALS["lv"]->calendar_struct["date"] : $GLOBALS["lv"]->calendar_struct["defaultDate"]))-1)."]");';
			break;
			case "monthname_short":
				$out='print g_l("date","[month][short][".(int)(date("n",($GLOBALS["lv"]->calendar_struct["date"]>0 ? $GLOBALS["lv"]->calendar_struct["date"] : $GLOBALS["lv"]->calendar_struct["defaultDate"]))-1)."]");';
			break;
			case "year":
				$out='print date("Y",($GLOBALS["lv"]->calendar_struct["date"]>0 ? $GLOBALS["lv"]->calendar_struct["date"] : $GLOBALS["lv"]->calendar_struct["defaultDate"]));';
			break;
			case "hour":
				$out='print date("H:i",($GLOBALS["lv"]->calendar_struct["date"]>0 ? $GLOBALS["lv"]->calendar_struct["date"] : $GLOBALS["lv"]->calendar_struct["defaultDate"]));';
			break;
			case "week":
				$out='print date("W",($GLOBALS["lv"]->calendar_struct["date"]>0 ? $GLOBALS["lv"]->calendar_struct["date"] : $GLOBALS["lv"]->calendar_struct["defaultDate"]));';
			break;
			default:
				$out='if($GLOBALS["lv"]->calendar_struct["date"]>0) print date("j",$GLOBALS["lv"]->calendar_struct["date"]);';
		}


		return "<?php $out?>";

	}

	function getCalendarFieldValue($calendar,$name){
		$out='';

		switch($name){
			case 'day':
				if($calendar['date']>0) return date('j',$calendar['date']);
				else  return date('j',$calendar['defaultDate']);
			break;
			case 'month':
				return date('m',$calendar['date']);
			break;
			case 'year':
				return date('Y',$calendar['date']);
			break;
			case "dayname":
			case "dayname_long":
				return g_l('date','[day][long]['.date("w",$calendar["date"]).']');
			break;
			case "dayname_short":
				return g_l('date','[day][short]['.date("w",$calendar["date"]).']');
			break;
			case "monthname":
			case "monthname_long":
				return g_l('date','[month][long]['.(date("n",$calendar["date"])-1).']');
			break;
			case "monthname_short":
				return g_l('date','[month][short]['.(date("n",$calendar["date"])-1).']');
			break;
			case 'hour':
				return date('H:i',$calendar['date']);
			break;
			case 'start_date':
				return $calendar['start_date'];
			break;
			case 'end_date':
				return $calendar['end_date'];
			break;
			case 'timestamp':
				return $calendar['date'];
			break;
			default:
				if($calendar['date']>0) return date('j',$calendar['date']);
		}
		return '';
	}

	function isCalendarField($type){
		return in_array($type,array('day','dayname','dayname_long','dayname_short','month','monthname','monthname_long','monthname_short','year','hour'));
	}

	function fetchCalendar(&$condition,&$calendar_select,&$calendar_where,$matrix=array()){
		if($this->calendar_struct['calendar']!=''){
			$calendar=$this->calendar_struct['calendar'];
			$day=date('j',$this->calendar_struct['defaultDate']);
			$month=date('m',$this->calendar_struct['defaultDate']);
			$year=date('Y',$this->calendar_struct['defaultDate']);

			if($calendar=='year'){
				$start_date=mktime(0,0,0,1,$day,$year);
 				$end_date=mktime(23,59,59,12,$day,$year);
 				$numofentries=12;
			}
			else if($calendar=='day'){
				$start_date=mktime(0,0,0,$month,$day,$year);
 				$end_date=mktime(23,59,59,$month,$day,$year);
 				$numofentries=24;
			}
			else{
				$numofentries=getNumberOfDays($month,$year);
				$start_date=mktime(0,0,0,$month,1,$year);
				$end_date=mktime(23,59,59,$month,$numofentries,$year);
			}

			$this->calendar_struct['date_human']=date('Y-m-d',$this->calendar_struct['defaultDate']);
			$this->calendar_struct['day_human']=$day;
			$this->calendar_struct['month_human']=$month;
			$this->calendar_struct['year_human']=$year;
			$this->calendar_struct['numofentries']=$numofentries;
			$this->calendar_struct['start_date']=$start_date;
			$this->calendar_struct['end_date']=$end_date;


			if($this->calendar_struct['datefield']=='' || $this->calendar_struct['datefield']=='###Published###'){
				$this->calendar_struct['datefield']='###Published###';
				$calendar_select=','.FILE_TABLE.'.Published AS Calendar ';
				$calendar_where=' AND ('.FILE_TABLE.".Published>=$start_date AND ".FILE_TABLE.".Published<=$end_date) ";
			}else{
				if(count($matrix) && in_array($this->calendar_struct['datefield'],array_keys($matrix))){
					$field=$matrix[$this->calendar_struct['datefield']]['table'].'.'.$matrix[$this->calendar_struct['datefield']]['type'].'_'.$this->calendar_struct['datefield'];
				}
				else{
					$field=CONTENT_TABLE.'.Dat';
				}

				$calendar_select=','.$field.' AS Calendar ';
				if($condition=='') $condition=$this->calendar_struct['datefield'].">=$start_date AND ".$this->calendar_struct['datefield']."<=$end_date";
				else $condition.=' AND '.$this->calendar_struct['datefield'].">=$start_date AND ".$this->calendar_struct['datefield']."<=$end_date";
			}

		}
	}

	function postFetchCalendar(){
		if($this->calendar_struct['calendar']!=''){
			$start=0;
			if($this->calendar_struct['calendar']=='month_table'){
				$start=(int)date('w',strtotime(date('Y',$this->calendar_struct['defaultDate']).'-'.date('m',$this->calendar_struct['defaultDate']).'-1'));
				if($this->calendar_struct['weekstart']!=''){
					$start=$start-$this->calendar_struct['weekstart'];
					if($start<0) $start=7+$start;
				}
			}
			$this->anz = $this->calendar_struct['numofentries']+$start;
			$this->anz_all = $this->anz;

			switch ($this->calendar_struct['calendar']) {
				case 'day':
					$this->calendar_struct['calendarCount'] = -1;
					break;
				case 'month_table':
					$this->calendar_struct['calendarCount'] = 0 - $start;
					$rest = $this->cols - ($this->anz % $this->cols);
					if ($rest < $this->cols) {
						$this->anz+=$rest;
						$this->anz_all+=$rest;
					}
					break;
				default:
					$this->calendar_struct['calendarCount'] = 0;
			}


			$this->calendar_struct['date']=-1;
		}
	}

	/**
	* @return boolean
	* @desc returns, if tag we:next / we:back should set an endtag automatically. As default
	*       it should set one
	*/
	function close_a(){
	    $_close = $this->close_a;
	    $this->close_a = true;
	    return $_close;
	}

}