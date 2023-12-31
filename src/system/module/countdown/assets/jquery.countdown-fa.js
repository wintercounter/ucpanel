/* http://keith-wood.name/countdown.html
   Persian (فارسی) initialisation for the jQuery countdown extension
   Written by Alireza Ziaie (ziai@magfa.com) Oct 2008. */
(function($) {
	$.countdown.regional['fa'] = {
		labels: ['‌سال', 'ماه', 'هفته', 'روز', 'ساعت', 'دقیقه', 'ثانیه'],
		labels1: ['سال', 'ماه', 'هفته', 'روز', 'ساعت', 'دقیقه', 'ثانیه'],
		compactLabels: ['س', 'م', 'ه', 'ر'],
		whichLabels: null,
		digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
		timeSeparator: ':', isRTL: true};
	$.countdown.setDefaults($.countdown.regional['fa']);
})(jQuery);
