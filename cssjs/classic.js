/*
 * Webinista WreadIt
 * classic.js: JavaScript for the edit post page when the Classic Editor is active.
 *
 * Note that this package includes libraries distributed with Apache 2.0 and MIT licenses.
 *
 * Copyright (C) 2025  Tiffany B. Brown, Webinista Inc.
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

'use strict';

class WebinistaWreadItClassic {
	static postId(){
	  let id = 0;

    const url = new URL(window.location);
    const { searchParams } = url;

    if( searchParams.has( 'post' ) ) {
      id = searchParams.get( 'post' );
    }

    return id;
  }

  static apiRequest(path = '', options = {}, callback = ()=>{} ) {
    if( !path ) {
      throw new Error('Path parameter is required.');
    }

    const opts = { method: 'GET', ...options };

    fetch( path, opts )
      .then(function(response) {

        // console.log( response.headers )

        if(response.ok) {
          return response.json();
        } else {
          return JSON.stringify( {} );
        }
      })
      .then((data) => {
        console.log( data )
      })
      .catch((error) => {
        alert( error );
      });

    return false;
  }

}

( ( w, d ) => {

  async function foo( path, options, callback ) {
    return WebinistaWreadItClassic.apiRequest( path )
  }

  var bar = foo(`/wp-json/wp/v2/posts/${WebinistaWreadItClassic.postId()}`);

  /* async function checkAudio() {

    const params = new URLSearchParams({
      post_id: WebinistaWreadItClassic.post_id(),
      _locale: 'user'
    });

    var foo = fetch(
    `/wreadit/v1/audio?=${params.toString()}`,
    {
      method: 'GET'
    })
    .then(function(response) {
      if(response.ok) {
        return response.json();
      } else {
        return JSON.stringify( {} );
      }
    })
    .then((data) => {
      console.log(data )
    })
    .catch((error) => {
      alert( error );
    });
  }

  checkAudio(); */

	window.addEventListener( 'load', () => {
    const requestAudio = jQuery('[id=wreadit_request_url]');



	});
} )( window, document );
