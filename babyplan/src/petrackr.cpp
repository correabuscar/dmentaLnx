/****************************************************************************
*
*                             dmental links
*	Copyright (c) 28 Feb 2005 AtKaaZ, AtKaaZ at users.sourceforge.net
*    Portions Copyright (c) 1983-2002 Sybase, Inc. All Rights Reserved.
*
*  ========================================================================
*
*    This file contains Original Code and/or Modifications of Original
*    Code as defined in and that are subject to the Sybase Open Watcom
*    Public License version 1.0 (the 'License'). You may not use this file
*    except in compliance with the License. BY USING THIS FILE YOU AGREE TO
*    ALL TERMS AND CONDITIONS OF THE LICENSE. A copy of the License is
*    provided with the Original Code and Modifications, and is also
*    available at www.sybase.com/developer/opensource.
*
*    The Original Code and all software distributed under the License are
*    distributed on an 'AS IS' basis, WITHOUT WARRANTY OF ANY KIND, EITHER
*    EXPRESS OR IMPLIED, AND SYBASE AND ALL CONTRIBUTORS HEREBY DISCLAIM
*    ALL SUCH WARRANTIES, INCLUDING WITHOUT LIMITATION, ANY WARRANTIES OF
*    MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, QUIET ENJOYMENT OR
*    NON-INFRINGEMENT. Please see the License for the specific language
*    governing rights and limitations under the License.
*
*  ========================================================================
*
* Description: provides personalized FIFO error tracking capab@source level
*
****************************************************************************/

#include <stdlib.h>
#include <stdio.h>
#include "petrackr.h"

uderrtrk *etracker;

uderrtrk::uderrtrk(){
}
uderrtrk::~uderrtrk(){
}

void uderrtrk::usrshowthemall(){
	s_item *tmp=getlasterr();
	while (tmp){
		fprintf(stderr,
			"%stypeTRKd::lvl#%d `%s'\nIn func `%s' from file `%s' at line `%d'\n"
			,str_t_all[tmp->error.errtype]
			,tmp->error.level
			,tmp->error.userdesc
			,tmp->error.funx
			,tmp->error.errfile
			,tmp->error.errline
		);
		clrlasterr();
		tmp=getlasterr();
	}//while
}


void init_error_tracker(){
	etracker=new uderrtrk;
	if (!etracker) {
		fprintf(stderr,"error allocating etracker pointer in file %s at line %d in func %s\n",__FILE__,__LINE__,__func__);
		abort();
	}
}

void deinit_error_tracker(){
	if (etracker) {
		if (etracker->getlasterr()!=NULL){//show all errors before kill
			etracker->usrshowthemall();
		}
		delete etracker;
		etracker=NULL;
	}
}


