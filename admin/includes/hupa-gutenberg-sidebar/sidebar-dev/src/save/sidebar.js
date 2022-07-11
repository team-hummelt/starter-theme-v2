import { registerPlugin } from '@wordpress/plugins';
import { PluginSidebar } from '@wordpress/edit-post';
import { el } from '@wordpress/element';

( function ( wp ) {
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginSidebar = wp.editPost.PluginSidebar;
    var el = wp.element.createElement;

    registerPlugin( 'hupa-settings-sidebar', {
        render: function () {
            return el(
                PluginSidebar,
                {
                    name: 'hupa-settings-sidebar',
                    icon:  'media-document',
                    title: 'Hupa Settings',
                },
                'Meta field'
            );
        },
    } );
} )( window.wp );