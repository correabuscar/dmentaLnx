/****************************************************************************
*
*                             dmental links
*    Copyright (C) June 2006 AtKaaZ, AtKaaZ at users.sourceforge.net
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
* Description: demlinks environment level 2
*
****************************************************************************/

#ifndef DMLENVL2_H__
#define DMLENVL2_H__

#include "dmlenvl1.h"

/****************************/
                        /* we need that ":public TDMLPointer {"(below) if we want to use it like in NewPoint() in setup_inputs.cpp currently lines 153 and 317 */
class MDMLDomainPointer:public TDMLPointer { //can be NULL or only values from  either a kGroup or a kSubGroup Node;
private:
        ENodeType_t fDomainType;
        NodeId_t fDomainId;

        MDMLDomainPointer();//unusable constructor; purposely made private

        function
        verify(const NodeId_t a_Pointee);
public:
        MDMLDomainPointer(TLink *m_WorkingOnThisTLink);
        ~MDMLDomainPointer();

        function
        InitDomPtr(
                const ENodeType_t a_NodeType, //this should always be kGroup! otherwise you will poison the pointees which you will force them to point to you as a kSubGroup (in other words the pointee is a kGroup which will point to you as a kSubGroup, and maybe that pointee is in turn a kGroup pointer to something else and it having two kSubGroups will invalidate the pointer because a kGroup pointer is supposed to have only one kSubGroup - the pointee) anyways you could totally reverse the kSubGroup and kGroup in all items and thus this param will be useful; or for any other reason.
                const NodeId_t a_Name, //pointer name
                const ENodeType_t a_DomainType,
                const NodeId_t a_DomainId, //domain in which pointer can be assigned values from.
                const int a_Flags=kNone,
                DbTxn *a_ParentTxn=NULL
                );

/*        bool
        IsInited();*/

        function
        GetPointee(
                NodeId_t &m_NodeId
                );

        function
        SetPointee(
                const NodeId_t a_NodeId
                );
};
/****************************/
class MDMLFIFOBuffer: private TDMLCursor, MDMLDomainPointer {
private:
        MDMLFIFOBuffer();//constructor
public:
        MDMLFIFOBuffer(TLink *m_WorkingOnThisTLink);
        ~MDMLFIFOBuffer();
};//MDMLFIFOBuffer
/****************************/
/****************************/

/****************************/
/****************************/
/****************************/



#endif
