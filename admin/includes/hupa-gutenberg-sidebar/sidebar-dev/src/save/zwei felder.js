import "./index.scss";
const { registerPlugin } = wp.plugins;
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { PluginSidebarMoreMenuItem, PluginSidebar } = wp.editPost;
const { PanelBody, PanelRow, ToggleControl, TextControl } = wp.components;
const { compose } = wp.compose;
const { withDispatch, withSelect } = wp.data;

const axios = require('axios').default;

const CustomSidebarMetaComponent = (props) => {
    return(
        <ToggleControl
            label={__('Titel anzeigen', 'bootscore')}
            checked={props.customPostMetaValue}
            onChange={props.setCustomPostMeta}
        />
    );
}

const CustomSidebarMeta = compose([
    withSelect(select => {
        return { customPostMetaValue: select('core/editor').getEditedPostAttribute('meta')['hupa_show_title'] }
    }),
    withDispatch(dispatch => {
        return {
            setCustomPostMeta: function(value) {
                dispatch('core/editor').editPost({ meta: { hupa_show_title: value } });
            }
        }
    })
])(CustomSidebarMetaComponent);

/* ==================  */
const CustomSidebarMetaTextComponent = (props) => {
    return(
        <TextControl
            label={__('Titel anzeigen', 'bootscore')}
            value={props.customPostMetaTextValue}
            onChange={props.setCustomPostTextMeta}
        />
    );
}

const CustomSidebarTextMeta = compose([
    withSelect(select => {
        return { customPostMetaTextValue: select('core/editor').getEditedPostAttribute('meta')['hupa_custom_title'] }
    }),
    withDispatch(dispatch => {
        return {
            setCustomPostTextMeta: function(value) {
                dispatch('core/editor').editPost({ meta: { hupa_custom_title: value } });
            }
        }
    })
])(CustomSidebarMetaTextComponent);
/*===================== */

const CustomSidebarComponent = () => {
    return(
        <Fragment>
            <PluginSidebarMoreMenuItem
                target='hupa-option-sidebar'
                icon='layout'
            >{__('Hupa Theme options', 'bootscore')}</PluginSidebarMoreMenuItem>
            <PluginSidebar
                name="hupa-option-sidebar"
                title={__('Hupa Theme options', 'bootscore')}
                className="hupa-block-sidebar"
            >
                <PanelBody
                    title={__('This is a panel section', 'bootscore')}
                    initialOpen={true}
                    className="hupa-sidebar-content"
                >
                    <PanelRow>
                        <CustomSidebarMeta />
                    </PanelRow>
                </PanelBody>
                <PanelBody
                    title={__('Another section', 'bootscore')}
                    initialOpen={false}
                    className="hupa-sidebar-content"
                >
                    <PanelRow>
                        <CustomSidebarTextMeta />
                    </PanelRow>
                </PanelBody>
            </PluginSidebar>
        </Fragment>
    );
}

registerPlugin('hupa-sidebar-options', {
    render: CustomSidebarComponent,
    icon: 'layout'
});