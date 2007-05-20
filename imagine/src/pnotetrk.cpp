/****************************************************************************
*
*                             dmental links
*    Copyright (C) 2005 AtKaaZ, AtKaaZ at users.sourceforge.net
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
* Description: provides personalized FIFO notify tracking capab@source level
*
****************************************************************************/

#include <stdlib.h>
#include <stdio.h>
#include <exception>
#include <signal.h>

#include "pnotetrk.h"

/* global uniq variable, this is where we keep all errors
 * this is used in almost all source files */
MNotifyTracker gNotifyTracker;
bool gTrackFRETs=false; //if this is true then it will track _fret() even if TRACKABLE_FRET is not defined
bool gTrackHRETs=false; //if this is true then it will track _hret() even if TRACKABLE_FRET is not defined
bool gTrackFlags=false; //if this is true then it will track _makeFLAG()

/*  the textual descriptions of notification types
 *  ie. "WARN" */
const PChar_t kNotifyDescriptions[kNumNotifyTypes]={
        "NONE",
        "\x1B[33mWARN",
        "\x1B[31mERR",
        "\x1B[34mFuncFail",
        "\x1B[35mEXIT",
        "\x1B[33mINFO",
        "\x1B[37mexception",

        "\x1B[31mBUG?",//possible bug, check it out!

        /* triggered by a paranoid check(which should always be false) */
        "\x1B[31mFATAL"
};


/* constructor */
MNotifyTracker::MNotifyTracker():fIsOn(false)
{
        SetOn();
}

/* destructor */
MNotifyTracker::~MNotifyTracker()
{
        /* show them all if we didn't got the chance */
        if (IsOn()) {
                if (GetLastNote())
                        ShowAllNotes();
                SetOff();
        }
}

/* adds a notification and checks to see if we failed to properly add it
 * if so displays a message and aborts the running program */
void
CheckedAddNote(
        const NotifyType_t a_NotifyType,
        PChar_t a_Desc,
        File_t a_FileName,
        Func_t a_Func,
        const Line_t a_Line)
{
        if (gNotifyTracker.IsOff()) {
                fprintf(stderr,
                        "\x1B[31mThe error to be reported was:\n%s#%d: line(%u) file(%s) func(%s) \n\t`%s'\x1B[39m\n",
                        kNotifyDescriptions[a_NotifyType],
                        0,
                        a_Line,
                        a_FileName,
                        a_Func,
                        a_Desc
                        );
                fprintf(stderr,"\x1B[31mlame programmer: notification subsystem is "
                                "not initialized, now this should not happen!\x1B[39m\n");
#ifdef CONTINUE_IF_NOTINITED
                return;
#else
                abort();
#endif
        }

        bool tmpres=gNotifyTracker.AddUserNote(a_NotifyType,
                                                a_Desc,
                                                a_FileName,
                                                a_Func,
                                                a_Line);

        if ((tmpres==false) && (gNotifyTracker.HasFailedInternally())) {

                fprintf(stderr,
                        "\x1B[31mThe NotifyTracker has failed internally.\n"
                        "This is perhaps due to lack of memory.\n"
                        "However we're going to try and show you %d"
                        "notifications before aborting...\x1B[39m\n",
                        gNotifyTracker.GetNumNotes());

                gNotifyTracker.ShowAllNotes();

                abort();
        }

}

/* show a list of all notifications that happened since last time the list was
 * empty AND clear them out as you show them! */

void
MNotifyTracker::ShowAllNotes()
{
if (IsOn()) {
        NotifyItem_st *tmp=MoveOutLastNote();
        while (tmp){
                //so you'll have to tripple click on the './zvim' line
                fprintf(stderr,
                        "%s\x1B[39m#\x1B[33m%d\x1B[39m: func(\x1B[32m%s\x1B[39m)\n\t`\x1B[36m%s\x1B[39m'\n\t\x1B[38m./zvim %s +%u\x1B[39m\n",
                        kNotifyDescriptions[tmp->Contents.Type],
                        tmp->Contents.Depth,
                        tmp->Contents.Func,
                        tmp->Contents.UserDesc,
                        tmp->Contents.File,
                        tmp->Contents.Line
                        );
                tmp=MoveOutLastNote();
        }
}//fi
}

void SIGINT_sighandler_t(int)
{
        gNotifyTracker.SetOff();
}
void SIGSEGV_sighandler_t(int)
{
        gNotifyTracker.SetOff();
}

//needed like this for ... see SetOn()
void
ShutDownNT(void)
{
        gNotifyTracker.SetOff();
}

void
MNotifyTracker::SetOff(void)
{
        if (IsOn()) {
                ShowAllNotes();
                //delete gNotifyTracker;
                //gNotifyTracker=NULL;
                signal(SIGINT,SIG_DFL);
                signal(SIGSEGV,SIG_DFL);
                fIsOn=false;
        }
}

void
MNotifyTracker::SetOn()
{
        WARN_IF(IsOn());
        fIsOn=true;
        /* make sure the sutdown procedure is called on normal exit, even if the
         * programmer forgets to call it */
        ERR_IF( 0 != atexit(ShutDownNT),
                        abort());
        //std::set_terminate (__gnu_cxx::__verbose_terminate_handler);
        std::set_unexpected(ShutDownNT);
        std::set_terminate(ShutDownNT);
        signal(SIGSEGV,SIGSEGV_sighandler_t);
        signal(SIGINT,SIGINT_sighandler_t);
}



void
MNotifyTracker::PurgeAllNotifications()
{
        if (IsOn())
                PurgeThemAll();
}

void
ShowAllNotifications()
{
        if (gNotifyTracker.IsOn())
                gNotifyTracker.ShowAllNotes();
}

