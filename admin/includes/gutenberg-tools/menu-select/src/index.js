//  Import CSS.
import './editor.scss';
import './style.scss';

const {Component} = wp.element;
import {MenuSelect} from './components/menuSelect';
import {
    InspectorControls,
    ColorPaletteControl
} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';
// eslint-disable-next-line no-unused-vars
const {registerBlockType, PlainText} = wp.blocks;
import {
    TextControl,
    ToggleControl,
    PanelBody,
    Panel,
    RadioControl

} from '@wordpress/components';

registerBlockType('hupa/theme-menu-select', {
    title: __('Menu Select'),
    icon: 'menu',
    category: 'media',
    attributes: {
        selectedMenu: {
            type: 'string'
        },
        menuWrapper: {
            type: 'string'
        },
        menuUlClass: {
            type: 'string'
        },
        menuLiClass: {
            type: 'string'
        }
    },
    keywords: [
        __(' Gutenberg TOOLS BY Jens Wiecker'),
        __('Gutenberg Menu Select'),
    ],

    edit: class extends Component {
        constructor(props) {
            super(...arguments);
            this.props = props;

            this.updateSelectedMenu = this.updateSelectedMenu.bind(this);
            this.onInputMenuWrapperChange = this.onInputMenuWrapperChange.bind(this);
            this.onInputMenuUlChange = this.onInputMenuUlChange.bind(this);
            this.onInputMenuLiChange = this.onInputMenuLiChange.bind(this);
        }

        updateSelectedMenu(selectedMenu) {
            this.props.setAttributes({selectedMenu});
        }

        onInputMenuWrapperChange(menuWrapper) {
            this.props.setAttributes({menuWrapper});
        }

        onInputMenuUlChange(menuUlClass) {
            this.props.setAttributes({menuUlClass});
        }

        onInputMenuLiChange(menuLiClass) {
            this.props.setAttributes({menuLiClass});
        }

        render() {
            const SmallLine = ({color}) => (
                <hr
                    className="hr-small-trenner"
                />
            );

            const {valInputWrapper, attributes: {menuWrapper = ''} = {}} = this.props;
            const {valInputUlClass, attributes: {menuUlClass = ''} = {}} = this.props;
            const {valInputLiClass, attributes: {menuLiClass = ''} = {}} = this.props;

            return (
                <div className="hupa-theme-google-maps">
                    <Panel className="tools-form-panel">
                        <h5 className="hupa-menu-select-headline">Theme Menüs</h5>
                        <SmallLine/>
                        <div className="maps-form-wrapper">

                            <TextControl className={valInputWrapper}
                                         label="Menu Container CSS Klasse:"
                                         value={menuWrapper}
                                         onChange={this.onInputMenuWrapperChange}
                                         type="text"
                            />
                        </div>
                        <div className="maps-form-wrapper">
                            <TextControl className={valInputUlClass}
                                         label="Menu UL CSS Klasse:"
                                         value={menuUlClass}
                                         onChange={this.onInputMenuUlChange}
                                         type="text"

                            />
                            <TextControl className={valInputLiClass}
                                         label="Menu LI CSS Klasse:"
                                         value={menuLiClass}
                                         onChange={this.onInputMenuLiChange}
                                         type="text"

                            />
                        </div>

                           <MenuSelect
                                /* TODO JOB select Carousel */
                               selectedMenu={this.props.attributes.selectedMenu}
                                updateSelectedMenu={this.updateSelectedMenu}
                            />
                    </Panel>
                </div>
            );
        }
    },
    save() {
        return null;
    },
});