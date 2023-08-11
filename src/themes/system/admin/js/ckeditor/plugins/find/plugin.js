/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.plugins.add( 'find', {
	requires: 'dialog',
	lang: 'af,ar,bg,bn,bs,ca,cs,cy,da,de,el,en-au,en-ca,en-gb,en,eo,es,et,eu,fa,fi,fo,fr-ca,fr,gl,gu,he,hi,hr,hu,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,pl,pt-br,pt,ro,ru,sk,sl,sr-latn,sr,sv,th,tr,ug,uk,vi,zh-cn,zh', // %REMOVE_LINE_CORE%
	icons: 'find,find-rtl,replace', // %REMOVE_LINE_CORE%
	init: function( editor ) {
		var findCommand = editor.addCommand( 'find', new CKEDITOR.dialogCommand( 'find' ) );
		findCommand.canUndo = false;
		findCommand.readOnly = 1;

		var replaceCommand = editor.addCommand( 'replace', new CKEDITOR.dialogCommand( 'replace' ) );
		replaceCommand.canUndo = false;

		if ( editor.ui.addButton ) {
			editor.ui.addButton( 'Find', {
				label: editor.lang.find.find,
				command: 'find',
				toolbar: 'find,10'
			});

			editor.ui.addButton( 'Replace', {
				label: editor.lang.find.replace,
				command: 'replace',
				toolbar: 'find,20'
			});
		}

		CKEDITOR.dialog.add( 'find', this.path + 'dialogs/find.js' );
		CKEDITOR.dialog.add( 'replace', this.path + 'dialogs/find.js' );
	}
});

/**
 * Defines the style to be used to highlight results with the find dialog.
 *
 *		// Highlight search results with blue on yellow.
 *		config.find_highlight = {
 *			element: 'span',
 *			styles: { 'background-color': '#ff0', color: '#00f' }
 *		};
 *
 * @cfg
 * @member CKEDITOR.config
 */
CKEDITOR.config.find_highlight = {
	element: 'span', styles: { 'background-color': '#004', color: '#fff' } };
