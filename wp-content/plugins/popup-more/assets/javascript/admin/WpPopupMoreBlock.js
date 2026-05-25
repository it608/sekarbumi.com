function WpPopupMoreBlock() {

}

WpPopupMoreBlock.prototype.init = function() {
    if (typeof wp == 'undefined' || typeof wp.element == 'undefined' || typeof wp.blocks == 'undefined' || typeof wp.editor == 'undefined' || typeof wp.components == 'undefined') {
        return false;
    }
    var localizedParams = YPM_GUTENBERG_PARAMS;

    var __ = wp.i18n;
    var createElement     = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.editor.InspectorControls;
    var _wp$components    = wp.components,
        SelectControl     = _wp$components.SelectControl,
        TextareaControl   = _wp$components.TextareaControl,
        ToggleControl     = _wp$components.ToggleControl,
        PanelBody         = _wp$components.PanelBody,
        ServerSideRender  = _wp$components.ServerSideRender,
        Placeholder       = _wp$components.Placeholder;

    registerBlockType('popupmore/popups', {
        title: localizedParams.title,
        description: localizedParams.description,
        keywords: ['Popup', 'popups', 'popup more'],
        category: 'widgets',
        icon: 'welcome-widgets-menus',
        attributes: {
            popupId: {
                type: 'number'
            }
        },
        edit(props) {
            const {
                attributes: {
                    popupId = '',
                    displayTitle = false,
                    displayDesc = false
                },
                setAttributes
            } = props;

            const popupOptions = [];
            let allpopups = YPM_GUTENBERG_PARAMS.allpopups;
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
            let jsx;

            function selectpopup(value) {
                setAttributes({
                    popupId: value
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

            jsx = [
            <InspectorControls key="popupMore-gutenberg-form-selector-inspector-controls">
                <PanelBody title={'popup more title'}>
                    <SelectControl
                        label = {''}
                        value = {popupId}
                        options = {popupOptions}
                        onChange = {selectpopup}
                            />
                            <ToggleControl
                        label = {YPM_GUTENBERG_PARAMS.i18n.show_title}
                        checked = {displayTitle}
                        onChange = {toggleDisplayTitle}
                            />
                            <ToggleControl
                        label = {YPM_GUTENBERG_PARAMS.i18n.show_description}
                        checked = {displayDesc}
                        onChange = {toggleDisplayDesc}
                    />
                </PanelBody>
                </InspectorControls>
        ];

            if (popupId) {
                return '[ypm_popup id="' + popupId + '" event="'+popupEvent+'"][/ypm_popup]';
            }
            else {
                jsx.push(
                <Placeholder
                key="ycd-gutenberg-form-selector-wrap"
                className="ycd-gutenberg-form-selector-wrapper">
                    <SelectControl
                key = "ycd-gutenberg-form-selector-select-control"
                value = {popupId}
                options = {popupOptions}
                onChange = {selectpopup}
                    />
                    <SelectControl
                key = "ycd-gutenberg-form-selector-select-control"
                onChange = {selectpopup}
                    />
                    </Placeholder>
            );
            }

            return jsx;
        },
        save(props) {

            return '[ypm_popup id="'+props.attributes.popupId+'"][/ypm_popup]';
        }
    });
};

jQuery(document).ready(function () {
    var block = new WpPopupMoreBlock();
    block.init();
});