/**
 * Gutenberg POST SELECTOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

const {Component} = wp.element;
import axios from 'axios';
import {Panel} from "@wordpress/components";

export class MenuSelect extends Component {
    constructor(props) {
        super(...arguments);
        this.props = props;
        this.state = {
            selectMenu: [],
        }
        this.menuSelectChange = this.menuSelectChange.bind(this);
    }

    componentDidMount() {
        axios.get(hupaRestObj.url + 'get_menu_data', {
            headers: {
                'content-type': 'application/json',
                'X-WP-Nonce': hupaRestObj.nonce
            }
        })
            .then(({data = {}} = {}) => {
                this.setState({
                    selectMenu: data.themeMenu,

                });
            });
    }

    menuSelectChange(e) {
        this.props.updateSelectedMenu(
            this.props.selectedMenu = e
        );
    }

    render() {
        return (
            <div>
                <div className="settings-form-flex-column">
                    <label className="form-label" htmlFor="menuSelect"><b className="b-fett">Menü</b> auswählen: </label>
                    <select className="form-select" name="options" id="menuSelect"
                            onChange={e => this.menuSelectChange(e.target.value)}>
                        <option value=""> auswählen ...</option>
                        {!this.state.selectMenu ? (
                            <option value="">loading</option>) : (this.state.selectMenu).map((select, index) =>
                            <option
                                key={index} value={select.id}
                                selected={select.id == this.props.selectedMenu}>{select.name}</option>)}
                    </select>
                </div>
            </div>
        );
    }
}