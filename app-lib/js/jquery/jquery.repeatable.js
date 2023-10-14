
/*

version 0.40.3, 2023/OCTOBER/14TH

MIT License

Copyright (c) 2013-2018 Jennifer Wachter

Copyright (c) 2022-2023 Michael Kilday (mike@dragonfrugal.com)


Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


*/


(function ($) {

	$.fn.repeatable = function (devConfig) {

		/**
		 * Default config
		 * @type {Object}
		 */
		var defaults = {
			prefix: "new",
			addTrigger: ".add",
			deleteTrigger: ".delete",
			max: null,
               min: 0,
			template: null,
			itemContainer: ".field-group",
			beforeAdd: function () {},
			afterAdd: function (item) {},
			beforeDelete: function (item) {},
			afterDelete: function () {}
		};

		/**
		 * DOM element into which repeatable
		 * items will be added
		 * @type {jQuery object}
		 */
		var target = $(this);

		/**
		 * Blend passed user config with default config
		 * @type {array}
		 */
		var config = $.extend({}, defaults, devConfig);

		/**
		 * Total templated items found on the page
		 * at load. These may be created by server-side
		 * scripts.
		 * @return null
		 */
		var total = function () {
		    calc_total = $(target).find(config.itemContainer).length;
		    //console.log(calc_total); // DEBUGGING ONLY
			return calc_total;
		}();

		/**
		 * Iterator used to make each added
		 * repeatable element unique
		 * @type {Number}
		 */

		/**
		 * Add an element to the target
		 * and call the callback function
		 * @param  object e Event
		 * @return null
		 */
		var addOne = function (e) {
			e.preventDefault();
			config.beforeAdd.call(this);
			var item = createOne();
			config.afterAdd.call(this, item);
		};

		/**
		 * Delete the parent element
		 * and call the callback function
		 * @param  object e Event
		 * @return null
		 */
		var deleteOne = function (e) {
			e.preventDefault();
			if (total === config.min) { alert('Minimum allowed entries is ' + config.min + ', please just delete the data inside the fields, and update / save the settings.'); return; }
			var item = $(this).parents(config.itemContainer).first();
			config.beforeDelete.call(this, item);
			item.remove();
			total--;
			maintainAddBtn();
			config.afterDelete.call(this);
		};

		/**
		 * Add an element to the target
		 * @return null
		 */
		var createOne = function() {
			var item = getUniqueTemplate();
			item.appendTo(target);
			total++;
			maintainAddBtn();
			return item;
		};

		/**
		 * Alter the given template to make
		 * each form field name unique
		 * @return {jQuery object}
		 */
		var getUniqueTemplate = function () {
		     
		     while ( duplicateCheck() == 'yes' ) {
		     total++;
		     }
		     
			var template = $(config.template).html();
			template = template.replace(/{\?}/g, config.prefix + total); 	// {?} => iterated placeholder
			template = template.replace(/\{[^\?\}]*\}/g, ""); 	// {valuePlaceholder} => ""
			return $(template);
			
		};

		/**
		 * Checks for duplicate indexes in form arrays
		 */
		var duplicateCheck = function () {

			if ( 1 < $(config.itemContainer + ' input[data-track-index=' + config.prefix + total + ']').length || 1 < $(config.itemContainer + ' select[data-track-index=' + config.prefix + total + ']').length ) {
			console.log('POTENTIAL duplicate form array index clash, upping count...')
               return 'yes';
               }
               else {
               return 'no';
               }
               
		};

		/**
		 * Determines if the add trigger
		 * needs to be disabled
		 * @return null
		 */
		var maintainAddBtn = function () {
			if (!config.max) {
				return;
			}

			if (total === config.max) {
				$(config.addTrigger).attr("disabled", "disabled");
			} else if (total < config.max) {
				$(config.addTrigger).removeAttr("disabled");
			}
		};

		/**
		 * Setup the repeater
		 * @return null
		 */
		(function () {
			$(config.addTrigger).on("click", addOne);
			$("form").on("click", config.deleteTrigger, deleteOne);

			if (!total) {
				var toCreate = config.min - total;
				for (var j = 0; j < toCreate; j++) {
					createOne();
				}
			}

		})();
	};

})(jQuery);
