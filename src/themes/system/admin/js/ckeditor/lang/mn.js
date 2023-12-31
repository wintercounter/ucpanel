/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Mongolian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Contains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang[ 'mn' ] = {
	/**
	 * The language reading direction. Possible values are "rtl" for
	 * Right-To-Left languages (like Arabic) and "ltr" for Left-To-Right
	 * languages (like English).
	 * @default 'ltr'
	 */
	dir: 'ltr',

	// ARIA description.
	editor: 'Хэлбэрт бичвэр боловсруулагч',

	// Common messages and labels.
	common: {
		// Screenreader titles. Please note that screenreaders are not always capable
		// of reading non-English words. So be careful while translating it.
		editorHelp: 'Press ALT 0 for help', // MISSING

		browseServer: 'Сервер харуулах',
		url: 'URL',
		protocol: 'Протокол',
		upload: 'Хуулах',
		uploadSubmit: 'Үүнийг сервэррүү илгээ',
		image: 'Зураг',
		flash: 'Флаш',
		form: 'Форм',
		checkbox: 'Чекбокс',
		radio: 'Радио товч',
		textField: 'Техт талбар',
		textarea: 'Техт орчин',
		hiddenField: 'Нууц талбар',
		button: 'Товч',
		select: 'Сонгогч талбар',
		imageButton: 'Зурагтай товч',
		notSet: '<Оноохгүй>',
		id: 'Id',
		name: 'Нэр',
		langDir: 'Хэлний чиглэл',
		langDirLtr: 'Зүүнээс баруун (LTR)',
		langDirRtl: 'Баруунаас зүүн (RTL)',
		langCode: 'Хэлний код',
		longDescr: 'URL-ын тайлбар',
		cssClass: 'Stylesheet классууд',
		advisoryTitle: 'Зөвлөлдөх гарчиг',
		cssStyle: 'Загвар',
		ok: 'OK',
		cancel: 'Болих',
		close: 'Хаах',
		preview: 'Уридчлан харах',
		resize: 'Resize', // MISSING
		generalTab: 'Ерөнхий',
		advancedTab: 'Нэмэлт',
		validateNumberFailed: 'This value is not a number.', // MISSING
		confirmNewPage: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING
		options: 'Сонголт',
		target: 'Бай',
		targetNew: 'New Window (_blank)', // MISSING
		targetTop: 'Topmost Window (_top)', // MISSING
		targetSelf: 'Same Window (_self)', // MISSING
		targetParent: 'Parent Window (_parent)', // MISSING
		langDirLTR: 'Зүүн талаас баруун тийшээ (LTR)',
		langDirRTL: 'Баруун талаас зүүн тийшээ (RTL)',
		styles: 'Загвар',
		cssClasses: 'Stylesheet классууд',
		width: 'Өргөн',
		height: 'Өндөр',
		align: 'Эгнээ',
		alignLeft: 'Зүүн',
		alignRight: 'Баруун',
		alignCenter: 'Төвд',
		alignTop: 'Дээд талд',
		alignMiddle: 'Дунд талд',
		alignBottom: 'Доод талд',
		invalidValue	: 'Invalid value.', // MISSING
		invalidHeight: 'Өндөр нь тоо байх ёстой.',
		invalidWidth: 'Өргөн нь тоо байх ёстой.',
		invalidCssLength: 'Value specified for the "%1" field must be a positive number with or without a valid CSS measurement unit (px, %, in, cm, mm, em, ex, pt, or pc).', // MISSING
		invalidHtmlLength: 'Value specified for the "%1" field must be a positive number with or without a valid HTML measurement unit (px or %).', // MISSING
		invalidInlineStyle: 'Value specified for the inline style must consist of one or more tuples with the format of "name : value", separated by semi-colons.', // MISSING
		cssLengthTooltip: 'Enter a number for a value in pixels or a number with a valid CSS unit (px, %, in, cm, mm, em, ex, pt, or pc).', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	}
};
