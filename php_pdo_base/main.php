//run with ./2
//<?php this is for vim
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
* Description: main program
*
***************************************************************************}}}*/

#include "shortdef.php"
#include "color.php"
#include "dmlDBL1.php"
#include "dmlphpL2.php"
#include "debugL1.php"

#define _r(...) \
        __( show( retValue( __VA_ARGS__ )) );

        if (!terminal) {
?>
<html>

<head>

<script type="text/javascript" src="simpletreemenu.js">

/***********************************************
* Simple Tree Menu- � Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<link rel="stylesheet" type="text/css" href="simpletree.css" />

<!--
/* Context menu Script- � Dynamic Drive (www.dynamicdrive.com) Last updated: 01/08/22
For full source code and Terms Of Use, visit http://www.dynamicdrive.com */
-->

<link rel="stylesheet" type="text/css" href="cmenu.css" />

</head>

<body>

<a href="javascript:ddtreemenu.flatten('treemenu1', 'expand')">Expand All</a> | <a href="javascript:ddtreemenu.flatten('treemenu1', 'contract')">Contract All</a>

<?

        } //endif terminal

        beginprogram
        /*foreach($_SERVER as $key => $val) {
                echo $key."=".$val.nl;
        }*/
        /*__( $dmlar=new dmlphpL1 );
        _r( $dmlar->EnsurePCRel("A","B") );
                _r( $dmlar->EnsurePCRel("A","B") );*/
        /*$flags=array(kOverwrite,kKeepPrevValue,kCreateNodeIfNotExists,kTruncateIfMoreThanOneNode);
        _r( $dmlar->MakePointer($ptr,kParent,"P", $flags) );//kAlready, kInvalid(if kKeep...)
        _r( $ptr->SetPointee("A") );//P->A also creates DMLPointers->Parents->P
        _r( $dmlar->MakePointer($ptr,kChild,"cP", $flags) );//kAlready, kInvalid(if kKeep...)
        _r( $ptr->SetPointee("A") );//A->cP also creates DMLPointers->Children, cP->Children
        _r( $ptr->GetPointee($val) );
        echo $val;//A
        _r( $ptr->GetEnvironment($dphpgot) );//===$dmlar
        _r( $ptr->GetPointer($val) ); //==="cP"

        $flags=kNone;//so far
        _r( $dmlar->MakeCursor($curs, kParent,"CursP", $flags) );
        _r( $curs->Find("A") );
        $curs->Del("A");
                Get

        $flags=array(kCurrentNode, kAfterNode | kNextNode, kBeforeNode | kPrevNode, kFirstNode, kLastNode);
        Put("A", $flags);
        Put("B", array(kAfterNode|kNextNode, kBeforeNode|kPrevNode, kPinPointNode|kOverwriteThisNode), "A");
        Count($result);
        //a CURSOR should have 3 states, kExact, kBefore, kAfter   Node X, just in case Del() acts on node X, the cursor would be kAfter the element that was before X, or/and kBefore the element that was after X (ie. P->A, P->X, P->B)
         */

        _yntIFnot( $dmlDB=new dmlDBL1 );
        //debug_zval_dump($dmlDB);


        _ynif ($dmlDB->fFirstTime) {
                show("First time run!");
        } else {
                show("...using prev. defined table");
        }

        //_yntIFnot( $contents=file_get_contents("/home/emacs/phpnet.php") );
        __( $res=split("[ .,/\\\"\?\<\>&!;|\#\$\*\+\{\}=\(\)'`\n\-]",file_get_contents("dmlDBL0def.php")) );
        _yntIF(1===count($res));
        $i=2;
        $cnt=0;
        $prevval="";
        _artIFnot( $dmlDB->OpenTransaction() );
        $aborted=no;
        show("list of additions:");
        foreach ($res as $val) {
                     $val=trim($val);
                _ynif ($val) {//ie. non-empty
                        //if ($cnt % 15 == 0) {
                       // }

                   _TRY(

                        //_yntIFnot( $ret=$dmlar->EnsurePCRel($prevval, $val) );
                        $prevval=$val;
                        _artIFnot( $ret=$dmlDB->AddName($val) );
                        //echo retValue($ret);
                        //_if (isValue_InList(kAdded,$ret)) {
                        //_ifnot (isValue_InList(kAlready,$ret)) {
                        _ifnot (isFlagInList_L1(kAlready,$ret)) {
/*                                if ($i<6) {
                                        $i++;
                                } else {
                                        $i=2;
                                }
                                echo setcol($i).$val." ".br.nl;
                        } else {
                                $i=1;
                                echo setcol($i).$val." ".br.nl;*/
                                echo $val." ".nl;
                        }
                        //usleep(100000);
                        $cnt++;//echo "cnt=".$cnt.nl;

                        //if ($cnt % 15 == 0) {
                                //_yntIFnot( $dmlDB->CloseTransaction() );
                        //}

                   , _yntIFnot( $dmlDB->AbortTransaction());$aborted=yes ;break );//_TRY

                } //fi
        }
       echo nocol.nl;
        //if ( $cnt % 15 !== 0) { //left it open? if so close it
                _ynifnot($aborted) {
                        _artIFnot( $dmlDB->CloseTransaction() );
                }
        //}
       echo nocol.nl;



        _artIFnot( $dmlDB->IsName("if") );

        _artIFnot( $dmlDB->Show($into) );
        __( $arr=$into->fetchAll() );
        $count=count($arr);
        show( "Before del: $count times.");

        _TRY( $dmlDB->DelName("if") );
        __( $dmlDB->IsName("if") );

        _artIFnot( $dmlDB->Show($into) );
        __( $arr=$into->fetchAll() );
        $count=count($arr);
        show( "After del:  $count times.");

        //print_r($dmlDB->IsID("1"));

        $dmlDB=null;//ie. dispose()
/*
        //$arc=array();
        echo redcol.nl;
        _yntIFnot( $dmlar->GetOfChild_AllParents("if",$arc) );
        echo "Parents of 'if': ".retValue($arc).nl;

        _yntIFnot( $dmlar->GetOfParent_AllChildren("if",$arc) );
        echo "Children of 'if': ".retValue($arc).nl;

        _r( $dmlar->ynIsPCRel("text","if") );
        _r( $dmlar->DelPCRel("if","yes") );
        _r( $dmlar->DelPCRel("if","yes") );
        _r( $dmlar->ynIsPCRel("if","yes") );

        echo greencol.nl;
        _yntIFnot( $dmlar->GetOfParent_AllChildren("if",$arc) );
        echo "Children of 'if' after del child 'yes': ".retValue($arc).nl;

        _r( $dmlar->DelPCRel("text","if") );
        echo greencol;

        _yntIFnot( $dmlar->GetOfChild_AllParents("if",$arc) );
        echo "Parents of 'if' after parent 'text' del: ".retValue($arc).nl;


        echo purplecol.nl;
        _yntIFnot( $dmlar->GetOfChild_AllParents("not",$arc) );
        echo "Parents of 'not': ".retValue($arc).nl;

        _yntIFnot( $dmlar->DelAllChildrenOf("if") );

        __( $dmlar->GetOfParent_AllChildren("if",$arc) );
        echo "Children after del all children of 'if': ".retValue($arc).nl;

        _yntIFnot( $dmlar->GetOfChild_AllParents("not",$arc) );
        echo "Parents of 'not', not 'if'; after del: ".retValue($arc).nl;


        echo greencol.nl;
        __( $dmlar->GetOfParent_AllChildren("program",$arc) );
        echo "Children of 'program', before del 'if': ".retValue($arc).nl;

        _yntIFnot( $dmlar->GetOfChild_AllParents("if",$arc) );
        echo "Parents of 'if', before del: ".retValue($arc).nl;

        _yntIFnot( $dmlar->DelAllParents("if") );

        __( $dmlar->GetOfChild_AllParents("if",$arc) );
        echo "Parents of 'if', after del: ".retValue($arc).nl;

        __( $dmlar->GetOfParent_AllChildren("program",$arc) );
        echo "Children of 'program', after del 'if': ".retValue($arc).nl;
        $dmlar=null;//ie. dispose()
 */
/*
        echo nocol.nl;

        funcL1 (AnotherFunc,($someparam))
        {
                if (is_string($someparam)) {
                        setretflagL1($someparam);
                } else {
                        setretflagL1("not a string");
                }
        }endfuncL1("done")

        funcL1 (GetName,(&$name, $id))
        {
                print_r($name);
                print_r($id);
                echo nl;
                if ($id==1) {
                        addretflagL1(yes,no,kAdded);
                }
                if ($id==2) {
                        delretflagL1(no);
                        addretflagL1("a");
                }
                if ($id==3) {
                        setretflagL1(no);
                }
                countretflagsL1($numretflags);
                if ($numretflags <= 0) {
                        setretflagL1("other");
                }
        }endfuncL1("ReachedEndNormally")

        AnotherFunc("return1");
        AnotherFunc(1);
        AnotherFunc(2);

        $a="a";
        //_yntIFnot( $c=$debugL1->ShowTreeOfParentsForChild( GetName($a,"a") ) );
        echo isValidReturnL1($c);
        print_r( isValidReturnL1( GetName($a,"b") ));
        echo isValidReturnL1( GetName($a,"1") );
        echo isValidReturnL1( GetName($a,"3") );
        echo isValidReturnL1( GetName($a,"2") );
        echo isValidReturnL1( GetName($a,"c") );
*/
        global $debugL1;
        _yntIFnot( $debugL1->ShowTreeOfChildrenForParent(kAllFunctions) );
/*        _yntIFnot( $debugL1->ShowTreeOfChildrenForParent(kAllReturns) );
        _yntIFnot( $debugL1->ShowTreeOfParentsForChild("a") );
        _yntIFnot( $debugL1->ShowTreeOfParentsForChild(yes) );*/
        //_r( $debugL1->ynIsNode("a") );
        //_r( $debugL1->ynIsNode("if") );
        //$debugL1->a();
        __($debugL1->GetCursor_ofType_ofID($curs, kParent, kAllFunctions));
        show($debugL1);
        //$debugL1->b();
        _yntIFnot($curs->GetEnvironment($somevar));
        show($somevar);
        exit;

        __($ar=$curs->Get(kFirst, $id) );
        while (ynIsGood($ar)) {
                __($ar=$curs->Get(kNext, $id) );
        }

        __($curs->GetCursorTypeAndID($type,$id) );
        show($type);//kParent
        show($id);//'a'

        _yntIFnot($curs->Get(kLast, $id) );
        _yntIFnot($curs->Get(kPrev, $id) );
        _yntIFnot($curs->Get(kCurrent, $id) );
        _yntIFnot($curs->Get(kPinPoint, $id) );//aka Find
        _yntIFnot($curs->Get(kBefore,$what,$id) );
        _yntIFnot($curs->Get(kAfter,$what,$id) );

        _yntIFnot($curs->Find($id) );//next level wrapper for Get kPinPoint
        _yntIFnot($curs->Put(kFirst,$id) );
        _yntIFnot($curs->Put(kLast,$id) );
        _yntIFnot($curs->Put(kPrev,$id) );
        _yntIFnot($curs->Put(kNext,$id) );
        _yntIFnot($curs->Put(kCurrent,$id) );

        _yntIFnot($curs->Put(kAfter,$what,$id) );
        _yntIFnot($curs->Put(kBefore,$what,$id) );

        _yntIFnot($curs->Del(kAfter,$what) );
        _yntIFnot($curs->Del(kBefore,$what) );
        _yntIFnot($curs->Del(kCurrent) );
        _yntIFnot($curs->Del(kNext) );
        _yntIFnot($curs->Del(kPrev) );
        _yntIFnot($curs->Del(kFirst) );
        _yntIFnot($curs->Del(kLast) );
        _yntIFnot($curs->Del(kPinPoint, $id) );

        _yntIFnot($curs->Count($howmany) );

        __( $curs=null; );




        endprogram
// vim: fdm=marker
?>
