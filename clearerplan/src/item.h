/****************************************************************************
*
*                             dmental links
*    Copyright (c) 28 Feb 2005 AtKaaZ, AtKaaZ at users.sourceforge.net
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
* Description: the part of demlinks which interfaces Items (of Lists)
*
****************************************************************************/


#ifndef ITEM_____H
#define ITEM_____H

#include "common.h"


class MItem : private TRecordsStorage {
private:
        /* sizeof Item_st struct */
        const long fRecSize;
        const long fHeaderSize;

        /* true if Init was called and succeded */
        bool fInited;
public:
        /* constructor */
        MItem();
        /* destructor */
        ~MItem();

        /* open the file + init stuff
         * use this after constructor somewhere */
        bool Init(const char * a_FileName);

        /* use cache from now on... see recstor.h */
        bool InitCache(const RecNum_t a_MaxNumRecordsToBeCached);

        /* is cache enabled ? */
        bool IsCacheEnabled() { return TRecordsStorage::IsCacheEnabled();};


        /* stop using cache, frees some memory and also flushes the writes */
        bool KillCache();

        /* flush writes and close the file + cleanup stuff
         * use this before destructor, but is not necessary since it's called
           from within it if needed */
        bool DeInit();

        /* retrieve the record(contents) of an Item with the specified ID*/
        bool ReadWithID(
                        const ItemID_t a_ItemID,
                        Item_st &a_Into);

        /* write by overwritting prev data, the contents at a spec. ID */
        bool WriteWithID(
                        const ItemID_t a_ItemID,
                        const Item_st &a_From);

        /* who's the last ID in database
         * returns false if an error
         * bewarned that the ID may be 0 which is kNoItemID meaning there are
           no IDs */
        bool GetLastID(ItemID_t &a_ItemID);

        /* scather the data into the struct, used before Write
         * prev and/or next may be kNoItemID */
        bool Compose(
                        Item_st &a_Item_st,
                        const AnyReferrerID_t a_ReferrerID,
                        const ItemID_t a_PrevItem,
                        const ItemID_t a_NextItem);

        /* create a new item, doesn't connect anything
         * return its ID or kNoItemID if failed */
        ItemID_t AddNew(const Item_st &a_Item_st);

private:
        bool IsInited() const { return fInited; };
        void SetInited() { fInited = true; };
        void SetNotInited() { fInited = false; };
};




#endif
