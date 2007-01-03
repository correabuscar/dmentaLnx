//<?php
#ifndef DEBUGL1_PHP
#define DEBUGL1_PHP

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
* Description: this include file will help with tracking; Level 1;
*              it will use dmlphp*.php to hold data
*
***************************************************************************}}}*/


#include "shortdef.php"
#include "color.php"
#include "dmlphpL1.php"
#include "debugL0.php"

if (!is_a($debugL1,"dmlphpL1")) {
        echo "Initializing dmlphpL1".nl;
        $debugL1=new dmlphpL1;
}

define(kAllFunctions,"kAllFunctions");
define(kAllReturns,"kAllReturns");

#define addretflagL1(...) \
        _yntIFnot( $debugL1->AppendToParent_Children($TheReturnOfThisTime_forThisFunction, array(__VA_ARGS__) ) );

#define delretflagl1(...) \
        _yntIFnot( $debugL1->DeleteFromParent_Children($TheReturnOfThisTime_forThisFunction, array(__VA_ARGS__) ) );

#define setretflagl1(...) \
        _yntIFnot( $debugL1->SetOfParent_Children($TheReturnOfThisTime_forThisFunction, array(__VA_ARGS__) ) );

#define countretflags(_into) \
        _yntIFnot( $debugL1->GetCountOfChildren_OfParent(_into, $TheReturnOfThisTime_forThisFunction) );

#define funcl1(funcname, funcparams,...) \
        function funcname funcparams \
        { \
                $funcnameALKSD=#funcname." (vim ".getfile." +".getline.")"; \
                $returnIDForThisFunction="AllReturnsForFunction: ".$funcnameALKSD; \
                global $debugL1; \
                _yntIFnot( $debugL1->AddRel(kAllFunctions, $funcnameALKSD) ); \
                _yntIFnot( $debugL1->AddRel(kAllReturns, $returnIDForThisFunction) ); \
                _yntIFnot( $debugL1->AddRel($funcnameALKSD, $returnIDForThisFunction) ); \
                _yntIFnot( $debugL1->GetCountOfChildren_OfParent($TheReturnOfThisTime_forThisFunction, $returnIDForThisFunction) ); \
                $TheReturnOfThisTime_forThisFunction++; \
                $TheReturnOfThisTime_forThisFunction=#funcname.$TheReturnOfThisTime_forThisFunction; \
                _yntIFnot( $debugL1->AddRel($returnIDForThisFunction, $TheReturnOfThisTime_forThisFunction) );

#define endnowl1(...) \
                addretflagL1(__VA_ARGS__); \
                _yntIFnot( $debugL1->GetOfParent_AllChildren($TheReturnOfThisTime_forThisFunction, $tmpASKD) );/*must have at least one return flag*/ \
                return $TheReturnOfThisTime_forThisFunction;

#define endfuncl1(...) \
                endnowl1(__VA_ARGS__); \
        }

function isValidReturn($val)
{
        global $debugL1;
        //kAllReturns -> $returnIDForThisFunction -> $TheReturnOfThisTime_forThisFunction(aka $val)
        //find parent $X for the child $val, where $X has the parent kAllReturns
        //in other words: kAllReturns -> $X -> $val    ... find $X, if any
        //but, what we do wanna know is whether $val is a child of kAllReturns, thus it would be a valid return from a function
        _ynif (yes===ynIsGood($debugL1->TestElementInvariants($val)) && yes===ynIsGood($debugL1->GetOfChild_AllParents($val, $parents))) {
                foreach ($parents as $p) {
                        _ynif ($debugL1->GetOfChild_AllParents($p, $parentsofP) ) {
                                foreach ($parentsofP as $pp) {
                                        if ($pp === kAllReturns) {
                                                _yntIFnot( $debugL1->GetCountOfChildren_OfParent($count, $val) );
                                                _yntIFnot($count > 0);//bug in the program
                                                return TRUE;
                                        }
                                }
                        }
                }
        }
        return FALSE;
}


// vim: fdm=marker

#endif //header
//?>
