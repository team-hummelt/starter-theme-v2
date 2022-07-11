//  Import CSS.
import './editor.scss';
import './style.scss';

const {Component} = wp.element;
import {ThemeCarousel} from './components/themeCarousel';
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

registerBlockType('hupa/theme-carousel', {
    title: __('Carousel'),
    icon: 'cover-image',
    category: 'media',
    attributes: {
        selectedCarousel: {
            type: 'string'
        }
    },
    keywords: [
        __(' Gutenberg TOOLS BY Jens Wiecker'),
        __('Gutenberg Theme Carousel'),
    ],

    edit: class extends Component {
        constructor(props) {
            super(...arguments);
            this.props = props;

            this.updateSelectedCarousel = this.updateSelectedCarousel.bind(this);
        }

        updateSelectedCarousel(selectedCarousel) {
            this.props.setAttributes({selectedCarousel});
        }

        render() {
            const SmallLine = ({color}) => (
                <hr
                    className="hr-small-trenner"
                />
            );

            return (
                <div className="hupa-theme-carousel">
                    <Panel className="tools-form-panel">
                        <h5 className="hupa-tools-headline">Theme Carousel</h5>
                        <SmallLine/>
                           <ThemeCarousel
                                /* TODO JOB select Carousel */
                                selectedCarousel={this.props.attributes.selectedCarousel}
                                updateSelectedCarousel={this.updateSelectedCarousel}
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