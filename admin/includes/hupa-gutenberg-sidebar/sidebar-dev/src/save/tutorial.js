import Api from './api.js';
import "./save/index.scss";
import includes from "lodash/includes";
import {PanelBody, TextControl} from "@wordpress/components";
import {PluginSidebar, PluginSidebarMoreMenuItem} from "@wordpress/edit-post";
import {registerPlugin} from "@wordpress/plugins";
import { withSelect, withDispatch  } from "@wordpress/data";
import {__} from "@wordpress/i18n";


let PluginMetaFields = (props) => {
    return (
        <>
            <PanelBody
                title={__('Seiten Titel', 'bootscore')}
                initialOpen={true}
                icon="editor-paste-text"
                className="hupa-sidebar-content"
            >
                <TextControl
                    value={props.text_metafield}
                    label={__('Titel ändern', 'bootscore')}
                    onChange={(value) => props.onMetaFieldChange(value)}
                />
            </PanelBody>
        </>
    )
}

PluginMetaFields = withSelect(
    (select) => {
        return {
            text_metafield: select('core/editor').getEditedPostAttribute('meta')['_hupa_custom_title']
        }
    }
)(PluginMetaFields);

PluginMetaFields = withDispatch(
    (dispatch) => {
        return {
            onMetaFieldChange: (value) => {
                dispatch('core/editor').editPost({meta: {_hupa_custom_title: value}})
            }
        }
    }
)(PluginMetaFields);


registerPlugin('hupa-sidebar-options', {
    icon: 'layout',
    render: () => {
        return (
            <>
                <PluginSidebarMoreMenuItem
                    target='hupa-option-sidebar'
                    icon='layout'
                >
                    {__('Hupa Theme options', 'bootscore')}
                </PluginSidebarMoreMenuItem>
                <PluginSidebar
                    name="hupa-option-sidebar"
                    title={__('Hupa Theme Optionen', 'bootscore')}
                    className="hupa-block-sidebar"
                >

                    <PluginMetaFields/>
                </PluginSidebar>
            </>
        )
    }
})