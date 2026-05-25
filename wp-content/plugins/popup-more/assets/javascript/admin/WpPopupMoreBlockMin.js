'use strict';

function WpPopupMoreBlock() {}

WpPopupMoreBlock.prototype.init = function () {

    if (typeof wp == 'undefined' || typeof wp.element == 'undefined' || typeof wp.blocks == 'undefined' || typeof wp.editor == 'undefined' || typeof wp.components == 'undefined') {
        return false;
    }
    var localizedParams = YPM_GUTENBERG_PARAMS;

    var __ = wp.i18n;
    var createElement = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.editor.InspectorControls;
    var _wp$components = wp.components,
        SelectControl = _wp$components.SelectControl,
        TextareaControl = _wp$components.TextareaControl,
        ToggleControl = _wp$components.ToggleControl,
        PanelBody = _wp$components.PanelBody,
        ServerSideRender = _wp$components.ServerSideRender,
        Placeholder = _wp$components.Placeholder;

    registerBlockType('popupmore/popups', {
        title: localizedParams.title,
        description: localizedParams.description,
        keywords: ['Popup', 'popups', 'popup more'],
        category: 'widgets',
        icon: 'welcome-widgets-menus',
        attributes: {
            popupId: {
                type: 'number'
            },
            popupEvent: {
                type: 'string'
            }
        },
        edit: function edit(props) {
            var _props$attributes = props.attributes,
                _props$attributes$pop = _props$attributes.popupId,
                popupId = _props$attributes$pop === undefined ? '' : _props$attributes$pop,
                _props$attributes$dis = _props$attributes.displayTitle,
                displayTitle = _props$attributes$dis === undefined ? false : _props$attributes$dis,
                _props$attributes$dis2 = _props$attributes.displayDesc,
                displayDesc = _props$attributes$dis2 === undefined ? false : _props$attributes$dis2,
                _props$attributes$pop2 = _props$attributes.popupEvent,
                popupEvent = _props$attributes$pop2 === undefined ? '' : _props$attributes$pop2,
                setAttributes = props.setAttributes;

            const popupOptions = [];
            let allpopups = YPM_GUTENBERG_PARAMS.allpopups;
            let eventsOptions = YPM_GUTENBERG_PARAMS.allEvents.map(function (value) {
                return {
                    value: value.value,
                    label: value.title
                };
            });
            for(var id in allpopups) {
                var currentdownObj = {
                    value: id,
                    label: allpopups[id]
                }
                popupOptions.push(currentdownObj);
            }
            popupOptions.unshift({
                value: '',
                label: YPM_GUTENBERG_PARAMS.popup_select
            })
            var jsx = void 0;

            function selectpopup(value) {
                setAttributes({
                    popupId: parseInt(value)
                });
            }

            function selectEvent(value) {
                setAttributes({
                    popupEvent: value
                });
            }

            function setContent(value) {
                setAttributes({
                    content: value
                });
            }

            function toggleDisplayTitle(value) {
                setAttributes({
                    displayTitle: value
                });
            }

            function toggleDisplayDesc(value) {
                setAttributes({
                    displayDesc: value
                });
            }

            jsx = [React.createElement(
                InspectorControls,
                { key: 'popupMore-gutenberg-form-selector-inspector-controls' },
                React.createElement(
                    PanelBody,
                    { title: 'popup more title' },
                    React.createElement('h2', "Insert Popup More settings"),
                    React.createElement(SelectControl, {
                        label: 'Select popup',
                        value: popupId,
                        options: popupOptions,
                        onChange: selectpopup
                    }),
                    React.createElement(ToggleControl, {
                        label: 'Select popup',
                        checked: displayTitle,
                        onChange: toggleDisplayTitle
                    }),
                    React.createElement(ToggleControl, {
                        label: 'Select popup',
                        checked: displayDesc,
                        onChange: toggleDisplayDesc
                    })
                )
            )];

            if (popupId && popupEvent) {
                var clickText = '';
                var eventText = '';
                if (popupEvent == 'click' || popupEvent == 'hover') {
                    clickText = 'Shortcode text';
                    eventText = 'event="'+popupEvent+'"';
                }
                return '[ypm_popup id="' + popupId + '" '+eventText+']'+clickText+'[/ypm_popup]';
            } else {
                jsx.push(React.createElement(
                    Placeholder,
                    {
                        key: 'ypm-gutenberg-form-selector-wrap',
                        className: 'ycd-gutenberg-form-selector-wrapper' },
                    React.createElement('h5', null, "Insert Popup More settings"),
                    React.createElement(SelectControl, {
                        key: 'ypm-gutenberg-form-selector-select-control',
                        value: popupId,
                        options: popupOptions,
                        onChange: selectpopup
                    }),
                    React.createElement(SelectControl, {
                        key: 'ypm-gutenberg-form-selector-select-control',
                        onChange: selectpopup
                    }),
                    React.createElement(SelectControl, {
                        key: 'ypm-gutenberg-form-selector-select-control',
                        value: popupEvent,
                        options: eventsOptions,
                        onChange: selectEvent
                    })
                ));
            }

            return jsx;
        },
        save: function save(props) {
            console.log(props);
            var clickText = '';
            var eventText = '';
            var popupEvent = props.attributes.popupEvent;
            if (popupEvent == 'click' || popupEvent == 'hover') {
                clickText = 'Shortcode text';
                eventText = 'event="'+popupEvent+'"';
            }
            return '[ypm_popup id="' + props.attributes.popupId + '" '+eventText+']'+clickText+'[/ypm_popup]';
        }
    });
};

jQuery(document).ready(function () {
    var block = new WpPopupMoreBlock();
    block.init();
});