import { registerPlugin } from '@wordpress/plugins';
import { PluginSidebar } from '@wordpress/edit-post';
import { more } from '@wordpress/icons';


( function ( wp ) {
    const registerPlugin = wp.plugins.registerPlugin;
    const PluginSidebar = wp.editPost.PluginSidebar;
    const icon = 'chart-area';

    const Component = () => (
        <>
            <PluginSidebarMoreMenuItem
                target="sidebar-name"
            >
                My Sidebar
            </PluginSidebarMoreMenuItem>
            <PluginSidebar
                name="hupa-settings"
                title="Hupa Theme Settings"
            >
                Hupa Theme Settings
            </PluginSidebar>
        </>
    );
    registerPlugin( 'hupa-settings-sidebar', {
        icon: more,
        render: Component,
        scope: 'my-page',
    });
} )( window.wp );