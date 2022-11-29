postboxes.add_postbox_toggles(pagenow);

(function($) {
    $("#cptui_select_post_type_submit").hide();
    $("#cptui_select_taxonomy_submit").hide();
    if ("edit" === getParameterByName("action")) {
        var original_slug = $("#name").val();
    }
    $("#hierarchical").on("change", function() {
        var hierarchical = $(this).val();
        if ("1" === hierarchical) {
            $("#page-attributes").prop("checked", true);
        } else {
            $("#page-attributes").prop("checked", false);
        }
    });
    $("#post_type").on("change", function() {
        $("#cptui_select_post_type").submit();
    });
    $("#taxonomy").on("change", function() {
        $("#cptui_select_taxonomy").submit();
    });
    $(".cptui-delete-top, .cptui-delete-bottom").on("click", function(e) {
        e.preventDefault();
        var msg = "";
        if (typeof cptui_type_data !== "undefined") {
            msg = cptui_type_data.confirm;
        } else if (typeof cptui_tax_data !== "undefined") {
            msg = cptui_tax_data.confirm;
        }
        var submit_delete_warning = $('<div class="cptui-submit-delete-dialog">' + msg + "</div>").appendTo("#poststuff").dialog({
            dialogClass: "wp-dialog",
            modal: true,
            autoOpen: true,
            buttons: {
                OK: function() {
                    var form = $(e.target).closest("form");
                    $(e.target).off("click").click();
                },
                Cancel: function() {
                    $(this).dialog("close");
                }
            }
        });
    });
    $("#support .question").each(function() {
        var tis = $(this), state = false, answer = tis.next("div").slideUp();
        tis.on("click keydown", function(e) {
            if (e.type === "keydown" && e.keyCode !== 32 && e.keyCode !== 13) {
                return;
            }
            e.preventDefault();
            state = !state;
            answer.slideToggle(state);
            tis.toggleClass("active", state);
            tis.attr("aria-expanded", state.toString());
            tis.focus();
        });
    });
    $("#name").on("keyup", function(e) {
        var value, original_value;
        value = original_value = $(this).val();
        if (e.keyCode !== 9 && e.keyCode !== 37 && e.keyCode !== 38 && e.keyCode !== 39 && e.keyCode !== 40) {
            value = value.replace(/ /g, "_");
            value = value.toLowerCase();
            value = replaceDiacritics(value);
            value = transliterate(value);
            value = replaceSpecialCharacters(value);
            if (value !== original_value) {
                $(this).prop("value", value);
            }
        }
        if (typeof original_slug !== "undefined") {
            var $slugchanged = $("#slugchanged");
            if (value != original_slug) {
                $slugchanged.removeClass("hidemessage");
            } else {
                $slugchanged.addClass("hidemessage");
            }
        }
        var $slugexists = $("#slugexists");
        if (typeof cptui_type_data != "undefined") {
            if (cptui_type_data.existing_post_types.hasOwnProperty(value) && value !== original_slug) {
                $slugexists.removeClass("hidemessage");
            } else {
                $slugexists.addClass("hidemessage");
            }
        }
        if (typeof cptui_tax_data != "undefined") {
            if (cptui_tax_data.existing_taxonomies.hasOwnProperty(value) && value !== original_slug) {
                $slugexists.removeClass("hidemessage");
            } else {
                $slugexists.addClass("hidemessage");
            }
        }
    });
    function replaceDiacritics(s) {
        var diacritics = [ /[\300-\306]/g, /[\340-\346]/g, /[\310-\313]/g, /[\350-\353]/g, /[\314-\317]/g, /[\354-\357]/g, /[\322-\330]/g, /[\362-\370]/g, /[\331-\334]/g, /[\371-\374]/g, /[\321]/g, /[\361]/g, /[\307]/g, /[\347]/g ];
        var chars = [ "A", "a", "E", "e", "I", "i", "O", "o", "U", "u", "N", "n", "C", "c" ];
        for (var i = 0; i < diacritics.length; i++) {
            s = s.replace(diacritics[i], chars[i]);
        }
        return s;
    }
    function replaceSpecialCharacters(s) {
        s = s.replace(/[^a-z0-9\s-]/gi, "_");
        return s;
    }
    function composePreviewContent(value) {
        var re = /(http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/;
        var is_url = re.test(value);
        if (!value) {
            return "";
        } else if (0 === value.indexOf("dashicons-")) {
            return $('<div class="dashicons-before"><br></div>').addClass(htmlEncode(value));
        } else if (is_url) {
            var imgsrc = encodeURI(value);
            var theimg = document.createElement("IMG");
            theimg.src = imgsrc;
            return theimg;
        }
    }
    function htmlEncode(str) {
        return String(str).replace(/[^-\w. ]/gi, function(c) {
            return "&#" + c.charCodeAt(0) + ";";
        });
    }
    var cyrillic = {
        "Ё": "YO",
        "Й": "I",
        "Ц": "TS",
        "У": "U",
        "К": "K",
        "Е": "E",
        "Н": "N",
        "Г": "G",
        "Ш": "SH",
        "Щ": "SCH",
        "З": "Z",
        "Х": "H",
        "Ъ": "'",
        "ё": "yo",
        "й": "i",
        "ц": "ts",
        "у": "u",
        "к": "k",
        "е": "e",
        "н": "n",
        "г": "g",
        "ш": "sh",
        "щ": "sch",
        "з": "z",
        "х": "h",
        "ъ": "'",
        "Ф": "F",
        "Ы": "I",
        "В": "V",
        "А": "a",
        "П": "P",
        "Р": "R",
        "О": "O",
        "Л": "L",
        "Д": "D",
        "Ж": "ZH",
        "Э": "E",
        "ф": "f",
        "ы": "i",
        "в": "v",
        "а": "a",
        "п": "p",
        "р": "r",
        "о": "o",
        "л": "l",
        "д": "d",
        "ж": "zh",
        "э": "e",
        "Я": "Ya",
        "Ч": "CH",
        "С": "S",
        "М": "M",
        "И": "I",
        "Т": "T",
        "Ь": "'",
        "Б": "B",
        "Ю": "YU",
        "я": "ya",
        "ч": "ch",
        "с": "s",
        "м": "m",
        "и": "i",
        "т": "t",
        "ь": "'",
        "б": "b",
        "ю": "yu"
    };
    function transliterate(word) {
        return word.split("").map(function(char) {
            return cyrillic[char] || char;
        }).join("");
    }
    if (undefined != wp.media) {
        var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;
    }
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"), results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return "";
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
    $("#cptui_choose_icon").on("click", function(e) {
        e.preventDefault();
        var button = $(this);
        var id = jQuery("#menu_icon").attr("id");
        _custom_media = true;
        wp.media.editor.send.attachment = function(props, attachment) {
            if (_custom_media) {
                $("#" + id).val(attachment.url).change();
            } else {
                return _orig_send_attachment.apply(this, [ props, attachment ]);
            }
        };
        wp.media.editor.open(button);
        return false;
    });
    $("#menu_icon").on("change", function() {
        var value = $(this).val();
        value = value.trim();
        $("#menu_icon_preview").html(composePreviewContent(value));
    });
    $(".cptui-help").on("click", function(e) {
        e.preventDefault();
    });
    $(".cptui-taxonomy-submit").on("click", function(e) {
        if ($(".cptui-table :checkbox:checked").length == 0) {
            e.preventDefault();
            var no_associated_type_warning = $('<div class="cptui-taxonomy-empty-types-dialog">' + cptui_tax_data.no_associated_type + "</div>").appendTo("#poststuff").dialog({
                dialogClass: "wp-dialog",
                modal: true,
                autoOpen: true,
                buttons: {
                    OK: function() {
                        $(this).dialog("close");
                    }
                }
            });
        }
    });
    $("#auto-populate").on("click tap", function(e) {
        e.preventDefault();
        var slug = $("#name").val();
        var plural = $("#label").val();
        var singular = $("#singular_label").val();
        var fields = $('.cptui-labels input[type="text"]');
        if ("" === slug) {
            return;
        }
        if ("" === plural) {
            plural = slug;
        }
        if ("" === singular) {
            singular = slug;
        }
        $(fields).each(function(i, el) {
            var newval = $(el).data("label");
            var plurality = $(el).data("plurality");
            if ("undefined" !== newval) {
                if ("plural" === plurality) {
                    newval = newval.replace(/item/gi, plural);
                } else {
                    newval = newval.replace(/item/gi, singular);
                }
                if ($(el).val() === "") {
                    $(el).val(newval);
                }
            }
        });
    });
    $("#auto-clear").on("click tap", function(e) {
        e.preventDefault();
        var fields = $('.cptui-labels input[type="text"]');
        $(fields).each(function(i, el) {
            $(el).val("");
        });
    });
})(jQuery);