/**
 * Webinista WreadIt
 *
 * WebinistaWreadItSidebar.jsx: Container component for the Create Audio Version
 * editorsblock.
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

import { __ } from '@wordpress/i18n';
import { useEffect, useState, useRef } from '@wordpress/element';

import { useSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { useCopyToClipboard } from '@uidotdev/usehooks';

import WreadItIcon from './WreadItIcon';
import MakeAudioPanel from './MakeAudioPanel';
import WreaditErrorPanel from './WreaditErrorPanel';

const WebinistaWreadItSidebar = () => {
	const [ apiError, setApiError ] = useState( false );
	const [ isGenerating, setIsGenerating ] = useState( false );
	const [ notGenerated, setNotGenerated ] = useState( true );
	const [ audioUrl, setAudioUrl ] = useState( null );
	const [ audioPostId, setAudioPostId ] = useState( null );
	const [ clipping, copyToClipboard ] = useCopyToClipboard();
	const [ copyIsDisabled, setCopyIsDisabled ] = useState( false );
	const [ deleteIsDisabled, setDeleteIsDisabled ] = useState( true );
	const [ isModalOpen, setIsModalOpen ] = useState( false );
	const [ isConfirmOpen, setIsConfirmOpen ] = useState( false );

	const [ token, setToken ] = useState( null );

	useEffect( () => {
		if ( audioUrl === null ) {
			loadExisting();
		}
	}, [ audioUrl ] );

	const urlRef = useRef( null );

	const postId = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostId(),
		[]
	);

	const loadExisting = () => {
		const requestObj = {
			path: addQueryArgs( '/wreadit/v1/audio', { post_id: postId } ),
		};

		apiFetch( requestObj )
			.then( ( response ) => {
				if ( Object.hasOwn( response, 'token' ) ) {
					setToken( response?.token );
				}

				const { audio } = response;

				if ( audio === false ) {
					setAudioUrl( false );
				} else if ( audio.length ) {
					setAudioUrl( audio[ 0 ].guid );
					setNotGenerated( false );
					setDeleteIsDisabled( false );
					setAudioPostId( audio[ 0 ].ID );
				} else {
					setAudioUrl( '' );
				}
			} )
			.catch( ( error ) => {
				setApiError( { error: true, ...error } );
			} );
	};

	const requestGeneration = () => {
		setIsGenerating( true );

		const requestObj = {
			path: '/wreadit/v1/audio',
			data: {
				post_id: postId,
				_wpnonce: token,
			},
			method: 'POST',
		};

		apiFetch( requestObj ).then( ( response ) => {
			if ( response && Object.hasOwn( response, 'url' ) ) {
				setAudioUrl( response.url );
				setToken( response?.token );
				setNotGenerated( false );
				setIsGenerating( false );
				setDeleteIsDisabled( false );
				setAudioPostId( response?.audio_id );
			} else {
				throw new Error(
					"Something is wrong. The server response didn't include a URL."
				);
			}
		} );
	};

	const copyIt = () => {
		if ( ! Object.hasOwn( urlRef, 'current' ) ) {
			throw new Error(
				"I can't find the text to copy. Save your post and reload the page."
			);
		}
		copyToClipboard( urlRef.current.value );
		setCopyIsDisabled( true );

		// Re-enable the Copy to clipboard button 3 seconds after copying.
		let timeOutId;
		const resetClipboard = () => {
			setCopyIsDisabled( false );
			clearTimeout( timeOutId );
		};

		timeOutId = setTimeout( resetClipboard, 3000 );
	};

	const confirmDeletePrompt = () => {
		setIsConfirmOpen( true );
	};

	const onCancelDelete = () => {
		setIsConfirmOpen( false );
	};

	const deleteIt = () => {
		const requestObj = {
			path: `/wreadit/v1/audio/delete`,
			data: {
				post_id: postId,
				audio_id: audioPostId,
				_wpnonce: token,
			},
			method: 'POST',
		};

		const foo = apiFetch( requestObj ).then( ( response ) => {
			if (
				Object.hasOwn( response, 'success' ) &&
				response?.success === true
			) {
				setIsGenerating( false );
				setNotGenerated( true );
				setAudioUrl( '' );
				setAudioPostId( null );
				setCopyIsDisabled( false );
				setDeleteIsDisabled( true );
				openModal();
			} else {
				throw new Error( 'Could not delete the audio post.' );
			}
		} );
	};

	const openModal = () => {
		setIsModalOpen( true );
	};

	const closeModal = () => {
		setIsModalOpen( false );
	};

	if ( apiError ) {
		return <WreaditErrorPanel { ...apiError } />;
	} else {
		return (
			<MakeAudioPanel
				notGenerated={ notGenerated }
				isGenerating={ isGenerating }
				requestGeneration={ requestGeneration }
				audioUrl={ audioUrl }
				urlRef={ urlRef }
				onCopy={ copyIt }
				copyDisabled={ copyIsDisabled }
				onConfirmDelete={ confirmDeletePrompt }
				deleteDisabled={ deleteIsDisabled }
				onCancelDelete={ onCancelDelete }
				isModalOpen={ isModalOpen }
				onModalClose={ closeModal }
				isConfirmOpen={ isConfirmOpen }
				afterConfirmDelete={ deleteIt }
			/>
		);
	}
};

export default WebinistaWreadItSidebar;
