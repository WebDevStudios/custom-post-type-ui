import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { useSelect } from '@wordpress/data';
import { useState } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

const CPTUIProPanel = () => {
	const postType = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostType(),
		[]
	);

	const [ dismissed, setDismissed ] = useState( false );
	const [ dismissing, setDismissing ] = useState( false );

	const config = window.cptuiProPanel || {};
	const allowedTypes = config.postTypes || [];

	if ( dismissed || ! postType || ! allowedTypes.includes( postType ) ) {
		return null;
	}

	const handleDismiss = () => {
		setDismissing( true );
		apiFetch( {
			path: '/cptui/v1/dismiss-pro-upsell',
			method: 'POST',
		} )
			.then( () => {
				setDismissed( true );
			} )
			.catch( () => {
				setDismissing( false );
			} );
	};

	return (
		<PluginDocumentSettingPanel
			name="cptui-pro-callout"
			title={ __( 'Display with CPT UI Pro', 'custom-post-type-ui' ) }
			className="cptui-pro-panel"
		>
			<p style={ { marginTop: 0 } }>
				{ __(
					'CPT UI Pro adds a dedicated Gutenberg block for displaying this content anywhere on your site — pull and render this post type inside any block-editor post or page, no code required.',
					'custom-post-type-ui'
				) }
			</p>
			<div style={ { display: 'flex', alignItems: 'center', gap: '12px', flexWrap: 'wrap' } }>
				<Button
					variant="primary"
					href={ config.proUrl }
					target="_blank"
					rel="noopener noreferrer"
				>
					{ __( 'Get CPT UI Pro', 'custom-post-type-ui' ) }
				</Button>
				<Button
					variant="link"
					onClick={ handleDismiss }
					disabled={ dismissing }
				>
					{ __( 'Dismiss', 'custom-post-type-ui' ) }
				</Button>
			</div>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( 'cptui-pro-panel', {
	render: CPTUIProPanel,
} );
