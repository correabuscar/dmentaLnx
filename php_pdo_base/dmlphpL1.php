//<?php
//header starts
#ifndef DMLPHPL1_PHP
#define DMLPHPL1_PHP

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
* Description: demlinks applied in coding(php) ie. using demlinks idea(s) to code in php
*               this is Level 1, the next higer level
*
***************************************************************************}}}*/


#include "shortdef.php"
#include "debug.php"
#include "color.php"
#include "dmlphpL0.php"

class dmlphpL1 extends dmlphpL0 {

        func (__construct(), dconstr)/*{{{*/
        {
                __( $ar=parent::__construct() );
                keepflags($ar);
        }endfunc()/*}}}*/

        func (__destruct(), ddestr)/*{{{*/
        {
                __( $ar=parent::__destruct() );
                keepflags($ar);
        }endfunc()/*}}}*/

        func (SetRel($parent,$child), dset)/*{{{*/
        {
                //well, no transaction... too bad
                _tIFnot( $ar=$this->addChild($parent, $child) );
                keepflags($ar);
                _tIFnot( $ar=$this->addParent($child, $parent) );
                keepflags($ar);
        }endfunc()/*}}}*/

        func (DelRel($parent,$child), dset)/*{{{*/
        {
                //well, no transaction... too bad
                _tIFnot( $ar=$this->delChild($parent, $child) );
                keepflags($ar);
                _tIFnot( $ar=$this->delParentFromChild($parent, $child) );
                keepflags($ar);
        }endfunc()/*}}}*/

        func (IsRel($parent,$child), dis)/*{{{*/
        {
                _if( TRUE===is_array(kChildrenOf[$parent]) && TRUE===in_array($child, kChildrenOf[$parent])) {
                        retflag(yes);
                } else {
                        retflag(no);
                }
        }endfunc()/*}}}*/

        func (SetOfParent_Children($parent,$children), dset)/*{{{*/
        {//overwrites all children
                _tIFnot( $this->DelAllChildrenOf($parent) );
                _tIFnot( $this->AppendToParent_Children($parent, $children) );
        }endfunc(yes)/*}}}*/

        func (AppendToParent_Children($parent,$children), dadd)/*{{{*/
        {//addition
                _ifnot( is_array($children) ) {
                        $children=array($children);
                }
                foreach ($children as $child) {
                        _tIFnot( $this->SetRel($parent, $child) );
                }
        }endfunc(yes)/*}}}*/

        func (DeleteFromParent_Children($parent,$children), dadd)/*{{{*/
        {//substraction
                _ifnot( is_array($children) ) {
                        $children=array($children);
                }
                foreach ($children as $child) {
                        _tIFnot( $this->DelRel($parent, $child) );
                }
        }endfunc(yes)/*}}}*/



}//endclass

#endif //header ends
// vim: fdm=marker
//?>
