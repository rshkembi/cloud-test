define([
    "jquery",
    "cryptoJs",
    "mage/cookies"
], function($, crypto){
    'use strict';

    $.cookieViewedPageCount = {
        currentUrl: null,
        cookieName: null,
        cookieLifeTime: null,
        cookiePath: null,
        cookieDomain: null,

        initAddCookieProcess: function (currentUrl, cookieName, cookieLifeTime, cookiePath, cookieDomain) {
            this.currentUrl = currentUrl;
            this.cookieName = cookieName;
            this.cookieLifeTime = cookieLifeTime;
            this.cookiePath = cookiePath;
            this.cookieDomain = cookieDomain;
            this.addCookie();
            return true;
        },

        /**
         * Add viewed page count data to cookie
         *
         * @returns {boolean}
         */
        addCookie: function () {
            if (this.currentUrl) {
                var pageCountVal = this.getPageCountValue();
                var currentDate = new Date();
                var expireDate = currentDate.setTime(currentDate.getTime() + Number(this.cookieLifeTime));
                $.cookie(
                    this.cookieName,
                    pageCountVal,
                    {
                        expires: expireDate,
                        path: this.cookiePath,
                        domain: this.cookieDomain,
                        secure: false,
                        httponly: false
                    }
                );
            }
        },

        /**
         * Get viewed page count data
         *
         * @returns {string}
         */
        getPageCountValue: function () {
            var cookieVal = $.cookie(this.cookieName);
            var countArrayVal = [];
            if (cookieVal) {
                countArrayVal = JSON.parse(cookieVal);
                if (cookieVal.length > 2000) {
                    countArrayVal.shift();
                }
            }
            countArrayVal.push(crypto.SHA256(this.currentUrl).toString());
            var uniqueArrayVal = [...new Set(countArrayVal)];

            return JSON.stringify(uniqueArrayVal);
        }
    };

    return $.cookieViewedPageCount;
});
