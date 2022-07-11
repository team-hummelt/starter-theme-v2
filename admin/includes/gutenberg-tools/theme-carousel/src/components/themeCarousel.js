/**
 * Gutenberg POST SELECTOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

const {Component} = wp.element;
import axios from 'axios';
import {Panel} from "@wordpress/components";

export class ThemeCarousel extends Component {
    constructor(props) {
        super(...arguments);
        this.props = props;
        this.state = {
            selectCarousel: [],
        }
        this.carouselSelectChange = this.carouselSelectChange.bind(this);
    }

    componentDidMount() {
        axios.get(hupaRestObj.url + 'get_carousel_data', {
            headers: {
                'content-type': 'application/json',
                'X-WP-Nonce': hupaRestObj.nonce
            }
        })
            .then(({data = {}} = {}) => {
                this.setState({
                    selectCarousel: data.themeCarousel,

                });
            });
    }

    carouselSelectChange(e) {
        this.props.updateSelectedCarousel(
            this.props.selectedCarousel = e
        );
    }

    render() {
        return (
            <div>
                <div className="settings-form-flex-column">
                    <label className="form-label" htmlFor="CarouselSelect"><b className="b-fett">Carousel</b> auswählen: </label>
                    <select className="form-select" name="options" id="CarouselSelect"
                            onChange={e => this.carouselSelectChange(e.target.value)}>
                        <option value=""> auswählen ...</option>
                        {!this.state.selectCarousel ? (
                            <option value="">loading</option>) : (this.state.selectCarousel).map((select, index) =>
                            <option
                                key={index} value={select.id}
                                selected={select.id == this.props.selectedCarousel}>{select.name}</option>)}
                    </select>
                </div>
            </div>
        );
    }
}