import "./index.scss";
import {
    TextControl,
    TextareaControl,
    Flex,
    FlexBlock,
    FlexItem,
    Button,
    Icon,
    PanelBody,
    PanelRow,
    ColorPicker
} from "@wordpress/components";
import {registerPlugin} from '@wordpress/plugins';
import {PluginSidebar} from '@wordpress/edit-post';
import {el} from '@wordpress/element';
console.log('meine Sidebar');
const icon = 'chart-area';
(function (wp) {

    const {registerPlugin} = wp.plugins;
    const {PluginSidebar} = wp.editPost;
    // const { TextControl } = wp.components.TextControl;

    /*const MetaBlockField = () => {
        return (
            <TextControl
                label="Test Label"
                value='Meine Test Value'
                onChange={function (content) {
                    console.log('content changed to ', content);
                     }
                }
            />
        );
    };*/

    function MetaBlockField () {

        return (
            <TextControl
                label="Test Label"
                value='Meine Test Value'
                onChange={function (content) {
                    console.log('content changed to ', content);
                }
                }
            />
        );
    }



    registerPlugin('hupa-settings-sidebar', {
        render: function () {
            return (
                <PluginSidebar className="hupa-block-sidebar" name="hupa-theme-settings" title="Hupa Theme Settings" icon={icon}>
                    <div className="hupa-sidebar-content">
                        <PanelBody className="hupa-panel-body" title="Mein Body Titel" initialOpen={false}>
                            <PanelRow>

                             </PanelRow>
                        </PanelBody>
                    </div>
                </PluginSidebar>
            )
        }
    });
})(window.wp);