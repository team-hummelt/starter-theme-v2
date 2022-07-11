/**
 * Gutenberg POST SELECTOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

const {Component} = wp.element;
import axios from 'axios';
import {Panel} from "@wordpress/components";

export class SelectMaps extends Component {
    constructor(props) {
        super(...arguments);
        this.props = props;
        this.state = {
            selectGmaps: [],
        }
        this.mapsSelectChange = this.mapsSelectChange.bind(this);
    }

    componentDidMount() {
        axios.get(hupaRestObj.url + 'get_gmaps_data', {
            headers: {
                'content-type': 'application/json',
                'X-WP-Nonce': hupaRestObj.nonce
            }
        })
            .then(({data = {}} = {}) => {
                this.setState({
                    selectGmaps: data.maps,

                });
            });
    }

    mapsSelectChange(e) {
        this.props.updateSelectedMap(
            this.props.selectedMap = e
        );
    }

    render() {


        return (
            <div>
                <div className="settings-form-flex-column">
                    <label className="form-label" htmlFor="MapSelect"><b className="b-fett">Galerie</b> auswählen: </label>
                    <select className="form-select" name="options" id="MapSelect"
                            onChange={e => this.mapsSelectChange(e.target.value)}>
                        <option value=""> auswählen ...</option>
                        {!this.state.selectGmaps ? (
                            <option value="">loading</option>) : (this.state.selectGmaps).map((select, index) =>
                            <option
                                key={index} value={select.id}
                                selected={select.id == this.props.selectedMap}>{select.name}</option>)}
                    </select>
                </div>
            </div>
        );
    }
}