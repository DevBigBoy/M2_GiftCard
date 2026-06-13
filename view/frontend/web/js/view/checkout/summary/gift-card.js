define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote'
], function (Component, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Market_GiftCard/checkout/summary/gift-card',
            title: 'Gift Card'
        },

        /**
         * @return {Boolean}
         */
        isDisplayed: function () {
            return this.isFullMode() && this.getPureValue() !== 0;
        },

        /**
         * @return {Number}
         */
        getPureValue: function () {
            var totals = quote.getTotals()();

            if (!totals || !totals.total_segments) {
                return 0;
            }

            return totals.total_segments.reduce(function (result, total) {
                if (total.code === 'gift_card') {
                    return total.value;
                }

                return result;
            }, 0);
        },

        /**
         * @return {String}
         */
        getValue: function () {
            return this.getFormattedPrice(this.getPureValue());
        }
    });
});
