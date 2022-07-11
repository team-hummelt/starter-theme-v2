//  Import CSS.
import './editor.scss';
import './style.scss';

const {Component} = wp.element;
import {SelectMaps} from './components/googleMaps';
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

registerBlockType('hupa/theme-google-maps', {
    title: __('Google Maps'),
    icon: 'google',
    category: 'media',
    attributes: {
        selectedMap: {
            type: 'string',
        },
        cardWidth: {
            type: 'string',
        },
        cardHeight: {
            type: 'string',
        },


    },
    keywords: [
        __(' Gutenberg TOOLS BY Jens Wiecker'),
        __('Gutenberg Google Maps'),
    ],

    edit: class extends Component {
        constructor(props) {
            super(...arguments);
            this.props = props;

            this.onInputWidthChange = this.onInputWidthChange.bind(this);
            this.onInputHeightChange = this.onInputHeightChange.bind(this);
            this.updateSelectedMap = this.updateSelectedMap.bind(this);
        }

        updateSelectedMap(selectedMap) {
            this.props.setAttributes({selectedMap});
        }

        onInputWidthChange(cardWidth) {
            this.props.setAttributes({cardWidth});
        }

        onInputHeightChange(cardHeight) {
            this.props.setAttributes({cardHeight});
        }

        render() {
            const SmallLine = ({color}) => (
                <hr
                    className="hr-small-trenner"
                />
            );
            const {valInputWidth, attributes: {cardWidth = ''} = {}} = this.props;
            const {valInputHeight, attributes: {cardHeight = ''} = {}} = this.props;
            return (
                <div className="hupa-theme-google-maps">
                    <Panel className="tools-form-panel">
                        <h5 className="hupa-tools-headline">Google Maps </h5>
                        <SmallLine/>
                        <div className="maps-form-wrapper">
                            <TextControl className={valInputWidth}
                                         label="Karten Breite:"
                                         value={cardWidth}
                                         onChange={this.onInputWidthChange}
                                         type="text"

                            />

                            <TextControl className={valInputHeight}
                                         label="Karten Höhe:"
                                         value={cardHeight}
                                         onChange={this.onInputHeightChange}
                                         type="text"

                            />
                        </div>
                            <SmallLine/>
                            <SelectMaps
                                /* TODO JOB select MAPS */
                                selectedMap={this.props.attributes.selectedMap}
                                updateSelectedMap={this.updateSelectedMap}
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