/*  Copyright (C) 2005-2008 AtKaaZ <atkaaz@users.sourceforge.net>
 	
 	This file and its contents are part of DeMLinks.

    DeMLinks is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    DeMLinks is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with DeMLinks.  If not, see <http://www.gnu.org/licenses/>.
*/

package org.demlinks.javathree;

import static org.junit.Assert.*;
import java.util.NoSuchElementException;
import org.junit.Before;
import org.junit.Test;

public class Environment_L2Test {

	Environment_L2 env;
	Node _a, _b;
	Id ida, idb;
	
	@Before
	public void init() throws Exception {
		env = new Environment_L2();
		ida = new Id("a");
		idb = new Id("b");
		//link two new nodes "a"->"b"
		assertTrue( env.link(ida, idb) );
		_a = env.getNode(ida);//get the Node object who's ID is "a"
		_b = env.getNode(idb);
		assertTrue(null != _a);
		assertTrue(null != _b);
		
		
	}
	
	@Test
	public void testLinkTo() throws Exception {
		
		assertTrue( _a.isLinkForward(_b) );// does link _a -> _b exists ? also implies _a <- _b exists
		//assertTrue( _a.isLinkTo(new String("b")) );
		//assertTrue(env.isLink(_a, "b"));
		assertTrue(env.isLink(_a, idb));
		assertTrue(env.isLink(_a, new Id("b")));//working is the king of war
		assertTrue( _b.isLinkBackward(_a) );
		
		assertTrue( (new Id("b")).equals(env.getID(_b)));
		assertTrue( env.getID(_a).equals(new Id("a")));
	}

	@Test
	public void testLink() throws Exception {
		
		Id idc = new Id("c");
		assertTrue( null == env.getNode(idc) ); //inexistent Node
		
		//assertTrue( null == env.getID(new Node(env)));
		

		
		env.link(_a,idc); // link between existing node _a and new node "c"
		assertTrue(env.isLink(_a,idc));
		
		Node _c = env.getNode(idc);
		assertTrue(null != _c);
		
		assertTrue(env.isLink(_a, _c));//same test different identifying ways
		assertTrue(env.isLink(new Id("a"), _c));//same test different identifying ways
		assertTrue(env.isLink(new Id("a"), new Id("c")));//same test different identifying ways
		
		env.link(_b,_c); //link two existing nodes
		assertTrue(env.isLink(_b, _c));
		
		env.link(new Id("d"), _c);//new node "d"
		assertTrue(env.isLink(new Id("d"), _c));
		
		Node _d = env.getNode(new Id("d"));
		assertTrue(env.isLink(_d, _c));
		

		// -------------------------
		
		Id allChars = new Id("AllChars");
		Id __A = new Id("A");
		assertTrue( env.link(allChars,__A) );
		assertFalse( env.link(allChars,__A) );
		assertFalse( env.link(allChars,new Id("A")) );
		assertFalse( env.link(allChars,new Id(String.format("%c", 65))) );
		assertTrue( env.getNode(allChars).getForwardList().size() == 1);
		Node ac = env.getNode(allChars);
		addAllChars();
		
		NodeRefsList chi = ac.getForwardList();
		Node nn = new Node();
		chi.addLast(nn);
		try {
			parseTree(allChars,20,"");
			fail("should've errored");
		}catch (Error e) {
			System.out.println("ignore above^^");
		}
		
		assertTrue( nn == chi.removeNode(Location.LAST) );
		parseTree(allChars,20,"");
		parseTree(new Id("a"),20,"");
		parseTree(new Id("d"),20,"");
		parseTree(new Id("c"),20,"");

	}
	
	public void addAllChars() throws Exception {
		Node _a = env.getNode(new Id("AllChars"));
		for (int i = 65; i < 72; i++) {
			env.link(_a,new Id(String.format("%c", i)));
			assertTrue( env.isLink(_a,new Id(String.format("%c", i))) );
		}
	}

	public void parseTree(Id ID, int downToLevel, String whatWas) {
		whatWas+=ID;
		if  (downToLevel < 0) {
			System.out.println(whatWas+" {max level reached}");
			return;
		}
		Node nod = env.getNode(ID);
		if (null == nod) { // this will never happen (unless first call)
			throw new NoSuchElementException();
		}
		NodeRefsList list = nod.getForwardList();
		NodeRef parserNR=null;
		parserNR = list.getNodeRefAt(Location.AFTER, parserNR);
		if (null == parserNR) { //no more children
			System.out.println(whatWas);
			return;
		} else {
			do {
				Id id = env.getID(parserNR.getNode());
				if (null == id) {
					throw new AssertionError();
				}
				parseTree(id, downToLevel - 1, whatWas+"->");
				parserNR = list.getNodeRefAt(Location.AFTER, parserNR);
			} while (null != parserNR);
		}

	}

	
	@Test
	public void testUnMappedNode() throws Exception {
		//this will test behavior while using an unmapped new Node() , that is: it has no ID associated with it (at least not in this environment)
		
		//you know, never catch Errors ... in general, if you do here things may remain inconsistent link-wise
		assertFalse( env.link(ida,idb) );
		
		Node _boo = new Node();//a node that's not mapped ID to Node in the environment
		boolean errored=false;
		try {
			env.link(_boo, _a); //an attempt to link these nodes should fail because one of them is not in the environment/mapped
		} catch (Error e) {
			errored=true;
		}
		assertTrue(errored);
		
		assertFalse(env.isLink(_boo, _a));
		
		errored=false;
		try {
			assertTrue( _boo.linkForward(_a) );//_boo has no corresponding ID ! but this is done at Node level
		} catch (Error e) {
			errored=true;
		}
		assertFalse(errored);

		errored=false;
		try {
			assertFalse(env.isLink(_boo, _a)); //inconsistent link detected, Error
		}catch (Error e) {
			errored = true;
		}
		assertTrue(errored);
		
	}
	
	@Test
	public void testNullParameters() throws Exception {
		
		Id nullId = null;
		Node nullNode = null;
		Id fullId = new Id("something");
		Node fullNode = new Node();
		
		boolean excepted=false;
		
		try {
			env.link(ida, new Node());
		} catch (Error e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(new Node(),ida);
		} catch (Error e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(nullId, nullId);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted=false;
		try {
			env.link(nullNode, nullNode);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted=false;
		try {
			env.link(nullId, nullNode);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted=false;
		try {
			env.link(nullNode, nullId);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(fullId, nullId);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(fullId, nullNode);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(nullId, fullId);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(nullNode, fullId);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(_a, nullId);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(fullNode, nullNode);
		} catch (Error e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(nullId, _a);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
		excepted = false;
		try {
			env.link(nullNode, fullNode);
		} catch (NullPointerException e) {
			excepted = true;
		}
		assertTrue(excepted);
		
	}
	
}
