import Api from './api.js';
import "./save/index.scss";
import includes from "lodash/includes";

const {registerPlugin} = wp.plugins;
const {__} = wp.i18n;
const {Fragment} = wp.element;
const {PluginSidebarMoreMenuItem, PluginSidebar} = wp.editPost;
const {PanelBody, PanelRow, ToggleControl, TextControl, SelectControl, RadioControl, RadioGroup, Radio} = wp.components;
const {compose} = wp.compose;
const {withDispatch, withSelect, select} = wp.data;

Api.get('get_hupa_post_sidebar', {}).then(function (response) {

    /*=================================================================
    ========================== HEADER SELECT ==========================
    ===================================================================*/
    const selectHeaderOption = response.data.header.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });

    const selectHeader = (props) => {
        return (
            <SelectControl
                label={__('Header auswählen:')}
                onChange={props.changeHeaderSelect}
                options={selectHeaderOption}
                value={props.selectChecked}
            />
        );
    }
    const CustomHeaderMeta = compose([
        withSelect(select => {
            return {selectChecked: select('core/editor').getEditedPostAttribute('meta')['_hupa_select_header']}
        }),
        withDispatch(dispatch => {
            return {
                changeHeaderSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_select_header: value}});
                }
            }
        })
    ])(selectHeader);

    /*=================================================================
    ========================== FOOTER SELECT ==========================
    ===================================================================*/
    const selectFooterOption = response.data.footer.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });
    const selectFooter = (props) => {
        return (
            <SelectControl
                label={__('Footer auswählen:')}
                onChange={props.changeFooterSelect}
                options={selectFooterOption}
                value={props.selectFooterChecked}
            />
        );
    }
    const CustomFooterMeta = compose([
        withSelect(select => {
            return {selectFooterChecked: select('core/editor').getEditedPostAttribute('meta')['_hupa_select_footer']}
        }),
        withDispatch(dispatch => {
            return {
                changeFooterSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_select_footer: value}});
                }
            }
        })
    ])(selectFooter);

    /*====================================================================
    ========================== SHOW TITLE CHECK ==========================
    ======================================================================*/
    const CheckTitleAktivForm = (props) => {
        return (
            <ToggleControl
                label={__('Titel anzeigen', 'bootscore')}
                checked={props.checkTitle}
                onChange={props.changeTitleCheck}
            />
        );
    }

    const CheckTitleAktivMeta = compose([
        withSelect(select => {
            return {checkTitle: select('core/editor').getEditedPostAttribute('meta')['_hupa_show_title']}
        }),
        withDispatch(dispatch => {
            return {
                changeTitleCheck: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_show_title: value}});
                }
            }
        })
    ])(CheckTitleAktivForm);


    /*========================================================================
    ========================== NEW TITLE INPUT TEXT ==========================
    ==========================================================================*/
    const ChangeTitleText = (props) => {
        return (
            <TextControl
                label={__('Titel ändern', 'bootscore')}
                icon='arrow-right-alt'
                value={props.titleTextValue}
                onChange={props.setTitleTextValue}
            />
        );
    }

    const ChangeTitleTextMeta = compose([
        withSelect(select => {
            return {titleTextValue: select('core/editor').getEditedPostAttribute('meta')['_hupa_custom_title']}
        }),
        withDispatch(dispatch => {
            return {
                setTitleTextValue: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_custom_title: value}});
                }
            }
        })
    ])(ChangeTitleText);


    /*==============================================================================
    ========================== TITLE EXTRA CSS INPUT TEXT ==========================
    ================================================================================*/
    const TitelExtraCss = (props) => {
        return (
            <TextControl
                label={__('extra CSS Klasse', 'bootscore')}
                value={props.cssTextValue}
                onChange={props.setCssTextValue}
            />
        );
    }

    const TitelExtraCssMeta = compose([
        withSelect(select => {
            return {cssTextValue: select('core/editor').getEditedPostAttribute('meta')['_hupa_title_css']}
        }),
        withDispatch(dispatch => {
            return {
                setCssTextValue: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_title_css: value}});
                }
            }
        })
    ])(TitelExtraCss);


    /*===================================================================
    ========================== SELECT TOP AREA ==========================
    =====================================================================*/
    const selectTopAreaOption = response.data.topArea.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });
    const selectTopArea = (props) => {
        return (
            <SelectControl
                label={__('Top Area anzeigen:')}
                onChange={props.changeTopAreaSelect}
                options={selectTopAreaOption}
                value={props.selectTopAreaOption}
            />
        );
    }
    const TopAreaMeta = compose([
        withSelect(select => {
            return {selectTopAreaOption: select('core/editor').getEditedPostAttribute('meta')['_hupa_select_top_area']}
        }),
        withDispatch(dispatch => {
            return {
                changeTopAreaSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_select_top_area: value}});
                }
            }
        })
    ])(selectTopArea);

    /*============================================================================
    ========================== SHOW BOTTOM FOOTER CHECK ==========================
    ==============================================================================*/
    const CheckBottomFooter = (props) => {
        return (
            <ToggleControl
                label={__('Bottom Footer anzeigen', 'bootscore')}
                checked={props.checkFooter}
                onChange={props.changeFooterCheck}
            />
        );
    }

    const CheckBottomFooterMeta = compose([
        withSelect(select => {
            return {checkFooter: select('core/editor').getEditedPostAttribute('meta')['_hupa_show_bottom_footer']}
        }),
        withDispatch(dispatch => {
            return {
                changeFooterCheck: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_show_bottom_footer: value}});
                }
            }
        })
    ])(CheckBottomFooter);
    /* ==========================   META FIELDS END   ===================================*/

    const ColoBlackLine = ({color}) => (
        <hr
            className="hr-sidebar"
            style={{
                color: color,
                backgroundColor: color,
                height: 1
            }}
        />
    );

    /**=================================================================
     ========================== RENDER AUSGABE ==========================
     ====================================================================*/
    const HupaSideBarOptions = () => {
        const postType = select("core/editor").getCurrentPostType();
        if (!includes(["post", "page"], postType)) {
            return null;
        }
        return (
            <Fragment>
                <PluginSidebarMoreMenuItem
                    target='hupa-option-sidebar'
                    icon='layout'
                >
                    {__('Hupa Theme options', 'bootscore')}
                </PluginSidebarMoreMenuItem>
                <PluginSidebar
                    name="hupa-option-sidebar"
                    title={__('Hupa Theme Optionen', 'bootscore')}
                    className="hupa-block-sidebar"
                >
                    <PanelBody
                        title={__('Seiten Titel', 'bootscore')}
                        initialOpen={true}
                        className="hupa-sidebar-content"
                    >
                        <PanelRow>
                            <CheckTitleAktivMeta/>
                        </PanelRow>
                        <PanelRow>
                            <ChangeTitleTextMeta/>
                        </PanelRow>
                        <PanelRow>
                            <TitelExtraCssMeta/>
                        </PanelRow>
                    </PanelBody>

                    <PanelBody
                        title={__('Ansicht', 'bootscore')}
                        initialOpen={true}
                        className="hupa-sidebar-content"
                    >
                        <PanelRow
                            className="top-panel"
                        >
                            <TopAreaMeta/>
                        </PanelRow>
                        <PanelRow>
                            <CheckBottomFooterMeta/>
                        </PanelRow>
                    </PanelBody>
                    <PanelBody
                        title={__('Custom Header | Footer', 'bootscore')}
                        initialOpen={true}
                        className="hupa-sidebar-content"
                    >
                        <PanelRow
                            className="top-panel"
                        >
                            <CustomHeaderMeta/>
                        </PanelRow>
                        <PanelRow>
                            <CustomFooterMeta/>
                        </PanelRow>
                    </PanelBody>
                </PluginSidebar>
            </Fragment>
        );
    }

    registerPlugin('hupa-sidebar-options', {
        render: HupaSideBarOptions,
        icon: 'layout'
    });
});