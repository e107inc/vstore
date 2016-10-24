var e107 = e107 || {'settings': {}, 'behaviors': {}};

(function ($)
{
	'use strict';

	/**
	 * Zoom object.
	 */
	e107.ImageZoom = null;

	/**
	 * Namespace for vstore related settings.
	 */
	e107.settings.vstore = e107.settings.vstore || {};

	/**
	 * Default settings for Zoom.
	 */
	e107.settings.vstore.ImageZoom = e107.settings.vstore.ImageZoom || {
		url: false,
		on: 'mouseover',
		duration: 120,
		target: false,
		touch: true,
		magnify: 1,
		callback: function ()
		{
			// TODO colorbox support?
		},
		onZoomIn: function ()
		{
		},
		onZoomOut: function ()
		{
		}
	};

	/**
	 * @type {{attach: e107.behaviors.vstoreImageZoom.attach}}
	 */
	e107.behaviors.vstoreImageZoom = {
		attach: function (context, settings)
		{
			$(context).find('.vstore-zoom').once('vstore-image-zoom').each(function ()
			{
				var $this = $(this);

				if(typeof $.fn.zoom !== 'undefined')
				{
					e107.ImageZoom = $this;
					e107.settings.vstore.ImageZoom.url = $this.find('img:first').attr('src');
					e107.ImageZoom.zoom(e107.settings.vstore.ImageZoom);
				}
			});
		}
	};

	/**
	 * @type {{attach: e107.behaviors.vstoreThumbnail.attach}}
	 */
	e107.behaviors.vstoreThumbnail = {
		attach: function (context, settings)
		{
			$(context).find('.thumbnails a').once('vstore-thumbnail').each(function ()
			{
				$(this).click(function ()
				{
					var $this = $(this);

					var newSrc = $this.data('standard');
					var newSrcSet = $this.attr('href');

					var $images = $('.vstore-zoom img');
					var $links = $('.vstore-zoom a');

					$images.attr('src', newSrc);
					$images.attr('srcset', newSrcSet);

					$links.attr('href', newSrcSet);
					$links.attr('data-standard', newSrc);

					// Change URL in Zoom settings.
					e107.settings.vstore.ImageZoom.url = newSrcSet;
					// Destroy Zoom object.
					e107.ImageZoom.trigger('zoom.destroy');
					// Re-init Zoom with new settings.
					e107.ImageZoom.zoom(e107.settings.vstore.ImageZoom);

					return false;
				});
			});
		}
	};

	/**
	 * @type {{attach: e107.behaviors.vstoreCartQty.attach}}
	 */
	e107.behaviors.vstoreCartQty = {
		attach: function (context, settings)
		{
			$(context).find('.cart-qty').once('vstore-cart-qty').each(function ()
			{
				var $this = $(this);

				$this.keyup(function ()
				{
					$("#cart-qty-submit").show(0);
					$("#cart-checkout").hide(0);
				});
			});
		}
	};

})(jQuery);
