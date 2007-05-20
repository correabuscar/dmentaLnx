<?php

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
* Description: handles many things when the scrips is serving the webpage
*
***************************************************************************}}}*/

//don't include term.php here, because it uses this script


define("kRead","kRead");//first time request/GET
define("kWrite","kWrite");//ie. POST, not first time

static $ReqMethod;
static $Served=false;//not served, ie. terminal; if($Served) should be true if script is used on a webpage
static $PageVars;


function Served()
{
        global $Served;
        return $Served;
}

function PageVars()
{
        global $PageVars;
        return $PageVars;
}
function ReqMethod()
{
        global $ReqMethod;
        return $ReqMethod;
}


if (isset($_SERVER['REQUEST_METHOD']) ) {
        $ReqMethod=$_SERVER['REQUEST_METHOD'];
        if (!empty($ReqMethod)) {
                eval('$PageVars=&$_'.$ReqMethod.";");
                if (0===count($PageVars) && ("GET"===$ReqMethod)) {
                        $Served=kRead;
                } else {
                        $Served=kWrite;
                }
        }
} else {
        $Served=false;
}

// vim: fdm=marker
?>
