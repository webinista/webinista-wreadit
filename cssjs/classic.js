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

    const opts = {method: 'GET', ...options };

    fetch( path, opts )
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
    const exists = jQuery('[id=webinista_wreadit_audiogen] div > :has([id=wreadit_url])');
    const gen = jQuery('p:has([id=wreadit_request_url])');

    if( !!data.length ) {
      jQuery('[id=wreadit_url]').attr('value', data[0].source_url );

      gen.attr('hidden', true);
      exists.removeAttr('hidden');
    } else {

      gen.attr('hidden', true);
    }
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

  const params = WebinistaWreadItClassic.buildQuery({
    parent: WebinistaWreadItClassic.postId(),
    type: 'attachment'
  });

  makeRequest(
    `/wp-json/wp/v2/media/?${params}`,
    { method: 'GET' },
    WebinistaWreadItClassic.updateViewWithData
  );

	window.addEventListener( 'load', () => {
    const requestAudio = jQuery('[id=wreadit_request_url]');
    const copy2Clipboard = jQuery('[id=wreadit_copy_to_clipboard]');

    requestAudio.on('click', (event) => {

        const fd = new FormData();
        fd.set('post_id', WebinistaWreadItClassic.postId());
        fd.set('_wpnonce', null);

        makeRequest(
          `/wp-json/wreadit/v1/audio?${params}`,
          {
            method: 'POST',
            body: fd
          },
          ( response ) => {
            console.log( response )
          }
        )
    });

    copy2Clipboard.on('click', (event) => {
      let url = '';
      if(jQuery('[id=wreadit_url]').val() ) {
        url = jQuery('[id=wreadit_url]').val();
      }
      writeClipboardText( url )
    });
	});
} )( window, document );
