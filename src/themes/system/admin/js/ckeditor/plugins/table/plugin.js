/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.plugins.add( 'table', {
	requires: 'dialog',
	lang: 'af,ar,bg,bn,bs,ca,cs,cy,da,de,el,en-au,en-ca,en-gb,en,eo,es,et,eu,fa,fi,fo,fr-ca,fr,gl,gu,he,hi,hr,hu,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,pl,pt-br,pt,ro,ru,sk,sl,sr-latn,sr,sv,th,tr,ug,uk,vi,zh-cn,zh', // %REMOVE_LINE_CORE%
	icons: 'table', // %REMOVE_LINE_CORE%
	init: function( editor ) {
		if ( editor.blockless )
			return;

		var table = CKEDITOR.plugins.table,
			lang = editor.lang.table;

		editor.addCommand( 'table', new CKEDITOR.dialogCommand( 'table', { context: 'table' } ) );

		function createDef( def ) {
			return CKEDITOR.tools.extend( def || {}, {
				contextSensitive: 1,
				refresh: function( editor, path ) {
					this.setState( path.contains( 'table', 1 ) ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED );
				}
			});
		}

		editor.addCommand( 'tableProperties', new CKEDITOR.dialogCommand( 'tableProperties', createDef() ) );
		editor.addCommand( 'tableDelete', createDef({
			exec: function( editor ) {
				var path = editor.elementPath(),
					table = path.contains( 'table', 1 );

				if ( !table )
					return;

				// If the table's parent has only one child remove it as well (unless it's the body or a table cell) (#5416, #6289)
				var parent = table.getParent();
				if ( parent.getChildCount() == 1 && !parent.is( 'body', 'td', 'th' ) )
					table = parent;

				var range = editor.createRange();
				range.moveToPosition( table, CKEDITOR.POSITION_BEFORE_START );
				table.remove();
				range.select();
			}
		}));

		editor.ui.addButton && editor.ui.addButton( 'Table', {
			label: lang.toolbar,
			command: 'table',
			toolbar: 'insert,30'
		});

		CKEDITOR.dialog.add( 'table', this.path + 'dialogs/table.js' );
		CKEDITOR.dialog.add( 'tableProperties', this.path + 'dialogs/table.js' );

		// If the "menu" plugin is loaded, register the menu items.
		if ( editor.addMenuItems ) {
			editor.addMenuItems({
				table: {
					label: lang.menu,
					command: 'tableProperties',
					group: 'table',
					order: 5
				},

				tabledelete: {
					label: lang.deleteTable,
					command: 'tableDelete',
					group: 'table',
					order: 1
				}
			});
		}

		editor.on( 'doubleclick', function( evt ) {
			var element = evt.data.element;

			if ( element.is( 'table' ) )
				evt.data.dialog = 'tableProperties';
		});

		// If the "contextmenu" plugin is loaded, register the listeners.
		if ( editor.contextMenu ) {
			editor.contextMenu.addListener( function() {
				// menu item state is resolved on commands.
				return {
					tabledelete: CKEDITOR.TRISTATE_OFF,
					table: CKEDITOR.TRISTATE_OFF
				};
			});
		}
	}
});
