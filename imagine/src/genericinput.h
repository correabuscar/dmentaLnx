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
* Description: helper to tranform from multiple input sources(key,mouse,serial)
*               to one type of input (generic input)
*
****************************************************************************/

#ifndef GENERICINPUT_H
#define GENERICINPUT_H
/*****************************************************************************/
#include "_gcdefs.h"

//#include <allegro.h>
#include "timedinput.h"
#include "pnotetrk.h"
#include "buffer.h"
#include "SLL.h"
//#include "generichelper.h"

/*****************************************************************************/

/*****************************************************************************/
enum EnumAllGI_t {//these are NOT indexes, may start from anywhere
        kGI_Undefined=2000,//trapping bugs?
        kGI_Quit,
        kGI_OnCam1,
        kGI_OnCam2,
        kGI_OnCam3,
        kGI_OnCam4,
        kGI_NextSetOfValues,//temp
        kGI_Hold1Key,
        kGI_CamRollRight_byMouse,

        kGI_CamSlideBackward,
        kGI_CamSlideForward,
        kGI_CamSlideDown,
        kGI_CamSlideUp,
        kGI_CamSlideRight,
        kGI_CamSlideLeft,
        kGI_CamRollRight,
        kGI_CamRollLeft,
        kGI_CamPitchDown,
        kGI_CamPitchUp,
        kGI_CamTurnRight,
        kGI_CamTurnLeft,
        kGI_Aspect,
        kGI_FOV,

        kGI_Hold1Key_stop,
        kGI_CamSlideBackward_stop,
        kGI_CamSlideForward_stop,
        kGI_CamSlideDown_stop,
        kGI_CamSlideUp_stop,
        kGI_CamSlideRight_stop,
        kGI_CamSlideLeft_stop,
        kGI_CamRollRight_stop,
        kGI_CamRollLeft_stop,
        kGI_CamPitchDown_stop,
        kGI_CamPitchUp_stop,
        kGI_CamTurnRight_stop,
        kGI_CamTurnLeft_stop,
        kGI_Aspect_stop,
        kGI_FOV_stop,

        //last:
        //kMaxGIs
};

/*****************************************************************************/
//this is the type of the output from GenericInput and input to Actions
#define GENERICINPUT_TYPE EnumAllGI_t//GenericHelper_st<EnumAllGI_t> //used to be int
#define TRANSDUCER_S__TYPE void


/*****************************************************************************/
extern TBuffer<GENERICINPUT_TYPE> GenericInputBuffer;
#ifdef ENABLE_TIMED_INPUT
extern GLOBAL_TIMER_TYPE gLastGenericInputTime;
#endif
/*****************************************************************************/
struct UnifiedInput_st {
        int type;
        TRANSDUCER_S__TYPE *data;
};

/*****************************************************************************/
struct OneGenericInputTransducer_st {
        GenericSingleLinkedList_st<TRANSDUCER_S__TYPE> *Head;//in a list of same type inputs
        GenericSingleLinkedList_st<TRANSDUCER_S__TYPE> *Tail;
        int HowManySoFar;//counter, prolly just informative
        GenericSingleLinkedList_st<TRANSDUCER_S__TYPE> *WhosNext;//to be compared with input

        //this is set once, to be the output generinc input when combination
        //is fulfilled
        GENERICINPUT_TYPE Result;//trasforming SerialInputs into one
        //GenericInput which is this 'Result'
        int LostInputsBecauseTheyDidntMatch;

/******************/
//        OneGenericInputTransducer_st& operator=(
  //                      const OneGenericInputTransducer_st & source);
/******************/
        OneGenericInputTransducer_st();
/******************/
        //copy constructor
//        OneGenericInputTransducer_st(const OneGenericInputTransducer_st &rhs);
/******************/
        ~OneGenericInputTransducer_st();
/******************/
        EFunctionReturnTypes_t
        Append(const TRANSDUCER_S__TYPE* a_Dat);
/******************/
        bool HasStarted();
/******************/
        EFunctionReturnTypes_t
        RestartIfStarted();
/******************/
        function
        Reset();
/******************/
        function
        EatThis(
                        const UnifiedInput_st & what,
                        const bool reset_when_failed);
/******************/
        function
        PushToBuffer();
/******************/
  //      void
    //    MakeSureChildsAreGone();
/******************/
};//END struct

/*****************************************************************************/
struct GI_SLLTransducersArray_st{
        GenericSingleLinkedList_st<OneGenericInputTransducer_st> *Head;
        GenericSingleLinkedList_st<OneGenericInputTransducer_st> *Tail;
        int HowManySoFar;//counter, prolly just informative

/******************/
        GI_SLLTransducersArray_st();
/******************/
        ~GI_SLLTransducersArray_st();
/******************/
        EFunctionReturnTypes_t Append(
                        const OneGenericInputTransducer_st * const a_Dat);
/******************/
};//END struct

/*****************************************************************************/
extern GI_SLLTransducersArray_st GI_StrictOrderSLL[kMaxInputTypes];//head, may be NULL
extern GI_SLLTransducersArray_st GI_RelaxedOrderSLL[kMaxInputTypes];//head -//-

/*****************************************************************************/
/*****************************************************************************/
function
GenericInputHandler(
                const UnifiedInput_st & from);
/*****************************************************************************/
function
InitGenericInput();
/*****************************************************************************/
function
DoneGenericInput();
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/


#endif

