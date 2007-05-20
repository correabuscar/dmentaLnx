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
* Description: 
*
****************************************************************************/

#ifndef TIMEDINPUT_H
#define TIMEDINPUT_H
//this unit is necessary to make the diff between which type of input came
//first AKA the order of inputs ie. A, mouse_move_up, B; w/o this u'd get A,B
//of keyboard unit, then mouse_move_up on mouse unit, not knowing which input
//came first not to mention that between A and B there was the mouse motion.

#include "pnotetrk.h"
#ifdef ENABLE_TIMED_INPUT
        #include "globaltimer.h"
#endif

#include "threadslock.h"

#ifdef ENABLE_TIMED_INPUT
#define KEY_USES_THIS_TIMEVARIABLE gTimer //gActualKeyboardTime

#define MOUSE_USES_THIS_TIMEVARIABLE gTimer //gActualMouseTime
#endif

//FIXME(in progress): there are some hacks in timed*.* files battling to keep generalization but obviously failing

#define NEXT_ROTATION(m_Var,const_MAX) ((m_Var + 1) % const_MAX)
#define SET_NEXT_ROTATION(m_Var,const_MAX) m_Var = NEXT_ROTATION(m_Var, const_MAX)


enum {
        kKeyboardInputType
        ,kMouseInputType
//last:
        ,kMaxInputTypes
};


class TBaseInputInterface {
protected:
        //we may need these for future reference so we know if the key/mouse is real or emulated
        int fKeyFlags;
        int fMouseFlags;
public:
        TBaseInputInterface(){};
        virtual ~TBaseInputInterface(){};

        //removes it from buffer
        virtual function
        MoveFirstFromBuffer(void *into)=0;

        virtual function
        Query4HowManyInBuffer(int &m_HowMany)=0;

        virtual function
        Query4BufferFull(bool &m_Bool)=0;

        virtual function
        UnInstall()=0;

        virtual function
        Install()=0;

        virtual function
        Alloc(void *&dest)=0;//alloc mem and set dest ptr to it

        virtual function
        DeAlloc(void *&dest)=0;//freemem

        virtual function
        Compare(void *what, void *withwhat, int &result)=0;
        
#ifdef ENABLE_TIMED_INPUT
        virtual function
        GetMeTime(void * const &from, GLOBAL_TIMER_TYPE *dest)=0;
#endif

};//class

extern TBaseInputInterface *AllLowLevelInputs[kMaxInputTypes];//array of pointers

#define USING_COMMON_INPUT_BUFFER //anywhere since timedinput.h is included in:
#include "timedmouse.h" //for constants only
#include "timedkeys.h"


#define MAX_INPUT_EVENTS_BUFFERED (MAX_MOUSE_EVENTS_BUFFERED+MAX_KEYS_BUFFERED)
#define INPUT_TYPE InputWithTimer_st

struct INPUT_TYPE {
        int type;
        int how_many;
        INPUT_TYPE& operator=(const INPUT_TYPE & source);
        INPUT_TYPE& operator=(const volatile INPUT_TYPE & source);
};



#ifdef DISCREETE_CLEARENCE
extern volatile INPUT_TYPE gInputBuf[MAX_INPUT_EVENTS_BUFFERED];
extern volatile int gInputBufTail;
extern volatile int gInputBufPrevTail;//last Tail, used to cumulate input
extern int gInputBufHead;
extern volatile int gInputBufCount;//how many items in buffer
#endif


extern volatile int gLostInput[kMaxInputTypes];



function
MoveFirstGroupFromBuffer(INPUT_TYPE &m_Into);

function
Query4HowManyDifferentInputsInBuffer(int &m_HowMany);

function
Query4BufferFull(bool &m_Bool);//the buffer of the many different inputs? (consecutively different)

function
UnInstallAllInputs();

function
InstallAllInputs();



//used inside an int handler that's why we won't throw inside this:
void
ToCommonBuf(int input_type);

#endif
