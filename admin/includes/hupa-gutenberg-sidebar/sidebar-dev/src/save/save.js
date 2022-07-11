import "./index.scss";
import {Panel, PanelBody, PanelRow, ToggleControl} from '@wordpress/components';
import { dispatch, select } from '@wordpress/data';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
const icon = 'layout';

( function( wp ) {
    const { registerPlugin } = wp.plugins;
    const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
    const { Fragment } = wp.element;

    registerPlugin( 'hupa-sidebar-options', {
        render: function(){
            return (
                <Fragment>
                    <PluginSidebarMoreMenuItem target="hupa-settings-sidebar" icon="layout">
                        Hupa Page Optionen
                    </PluginSidebarMoreMenuItem>
                    <PluginSidebar
                        name="hupa-settings-sidebar"
                        title={__('Hupa Theme Settings', 'bootscore')}
                        icon={ icon } >
                        <div className="hupa-sidebar-content">
                            <PanelBody
                                className="hupa-panel-body"
                                title="Select Header | Footer"
                                initialOpen={false} >
                                <PanelRow>
                                </PanelRow>
                            </PanelBody>
                        </div>
                    </PluginSidebar>
                </Fragment>
            )
        }
    } );

} )( window.wp );