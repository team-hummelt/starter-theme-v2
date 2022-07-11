// don't forget to import the apiFetch()
import apiFetch from '@wordpress/api-fetch';
const { registerPlugin } = wp.plugins;
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { PluginSidebarMoreMenuItem, PluginSidebar } = wp.editPost;
const { PanelBody, PanelRow, ToggleControl, TextControl, SelectControl } = wp.components;
const { compose } = wp.compose;
const { withDispatch, withSelect,select } = wp.data;
import includes from "lodash/includes";
import axios from "axios";
import React from 'react';



export default class PersonList extends React.Component {
    state = {
        header: []
    }

    componentDidMount() {
        axios.get(hupaRestObj.url + 'get_custom_header', {
            credentials: 'include',
            headers: {
                'content-type': 'application/json',
                'X-WP-Nonce': hupaRestObj.nonce
            }
        })
            .then(res => {
                const header = res.data;
                this.setState({ header });

            })
    }

    render() {
        const selectHeader = (props) => {
            return (
                <SelectControl
                    label={__('Custom Header:')}
                    onChange={props.changeHeaderSelect}
                    options={ [
                        { value: null, label: 'Select a User', disabled: true },
                        { value: '1', label: 'User A' },
                        { value: '2', label: 'User B' },
                        { value: '3', label: 'User c' },
                    ] }
                    value={props.selectChecked}
                />
            );
        }
        const CustomHeaderMeta = compose([
            withSelect(select => {
                return { selectChecked: select('core/editor').getEditedPostAttribute('meta')['hupa_select_header'] }
            }),
            withDispatch(dispatch => {
                return {
                    changeHeaderSelect: function(value) {
                        dispatch('core/editor').editPost({ meta: { hupa_select_header: value } });
                    }
                }
            })
        ])(selectHeader);
    }
}
