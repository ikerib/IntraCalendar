! function(e, t) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define(t) : ((e = e || self).vdp_translation_es = e.vdp_translation_es || {}, e.vdp_translation_es.js = t())
}(this, function() {
    "use strict";

    function e(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1, r.configurable = !0, "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r)
        }
    }
    return new(function() {
        function t(e, n, r, o) {
            ! function(e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
            }(this, t), this.language = e, this.months = n, this.monthsAbbr = r, this.days = o, this.rtl = !1, this.ymd = !1, this.yearSuffix = ""
        }
        var n, r, o;
        return n = t, (r = [{
            key: "language",
            get: function() {
                return this._language
            },
            set: function(e) {
                if ("string" != typeof e) throw new TypeError("Language must be a string");
                this._language = e
            }
        }, {
            key: "months",
            get: function() {
                return this._months
            },
            set: function(e) {
                if (12 !== e.length) throw new RangeError("There must be 12 months for ".concat(this.language, " language"));
                this._months = e
            }
        }, {
            key: "monthsAbbr",
            get: function() {
                return this._monthsAbbr
            },
            set: function(e) {
                if (12 !== e.length) throw new RangeError("There must be 12 abbreviated months for ".concat(this.language, " language"));
                this._monthsAbbr = e
            }
        }, {
            key: "days",
            get: function() {
                return this._days
            },
            set: function(e) {
                if (7 !== e.length) throw new RangeError("There must be 7 days for ".concat(this.language, " language"));
                this._days = e
            }
        }]) && e(n.prototype, r), o && e(n, o), t
    }())
    ("Spanish",
        ["Urtarrila", "Otsaila", "Martxoa", "Apirila", "Maiatza", "Ekaina", "Uztaila", "Abuztua", "Iraila", "Urria", "Azaroa", "Abendua"],
        ["Urt", "Ots", "Mar", "Api", "Mai", "Eka", "Uzt", "Abu", "Ira", "Urr", "Aza", "Abe"],
        ["Iga", "Ast", "Asr", "Asz", "Ost", "Osr", "Lar"])
});
