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

  static audioId(){
	  let id = 0;

    console.log( jQuery('[id=wreadit_audio_id]') )

    return id;
  }

  static nonce(){
    return jQuery('[id=wreadit_nonce]').val()
  }

  static apiRequest(path = '', options = {}, callback = ()=>{} ) {
    if( !path ) {
      throw new Error('Path parameter is required.');
    }

    const opts = {method: 'GET', ...options };

    window.fetch( path, opts )
      .then(function(response) {
        if(response.ok) {
          return response.json();
        } else {
          return JSON.stringify( {} );
        }
      })
      .then( callback )
      .catch(( error ) => { console.log( error ); });
    return false;
  }

  static buildQuery( obj = {} ) {
    const params = new URLSearchParams( obj );

    return params.toString();
  }

  static updateViewWithData( data ) {
    console.log('--- updateViewWithData ---')
    console.log( data )
  }
}

( ( w, d ) => {
  async function writeClipboardText(text) {
    try {
      await navigator.clipboard.writeText(text);
    } catch (error) {
      console.error(error.message);
    }
  }

  async function makeRequest( path, options, callback ) {
    return WebinistaWreadItClassic.apiRequest( path, options, callback );
  }

  let wreadItData = {};
  const requestAudio = jQuery('[id=wreadit_request_url]');
  const copy2Clipboard = jQuery('[id=wreadit_copy_to_clipboard]');
  const deleteAudio = jQuery('[id=wreadit_delete_audio]');

	window.addEventListener( 'load', () => {
    const params = WebinistaWreadItClassic.buildQuery({
      post_id: WebinistaWreadItClassic.postId()
    });

    wp.apiRequest({path: `/wreadit/v1/audio?${params}` }).then( (response) => {
	      wreadItData = {...wreadItData, ...response}
    });
	});


   requestAudio.on('click', (event) => {

	  console.log( wreadItData );

      try {
        alert(' make the requests')
      } catch( error ) {
        console.log( error )
      }
  });


      /* const requestObj = {
			path: '/wreadit/v1/audio',
			data: {
				post_id: WebinistaWreadItClassic.postId(),
				_wpnonce: WebinistaWreadItClassic.nonce()
			},
			method: 'POST',
		};
      wp.apiRequest( requestObj ).then( posts => {
	      console.log( posts );
      });



    });

     copy2Clipboard.on('click', (event) => {
      let url = '';
      if(jQuery('[id=wreadit_url]').val() ) {
        url = jQuery('[id=wreadit_url]').val();
      }
      writeClipboardText( url )
    });

    deleteAudio.on('click', (event) => {
      const data = {
        audio_id: WebinistaWreadItClassic.audioId(),
        post_id: WebinistaWreadItClassic.postId()
      }

      makeRequest(
          `/wp-json/wreadit/v1/audio/delete/`,
          {
            method: 'POST',
            body: JSON.stringify( data )
          },
          ( response ) => { console.log( response ) }
        )
    }); */

} )( window, document );
