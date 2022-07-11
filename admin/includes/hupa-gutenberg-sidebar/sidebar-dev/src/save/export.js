const { __ } = wp.i18n;
const { compose } = wp.compose;
const { withSelect, withDispatch } = wp.data;
const { PluginDocumentSettingPanel } = wp.editPost;
const { ToggleControl, TextControl, PanelRow } = wp.components;
import includes from "lodash/includes";

const Hupa_Custom_Sidebar = ( { postType, postMeta, setPostMeta } ) => {
    // if ( 'post' !== postType ) return null;  // Will only render component for post type 'post'

    return(
        <PluginDocumentSettingPanel title={ __( 'My Custom Post meta', 'bootscore') } icon="edit" initialOpen="true">
            <PanelRow>
                <ToggleControl
                    label={ __( 'You can toggle me on or off', 'bootscore' ) }
                    onChange={ ( value ) => setPostMeta( { hupa_show_title: value } ) }
                    checked={ postMeta.hupa_show_title }
                />
            </PanelRow>
            <PanelRow>
                <TextControl
                    label={ __( 'Write some text, if you like', 'bootscore' ) }
                    value={ postMeta.hupa_custom_title }
                    onChange={ ( value ) => setPostMeta( { hupa_custom_title: value } ) }
                />
            </PanelRow>
        </PluginDocumentSettingPanel>
    );
}
export default compose( [
    withSelect( ( select ) => {
        return {
            postMeta: select( 'core/editor' ).getEditedPostAttribute( 'meta' ),
            postType: select( 'core/editor' ).getCurrentPostType(),
        };
    } ),
    withDispatch( ( dispatch ) => {
        return {
            setPostMeta( newMeta ) {
                dispatch( 'core/editor' ).editPost( { meta: newMeta } );
            }
        };
    } )
] )( Hupa_Custom_Sidebar );