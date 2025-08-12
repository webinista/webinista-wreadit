/**
 * Webinista WreadIt
 *
 * MakeAudioPanel.jsx: Creates the view for generating an audio file, or displays the
 * file URL and a 'Copy to Clipboard' button if an audio version exists.
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
import { PluginDocumentSettingPanel } from '@wordpress/editor';

import {
	__experimentalConfirmDialog as ConfirmDialog,
	Button,
	Icon,
	TextControl,
	Modal,
} from '@wordpress/components';

import WreadItIcon from './WreadItIcon';

const MakeAudioPanel = ( {
	notGenerated = true,
	isGenerating = false,
	audioUrl = null,
	requestGeneration = null,
	copyDisabled = true,
	onCopy = null,
	deleteDisabled = false,
	onConfirmDelete = null,
	isConfirmOpen = false,
	isModalOpen = false,
	onModalClose = null,
	onCancelDelete = null,
	urlRef = null,
	afterConfirmDelete = null,
} ) => {
	return (
		<>
			<PluginDocumentSettingPanel
				className="wreadit_panel"
				name="webinista-readit"
				title={ __( 'Create an audio version' ) }
				icon={ <Icon icon={ WreadItIcon } /> }
			>
				{ notGenerated && (
					<Button
						variant="primary"
						isBusy={ isGenerating }
						type="submit"
						onClick={ requestGeneration }
					>
						Generate audio version
					</Button>
				) }

				{ ! notGenerated && (
					<>
						<div>
							<TextControl
								__next40pxDefaultSize
								__nextHasNoMarginBottom
								value={ audioUrl }
								readOnly
								type="url"
								id="wreadit_url"
								label="Audio file URL"
								ref={ urlRef }
							/>
						</div>

						<div className="wreadit_buttonrack">
							<Button
								__next40pxDefaultSize
								accessibleWhenDisabled
								variant="primary"
								onClick={ onCopy }
								disabled={ copyDisabled }
							>
								Copy to clipboard
							</Button>

							<Button
								__next40pxDefaultSize
								accessibleWhenDisabled
								isDestructive
								variant="default"
								onClick={ onConfirmDelete }
								disabled={ deleteDisabled }
							>
								Delete
							</Button>
						</div>
					</>
				) }
			</PluginDocumentSettingPanel>
			{ isModalOpen && (
				<Modal
					bodyOpenClassName="wreadit_audio_deleted_modal_open"
					focusOnMount="firstElement"
					size="medium"
					title="Success: Audio deleted"
					onRequestClose={ onModalClose }
					focusOnMount
				>
					<p>I've deleted the previous audio version of this post.</p>
					<Button variant="primary" onClick={ onModalClose }>
						Close
					</Button>
				</Modal>
			) }

			<ConfirmDialog
				className="wreadit_confirm_delete"
				confirmButtonText="Yes"
				isOpen={ isConfirmOpen }
				onConfirm={ afterConfirmDelete }
				onCancel={ onCancelDelete }
			>
				<h1 className="components-modal__header-heading">Confirm</h1>
				<p>
					Are you sure you want to delete the audio version of this
					post? <strong>You can't undo this action.</strong>
				</p>
			</ConfirmDialog>
		</>
	);
};

export default MakeAudioPanel;
