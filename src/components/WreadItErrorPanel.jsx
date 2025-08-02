/**
 * Webinista WreadIt
 *
 * WreadItErrorPanel.jsx: Loads if the plugin's settings are empty / unset.
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
import WreadItIcon from './WreadItIcon';
import { Icon } from '@wordpress/components';

const WreadItErrorPanel = ( { code, message } ) => {
	/* Message should be HTML returned from the API. */
	return (
		<PluginDocumentSettingPanel
			className="wreadit_panel wreadit_panel--error"
			name="webinista-readit"
			title={ __( 'Create an audio version' ) }
			icon={ <Icon icon={ WreadItIcon } /> }
		>
			<div dangerouslySetInnerHTML={ { __html: message } }></div>
		</PluginDocumentSettingPanel>
	);
};

export default WreadItErrorPanel;
