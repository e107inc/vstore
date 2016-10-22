var e107 = e107 || {'settings': {}, 'behaviors': {}};

(function ($)
{
	'use strict';

	e107.settings.vstore = e107.settings.vstore || {};

	/**
	 * @type {{attach: e107.behaviors.vstoreImageZoom.attach}}
	 */
	e107.behaviors.vstoreImageZoom = {
		attach: function (context, settings)
		{
			$(context).find('.vstore-zoom').once('vstore-image-zoom').each(function ()
			{
				e107.settings.vstore.ImageZoom = $(this);

				if(typeof $.fn.zoom !== 'undefined')
				{
					e107.settings.vstore.ImageZoom.zoom({
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
					});
				}
			});
		},
		detach: function (context, settings)
		{
			$(context).find('.vstore-zoom').removeOnce('vstore-image-zoom').each(function ()
			{
				if(typeof $.fn.zoom !== 'undefined')
				{
					e107.settings.vstore.ImageZoom.trigger('zoom.destroy');
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

					// Use Zoom's container as context for detach/attach.
					var context = $('.vstore-zoom').parent();

					// Need to re-init Zoom, so we detach its behaviors first.
					e107.detachBehaviors(context);

					// Then attach behaviors again.
					e107.attachBehaviors(context);

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
