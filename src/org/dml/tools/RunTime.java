/**
 * 
 * Copyright (C) 2005-2010 AtKaaZ <atkaaz@users.sourceforge.net>
 * Copyright (C) 2005-2010 UnKn <unkn@users.sourceforge.net>
 * 
 * This file and its contents are part of DeMLinks.
 * 
 * DeMLinks is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * DeMLinks is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with DeMLinks. If not, see <http://www.gnu.org/licenses/>.
 */



package org.dml.tools;



import org.dml.error.BadCallError;
import org.dml.error.BugError;
import org.javapart.logger.Log;



/**
 * these mustn't be disabled, because they're sometimes calling methods as
 * parameters<br>
 * uhm... nvm, it seems the methods in params will always get executed
 */
public class RunTime {
	
	public static void bug() {

		bug( "Bug detected." );
	}
	
	public static void bug( String msg ) {

		throw new BugError( "Bug detected: " + msg );
	}
	
	public static void badCall() {

		badCall( "" );
	}
	
	public static void badCall( String msg ) {

		String msg2 = "BADCALL: " + msg;
		Log.thro( msg2 );// FIXME: add a minus level param to show caller's pos
		throw new BadCallError( msg2 );
	}
	
	public static void thro( Exception ex ) throws Exception {

		Log.thro( ex.getLocalizedMessage() );
		throw new Exception( ex );
	}
	
	public static void thro( RuntimeException rtex ) {

		// FIXME: maybe here show the level before this, ie. the line and file
		// prior to this call
		Log.thro( rtex.getLocalizedMessage() );
		throw new RuntimeException( rtex );
	}
	
	
	/**
	 * @param b
	 */
	public static void assumedTrue( boolean b ) {

		if ( !b ) {
			throw new AssertionError( "expected true condition was false!" );
		}
	}
	
	public static void assumedNotNull( Object... obj ) {

		for ( int i = 0; i < obj.length; i++ ) {
			if ( null == obj[i] ) {
				throw new AssertionError( "expected non-null object[" + ( i + 1 ) + "] was null!" );
			}
		}
	}
	
	public static void assumedNull( Object... obj ) {

		for ( int i = 0; i < obj.length; i++ ) {
			if ( null != obj[i] ) {
				throw new AssertionError( "expected null object[" + ( i + 1 ) + "] was NOT null!" );
			}
		}
	}
	
	public static void assumedFalse( boolean b ) {

		if ( b ) {
			throw new AssertionError( "expected false condition was true!" );
		}
		
	}
	
}
