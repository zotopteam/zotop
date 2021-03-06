/**
 * plugin.js
 *
 * Released under LGPL License.
 * Copyright (c) 1999-2015 Ephox Corp. All rights reserved
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*global tinymce:true */

(function () {
    var ajaxUrl = '';
    var loadingImage = '<img id="loadingImg" src="http://static.cnblogs.com/images/loading.gif" alt="" />';

    tinymce.create('tinymce.plugins.PasteUploadPlugin', {
        init: function (ed, url) {
            ed.on("Paste", (function (e) {
                var image, pasteEvent, text;
                pasteEvent = e;
                if (pasteEvent.clipboardData && pasteEvent.clipboardData.items) {
                    image = isImage(pasteEvent);
                    if (image) {
                        e.preventDefault();
                        ed.execCommand('mceInsertContent', false, loadingImage);
                        return uploadFile(image.getAsFile(), getFilename(pasteEvent));
                    }
                }
            }));

            function isImage(data) {
                var i, item;
                i = 0;
                while (i < data.clipboardData.items.length) {
                    item = data.clipboardData.items[i];
                    if (item.type.indexOf("image") !== -1) {
                        return item;
                    }
                    i++;
                }
                return false;
            };
            function uploadFile(file, filename) {
                var formData = new FormData();
                formData.append('imageFile', file);
                formData.append("mimeType", file.type);

                $.ajax({
                    url: ajaxUrl,
                    data: formData,
                    type: 'post',
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            insertIntoTinymce(data.message);
                        } else {
                            replaceLoading(filename);
                        }
                    },
                    error: function (xOptions, textStatus) {
                        replaceLoading(filename);
                        console.log(xOptions.responseText);
                    }
                });
            };
            function insertIntoTinymce(url) {
                var content = ed.getContent();
                content = content.replace(loadingImage, '<img src="' + url + '">');
                ed.setContent(content);
                ed.selection.select(ed.getBody(), true);
                ed.selection.collapse(false);
            };
            function replaceLoading(filename) {
                var content = ed.getContent();
                content = content.replace(loadingImage, filename);
                ed.setContent(content);
                ed.selection.select(ed.getBody(), true);
                ed.selection.collapse(false);
            };
            function getFilename(e) {
                var value;
                if (window.clipboardData && window.clipboardData.getData) {
                    value = window.clipboardData.getData("Text");
                } else if (e.clipboardData && e.clipboardData.getData) {
                    value = e.clipboardData.getData("text/plain");
                }
                value = value.split("\r");
                return value[0];
            };
        },
    });

    // Register plugin
    tinymce.PluginManager.add("pasteUpload", tinymce.plugins.PasteUploadPlugin);
})();
