<?php
//dmlL1functions

/*LICENSE*GNU*GPL************************************************************{{{
*
*                             dmental links
*    Copyright (C) 2006 AtKaaZ, AtKaaZ at users.sourceforge.net
*
*  ========================================================================
*
*    This program is free software; you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation; either version 2 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*
*  ========================================================================
*
* Description: demlinks sqlite dbase level 1 (based on level 0)
*
***************************************************************************}}}*/


require_once("shortdef.php");
require_once("dmldbl0.php");
require_once("color.php");


class dmlDBL1 extends dmlDBL0
{
        private $fParamNodeName;
        private $sqlGetNodeID;
        private $fPrepGetNodeID;

        private $sqlNewNode;
        private $fPrepNewNode;//prepared statement handler

        function __construct()/*{{{*/
        {
                parent::__construct();
                //--------- get ID by Name
                /*$this->sqlGetNodeID = 'SELECT '.$this->qNodeID.' FROM '.$this->qNodeNames.' WHERE '.$this->qNodeName.' = '.paramNodeName;
                exceptifnot( $this->fPrepGetNodeID = $this->fDBHandle->prepare($this->sqlGetNodeID) );
                exceptifnot( $this->fPrepGetNodeID->bindParam(paramNodeName, $this->fParamNodeName, PDO::PARAM_STR) );
                //---------
                $this->sqlNewNode = 'INSERT INTO '.$this->qNodeNames.' ('.$this->qNodeName.') VALUES ('.paramNodeName.')';//table name needs to be quoted

                exceptifnot( $this->fPrepNewNode = $this->fDBHandle->prepare($this->sqlNewNode) );//can't prepare unless the table already exists!
                exceptifnot( $this->fPrepNewNode->bindParam(paramNodeName, $this->fParamNodeName, PDO::PARAM_STR) ); //, PDO::PARAM_INT);
                 */
                pg_prepare($this->fDBHandle,dGetID,'SELECT GetID($1) as '.qID);// is it necessary to pg_free_result() the result of this function?
                pg_prepare($this->fDBHandle,dEnsureName,'SELECT EnsureName($1)');
                pg_prepare($this->fDBHandle,dDelName,'SELECT DelName($1)');

                //---------
        }/*}}}*/

        function __destruct()/*{{{*/
        {
                parent::__destruct();
        }/*}}}*/

        function EnsureName($nodename)/*{{{*/
        {
                initret($ret);
                exceptifnot( $this->TestElementInvariants($nodename) );//must not be empty or so; if it is then maybe's a bug outside this funcL1 provided user shall never call this funcL1 with an empty param value
                $rr=$this->GetID($id,$nodename);
                if (in_array(no,$rr)) {//no ID found, autoincrement ID on add; either the ID is used by another process in a transaction(=>not found by GetID) or it really is not found by GetID
                        //echo retValue($rr);
                        $result=pg_execute($this->fDBHandle,dEnsureName,array($nodename));
                        if (failed($result)) {
                        //$this->fParamNodeName=$nodename;
                        //if( failed($this->fPrepNewNode->execute()) ) {//error here? it probably already exists! error in GetID maybe; or transaction protected item(ie. another transaction is using this item that's why it wasn't found by GetID)
                                ensureexists($ret,no);
                                exceptifnot(pg_free_result($result));
                                return $ret;
                        }//fi
                        exceptifnot(pg_free_result($result));

                        ensureexists($ret,kAdded);
                } else {
                        ensureexists($ret,kAlready);
                }//fielse

                ensureexists($ret,ok);
                return $ret;
        }/*}}}*/

        function GetID(&$id,$nodename)// returns ID by Name /*{{{*/
        {
                initret($ret);
                exceptifnot( $this->TestElementInvariants($nodename) );
                $result=pg_execute($this->fDBHandle,dGetID,array($nodename));
                if ( !failed($result) ) {
                        $line = pg_fetch_array($result, null, PGSQL_ASSOC);
                        if(!failed( $line )) {
                    //            $id=(string)$ar[0][dNodeID];
                                $id=$line[dID];
                        }
                        $trimm=trim($id);
                        if (!empty($trimm)) {
                                ensureexists($ret,yes);
                        } else {
                                ensureexists($ret,array(no,kEmpty));
                        }
                } else {
                        ensureexists($ret,no);
                }
                exceptifnot(pg_free_result($result));
                return $ret;
        }/*}}}*/

        function IsName($nodename)/*{{{*/
        {
                initret($ret);
                exceptifnot( $this->TestElementInvariants($nodename) );
                if(in_array(yes, $this->GetID($id,$nodename) )) {
                        exceptifnot( $this->TestElementInvariants($id) );
                        ensureexists($ret,yes);
                        return $ret;
                }
                ensureexists($ret,no);
                return $ret;
        }/*}}}*/

        function DelName($nodename)/*{{{*/
        {
                initret($ret);
                exceptifnot( $this->TestElementInvariants($nodename) );

                $result=pg_execute($this->fDBHandle,dDelName,array($nodename));
                exceptifnot($result);
                exceptifnot(pg_free_result($result));

                /*if (in_array(yes,$this->GetID($id,$nodename))) {
                        exceptifnot( $this->DelID($id) );
                }*/
                //FIXME:
                ensureexists($ret,ok);
                return $ret;
        }/*}}}*/

} //class

// vim: fdm=marker
?>
