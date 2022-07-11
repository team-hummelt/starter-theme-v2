import Api from './api.js';
import includes from "lodash/includes";
import {Flex, FlexBlock, FlexItem, RadioControl} from "@wordpress/components";
import label from "@wordpress/components/build/resizable-box/resize-tooltip/label";

const {registerPlugin} = wp.plugins;
const {__} = wp.i18n;
const {Fragment} = wp.element;
const {PluginSidebarMoreMenuItem, PluginSidebar} = wp.editPost;
const {PanelBody, PanelRow, ToggleControl, TextControl, SelectControl} = wp.components;
const {compose} = wp.compose;
const {withDispatch, withSelect, select} = wp.data;

Api.get('get_hupa_post_sidebar', {}).then(function (response) {

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

    /*============================================================================
    ========================== SHOW MENU CHECK ==========================
    ==============================================================================*/
    const CheckShowMenu = (props) => {
        return (
            <ToggleControl
                label={__('Menu anzeigen', 'bootscore')}
                checked={props.checkMenu}
                onChange={props.changeMenuCheck}
            />
        );
    }

    const CheckShowMenuMeta = compose([
        withSelect(select => {
            return {checkMenu: select('core/editor').getEditedPostAttribute('meta')['_hupa_show_menu']}
        }),
        withDispatch(dispatch => {
            return {
                changeMenuCheck: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_show_menu: value}});
                }
            }
        })
    ])(CheckShowMenu);

    /*====================================================================
    ========================== MAIN MENU SELECT ==========================
    ======================================================================*/
    /*const selectMainMenuOption = response.data.menuSelect.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });
    const selectMainMenu = (props) => {
        return (
            <SelectControl
                label={__('Hauptmenü auswählen:')}
                onChange={props.changeMainMenuSelect}
                options={selectMainMenuOption}
                value={props.selectMainMenu}
            />
        );
    }
    const SelectMainMenuMeta = compose([
        withSelect(select => {
            return {selectMainMenu: select('core/editor').getEditedPostAttribute('meta')['_hupa_select_menu']}
        }),
        withDispatch(dispatch => {
            return {
                changeMainMenuSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_select_menu: value}});
                }
            }
        })
    ])(selectMainMenu);
    */
    /*====================================================================
    ========================== HANDY MENU SELECT ==========================
    ======================================================================*/
   /* const selectHandyMenuOption = response.data.handyMenuSelect.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });
    const selectHandyMenu = (props) => {
        return (
            <SelectControl
                label={__('Handymenü auswählen:')}
                onChange={props.changeHandyMenuSelect}
                options={selectHandyMenuOption}
                value={props.selectHandyMenu}
            />
        );
    }
    const SelectHandyMenuMeta = compose([
        withSelect(select => {
            return {selectHandyMenu: select('core/editor').getEditedPostAttribute('meta')['_hupa_select_handy_menu']}
        }),
        withDispatch(dispatch => {
            return {
                changeHandyMenuSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_select_handy_menu: value}});
                }
            }
        })
    ])(selectHandyMenu);
    */
    /*==================================================================
    ========================== SELECT SIDEBAR ==========================
    ====================================================================*/
    const selectSidebarOption = response.data.selectSidebars.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });
    const selectSidebar = (props) => {
        return (
            <SelectControl
                label={__('Sidebar auswählen:')}
                onChange={props.changeSidebarSelect}
                options={selectSidebarOption}
                value={props.selectSidebarOption}
            />
        );
    }
    const SidebarSelectMeta = compose([
        withSelect(select => {
            return {selectSidebarOption: select('core/editor').getEditedPostAttribute('meta')['_hupa_select_sidebar']}
        }),
        withDispatch(dispatch => {
            return {
                changeSidebarSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_select_sidebar: value}});
                }
            }
        })
    ])(selectSidebar);


    /*==============================================================================
    ========================== SELECT SOCIAL AUSGABE TYPE ==========================
    ================================================================================*/
    const selectSocialTypeOption = response.data.selectSocialType.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });
    const selectSocialType = (props) => {
        return (
            <SelectControl
                label={__('Ausgabe Option:')}
                onChange={props.changeSelectSocialType}
                options={selectSocialTypeOption}
                value={props.selectSocialTypeOption}
            />
        );
    }
    const SelectSocialTypeMeta = compose([
        withSelect(select => {
            return {selectSocialTypeOption: select('core/editor').getEditedPostAttribute('meta')['_hupa_select_social_type']}
        }),
        withDispatch(dispatch => {
            return {
                changeSelectSocialType: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_select_social_type: value}});
                }
            }
        })
    ])(selectSocialType);

    /*============================================================================
    ========================== SOCIAL COLOR CHECK ==========================
    ==============================================================================*/
    const selectSocialColorOption = response.data.selectSocialColor.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });

    const selectSocialColor = (props) => {
        return (
            <SelectControl
                label={__('Farbige Symbole anzeigen:')}
                onChange={props.changeSelectColor}
                options={selectSocialColorOption}
                value={props.selectSocialColorOption}
                help="Nur aktiv wenn Ausgabe Option Symbole gewählt ist."
            />
        );
    }
    const SelectSocialColorMeta = compose([
        withSelect(select => {
            return {selectSocialColorOption: select('core/editor').getEditedPostAttribute('meta')['_hupa_select_social_color']}
        }),
        withDispatch(dispatch => {
            return {
                changeSelectColor: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_select_social_color: value}});
                }
            }
        })
    ])(selectSocialColor);

    /*===========================================================================
    ========================== SOZIAL MEDIA EXTRA CSS  ==========================
    =============================================================================*/
    const SocialMediaExtraCss = (props) => {
        return (
            <TextControl
                label={__('extra CSS Klasse', 'bootscore')}
                value={props.socialCssTextValue}
                onChange={props.setCssSocialMediaValue}
            />
        );
    }

    const SocialMediaExtraCssMeta = compose([
        withSelect(select => {
            return {socialCssTextValue: select('core/editor').getEditedPostAttribute('meta')['_hupa_social_media_css']}
        }),
        withDispatch(dispatch => {
            return {
                setCssSocialMediaValue: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_social_media_css: value}});
                }
            }
        })
    ])(SocialMediaExtraCss);

    /*===================================================================
    ========================== SELECT TOP AREA ==========================
    =====================================================================*/
    const selectTopAreaOption = response.data.showTopAreaSelect.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });
    const selectTopArea = (props) => {
        return (
            <SelectControl
                label={__('Top Area Menu anzeigen:')}
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


    /*=======================================================================
    ==========================Top Area Container Width ==========================
    =========================================================================*/
    const selectTopAreaContainer = response.data.selectConatinerTopArea.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });

    const selectedTopAreaContainer = (props) => {
        return (
            <SelectControl
                label={__('Top Area Menu Container:')}
                onChange={props.changeTopContainerSelect}
                options={selectTopAreaContainer}
                value={props.selectTopContainerOption}
            />
        );
    }
    const TopContainerMeta = compose([
        withSelect(select => {
            return {selectTopContainerOption: select('core/editor').getEditedPostAttribute('meta')['_hupa_top_area_container']}
        }),
        withDispatch(dispatch => {
            return {
                changeTopContainerSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_top_area_container: value}});
                }
            }
        })
    ])(selectedTopAreaContainer)


    /*=======================================================================
     ==========================Top Area Container Width ==========================
     =========================================================================*/
    const selectMainContainer = response.data.selectMainContainer.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });

    const selectedMainContainer = (props) => {
        return (
            <SelectControl
                label={__('Main Container:')}
                onChange={props.changeMainContainerSelect}
                options={selectMainContainer}
                value={props.selectMainContainerOption}
            />
        );
    }
    const MainContainerMeta = compose([
        withSelect(select => {
            return {selectMainContainerOption: select('core/editor').getEditedPostAttribute('meta')['_hupa_main_container']}
        }),
        withDispatch(dispatch => {
            return {
                changeMainContainerSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_main_container: value}});
                }
            }
        })
    ])(selectedMainContainer)

    /*=======================================================================
    ==========================MENU Container Width ==========================
    =========================================================================*/
    const selectContainerOption = response.data.selectMenuContainer.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });
    const selectContainer = (props) => {
        return (
            <SelectControl
                label={__('Menu Container:')}
                onChange={props.changeContainerSelect}
                options={selectContainerOption}
                value={props.selectContainerOption}
            />
        );
    }
    const ContainerMeta = compose([
        withSelect(select => {
            return {selectContainerOption: select('core/editor').getEditedPostAttribute('meta')['_hupa_select_container']}
        }),
        withDispatch(dispatch => {
            return {
                changeContainerSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_select_container: value}});
                }
            }
        })
    ])(selectContainer);

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


    /*============================================================================
    ========================== SHOW BOTTOM FOOTER CHECK ==========================
    ==============================================================================*/
    const CheckTopFooter = (props) => {
        return (
            <ToggleControl
                label={__('Top Footer Widget anzeigen', 'bootscore')}
                checked={props.checkTopFooter}
                onChange={props.changeTopFooterCheck}
            />
        );
    }

    const CheckTopFooterMeta = compose([
        withSelect(select => {
            return {checkTopFooter: select('core/editor').getEditedPostAttribute('meta')['_hupa_show_top_footer']}
        }),
        withDispatch(dispatch => {
            return {
                changeTopFooterCheck: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_show_top_footer: value}});
                }
            }
        })
    ])(CheckTopFooter);

    /*============================================================================
    ========================== SHOW BOTTOM FOOTER CHECK ==========================
    ==============================================================================*/
    const CheckWidgetsFooter = (props) => {
        return (
            <ToggleControl
                label={__('Footer Widget anzeigen', 'bootscore')}
                checked={props.checkWidgetsFooter}
                onChange={props.changeWidgetsFooterCheck}
            />
        );
    }

    const CheckWidgetsFooterMeta = compose([
        withSelect(select => {
            return {checkWidgetsFooter: select('core/editor').getEditedPostAttribute('meta')['_hupa_show_widgets_footer']}
        }),
        withDispatch(dispatch => {
            return {
                changeWidgetsFooterCheck: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_show_widgets_footer: value}});
                }
            }
        })
    ])(CheckWidgetsFooter);

    /*============================================================================
    ========================== SHOW BOTTOM FOOTER CHECK ==========================
    ==============================================================================*/
    const CheckSozialMedia = (props) => {
        return (
            <ToggleControl
                label={__('' +
                    'Soziale Medien anzeigen', 'bootscore')}
                checked={props.checkSocialMedia}
                onChange={props.changeSocialMediaCheck}
            />
        );
    }

    const CheckSozialMediaMeta = compose([
        withSelect(select => {
            return {checkSocialMedia: select('core/editor').getEditedPostAttribute('meta')['_hupa_show_social_media']}
        }),
        withDispatch(dispatch => {
            return {
                changeSocialMediaCheck: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_show_social_media: value}});
                }
            }
        })
    ])(CheckSozialMedia);

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


    /*=============================================================================
    ========================== SHOW BOTTOM FOOTER SELECT ==========================
    ===============================================================================*/
    const selectStickyFooterOption = response.data.showStickyFooterSelect.map((select, index) => {
        return {
            label: select.label,
            value: select.value,
            key: index
        }
    });

    const selectStickyFooter = (props) => {
        return (
            <SelectControl
                label={__('Bottom Footer Sticky:')}
                onChange={props.changeStickyFooterSelect}
                options={selectStickyFooterOption}
                value={props.selectStickyFooter}
            />
        );
    }
    const SelectStickyFooterMeta = compose([
        withSelect(select => {
            return {selectStickyFooter: select('core/editor').getEditedPostAttribute('meta')['_hupa_sticky_widgets_footer']}
        }),
        withDispatch(dispatch => {
            return {
                changeStickyFooterSelect: function (value) {
                    dispatch('core/editor').editPost({meta: {_hupa_sticky_widgets_footer: value}});
                }
            }
        })
    ])(selectStickyFooter);
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

    /* ==========================   META FIELDS END   ===================================*/

    const ColoBlackLine = ({color}) => (
        <hr
            className="hr-sidebar-top"
            style={{
                color: color,
                backgroundColor: color,
                height: 1
            }}
        />
    );

    /**==================================================================
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
                    //icon='layout'
                >
                    {__('Hupa Theme Optionen', 'bootscore')}
                </PluginSidebarMoreMenuItem>
                <PluginSidebar
                    name="hupa-option-sidebar"
                    title={__('Hupa Theme Optionen', 'bootscore')}
                    className="hupa-block-sidebar"
                >
                    <PanelBody
                        title={__('Seiten Titel', 'bootscore')}
                        initialOpen={false}
                        //icon="edit-page"
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
                        initialOpen={false}
                        //icon="text-page"
                        className="hupa-sidebar-content"
                    >
                        <PanelRow
                            className="top-panel"
                        >
                            <PanelRow className="panel-row-column">
                                <p>
                                    <CheckShowMenuMeta/>
                                </p>
                                <PanelRow>
                                    <SidebarSelectMeta/>
                                </PanelRow>
                                <PanelRow>
                                    <TopAreaMeta/>
                                </PanelRow>
                                <p></p>
                            </PanelRow>
                        </PanelRow>

                        <span className="row-column-title font-weight">Seitenbreite | Container:</span>
                        <PanelRow>
                            <TopContainerMeta/>
                        </PanelRow>
                        <PanelRow>
                            <ContainerMeta/>
                        </PanelRow>
                        <PanelRow>
                            <MainContainerMeta/>
                        </PanelRow>
                    </PanelBody>

                    <PanelBody
                        title={__('Soziale Medien', 'bootscore')}
                        initialOpen={false}
                        //icon="schedule"
                        className="hupa-sidebar-content"
                    >
                        <PanelRow
                            className="top-panel"
                        >
                            <PanelRow className="panel-row-column">
                                <p>
                                    <CheckSozialMediaMeta/>
                                </p>
                                <p>
                                    <SelectSocialTypeMeta/>
                                </p>
                                <PanelRow>
                                    <SelectSocialColorMeta/>
                                </PanelRow>
                                <span>
                                 <SocialMediaExtraCssMeta/>
                                </span>
                                <p></p>
                            </PanelRow>

                        </PanelRow>

                    </PanelBody>

                    <PanelBody
                        title={__('Footer', 'bootscore')}
                        initialOpen={false}
                        //icon="schedule"
                        className="hupa-sidebar-content"
                    >
                        <PanelRow
                            className="top-panel"
                        >
                            <PanelRow className="panel-row-column">
                                <p>
                                    <CheckTopFooterMeta/>
                                </p>
                                <p>
                                    <CheckWidgetsFooterMeta/>
                                </p>
                                <p>
                                    <CheckBottomFooterMeta/>
                                </p>
                                <p>
                                    <SelectStickyFooterMeta/>
                                </p>

                            </PanelRow>
                        </PanelRow>

                    </PanelBody>
                    <PanelBody
                        title={__('Custom Header | Footer', 'bootscore')}
                        initialOpen={false}
                        //icon="schedule"
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

                        <PanelRow></PanelRow>
                    </PanelBody>
                </PluginSidebar>
            </Fragment>
        );
    }
    registerPlugin('hupa-sidebar-options', {
        render: HupaSideBarOptions,
        className: 'hupa-sidebar',
        icon: 'hupaIcon'
    });
});