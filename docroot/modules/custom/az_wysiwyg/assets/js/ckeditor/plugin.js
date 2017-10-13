/**
 * A CKeditor plugin to insert az_wysiwyg as in-place <fn> elements (consumed by Footnotes module in Drupal).
 *
 * This is a rather sophisticated plugin to show a dialog to insert
 * <fn> az_wysiwyg or edit existing ones. It produces and understands
 * the <fn>angle bracket</fn> variant and uses the fakeObjects API to
 * show a nice icon to the user, while producing proper <fn> tags when
 * the text is saved or View Source is pressed.
 *
 * If a text contains az_wysiwyg of the [fn]square bracket[/fn] variant,
 * they will be visible in the text and this plugin will not react to them.
 *
 * This plugin uses Drupal.t() to translate strings and will not as such
 * work outside of Drupal. (But removing those functions would be the only
 * change needed.) While being part of a Wysiwyg compatible module, it could
 * also be used together with the CKEditor module.
 *
 * Drupal Wysiwyg requirement: The first argument to CKEDITOR.plugins.add()
 * must be equal to the key used in $plugins[] in hook_wysiwyg_plugin().
 */
(function() {
    CKEDITOR.plugins.add( 'az_wysiwyg',
        {
            requires : [ 'fakeobjects','dialog' ],
            icons: 'az_wysiwyg',
            onLoad: function() {
                CKEDITOR.addCss(
                    '.cke_footnote' +
                    '{' +
                    'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/fn_icon2.png' ) + ');' +
                    'background-position: center center;' +
                    'background-repeat: no-repeat;' +
                    'width: 16px;' +
                    'height: 16px;' +
                    '}'
                );
            },
            init: function( editor )
            {
                editor.addCommand('createaz_wysiwyg', new CKEDITOR.dialogCommand('createaz_wysiwyg', {
                    allowedContent: 'fn[value]'
                }));
                editor.addCommand('editaz_wysiwyg', new CKEDITOR.dialogCommand('editaz_wysiwyg', {
                    allowedContent: 'fn[value]'
                }));

                // Drupal Wysiwyg requirement: The first argument to editor.ui.addButton()
                // must be equal to the key used in $plugins[<pluginName>]['buttons'][<key>]
                // in hook_wysiwyg_plugin().
                editor.ui.addButton && editor.ui.addButton( 'az_wysiwyg', {
                    label: Drupal.t('Add a footnote'),
                    command: 'createaz_wysiwyg',
                    icon: 'az_wysiwyg'
                });

                if (editor.addMenuItems) {
                    editor.addMenuGroup('az_wysiwyg', 100);
                    editor.addMenuItems({
                        az_wysiwyg: {
                            label: Drupal.t('Edit footnote'),
                            command: 'editaz_wysiwyg',
                            icon: 'az_wysiwyg',
                            group: 'az_wysiwyg'
                        }
                    });
                }
                if (editor.contextMenu) {
                    editor.contextMenu.addListener( function( element, selection ) {
                        if ( !element || element.data('cke-real-element-type') != 'fn' )
                            return null;

                        return { az_wysiwyg: CKEDITOR.TRISTATE_ON };
                    });
                }

                editor.on( 'doubleclick', function( evt ) {
                    if ( CKEDITOR.plugins.az_wysiwyg.getSelectedFootnote( editor ) )
                        evt.data.dialog = 'editaz_wysiwyg';
                });

                CKEDITOR.dialog.add( 'createaz_wysiwyg', this.path + 'dialogs/az_wysiwyg.js' );
                CKEDITOR.dialog.add( 'editaz_wysiwyg', this.path + 'dialogs/az_wysiwyg.js' );
            },
            afterInit : function( editor ) {
                var dataProcessor = editor.dataProcessor,
                    dataFilter = dataProcessor && dataProcessor.dataFilter;

                if (dataFilter) {
                    dataFilter.addRules({
                        elements: {
                            fn: function(element ) {
                                return editor.createFakeParserElement( element, 'cke_footnote', 'fn', false );
                            }
                        }
                    });
                }
            }
        });
})();

CKEDITOR.plugins.az_wysiwyg = {
    createFootnote: function( editor, origElement, text, value) {
        if (!origElement) {
            var realElement = CKEDITOR.dom.element.createFromHtml('<fn></fn>');
        }
        else {
            realElement = origElement;
        }

        if (text && text.length > 0 )
            realElement.setText(text);
        if (value && value.length > 0 )
            realElement.setAttribute('value',value);

        var fakeElement = editor.createFakeElement( realElement , 'cke_footnote', 'fn', false );
        editor.insertElement(fakeElement);
    },

    getSelectedFootnote: function( editor ) {
        var selection = editor.getSelection();
        var element = selection.getSelectedElement();
        var seltype = selection.getType();

        if ( seltype == CKEDITOR.SELECTION_ELEMENT && element.data('cke-real-element-type') == 'fn') {
            return element;
        }
    }
};