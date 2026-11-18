// src/public/plugins/editor/core/pondol-editor.js
(function (window) {
  "use strict";

  var PondolEditor = window.PondolEditor || {
    instances: {},
    syncCallbacks: {},
    drivers: {},

    getCsrfToken: function () {
      var meta = document.querySelector('meta[name="csrf-token"]');
      return meta ? meta.getAttribute("content") : "";
    },

    waitFor: function (conditionFn, callback, intervalMs, maxAttempts) {
      var attempts = 0;
      var max = maxAttempts || 200;
      var interval = intervalMs || 50;

      (function check() {
        if (conditionFn()) {
          callback();
          return;
        }
        attempts++;
        if (attempts < max) {
          setTimeout(check, interval);
        }
      })();
    },

    onReady: function (callback) {
      if (document.readyState === "complete") {
        callback();
      } else {
        window.addEventListener("load", callback);
      }
    },

    register: function (id, instance, syncFn) {
      this.instances[id] = instance;
      if (typeof syncFn === "function") {
        this.syncCallbacks[id] = syncFn;
      }
    },

    get: function (id) {
      return this.instances[id] || null;
    },

    syncAll: function () {
      Object.keys(this.syncCallbacks).forEach(function (id) {
        try {
          PondolEditor.syncCallbacks[id]();
        } catch (e) {}
      });
      // 기존 SmartEditor2 호환
      if (window.oEditors && window.oEditors.getById) {
        Object.keys(window.oEditors.getById).forEach(function (id) {
          try {
            window.oEditors.getById[id].exec("UPDATE_CONTENTS_FIELD", []);
          } catch (e) {}
        });
      }
    },

    bindFormSync: function (element, syncFn) {
      if (!element || !element.form || typeof syncFn !== "function") {
        return;
      }
      if (element.form.dataset.pondolSyncBound === "1") {
        return;
      }
      element.form.dataset.pondolSyncBound = "1";
      element.form.addEventListener("submit", function () {
        PondolEditor.syncAll();
      });
    },

    uploadFile: function (uploadUrl, file, fieldName) {
      var formData = new FormData();
      formData.append(fieldName || "file", file);

      return fetch(uploadUrl, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": this.getCsrfToken(),
          Accept: "application/json",
        },
        body: formData,
      }).then(function (res) {
        return res.json().then(function (data) {
          if (!res.ok) {
            throw new Error(data.error || data.message || "Upload failed");
          }
          return data;
        });
      });
    },

    registerDriver: function (name, initFn) {
      this.drivers[name] = initFn;
    },

    init: function (driverName, id, options) {
      var self = this;
      this.onReady(function () {
        var driverFn = self.drivers[driverName];
        if (typeof driverFn !== "function") {
          return;
        }
        if (self.instances[id]) {
          return; // 중복 초기화 방지
        }
        driverFn(id, options || {});
      });
    },
    loadedScripts: {},

    loadScript: function (src) {
      if (this.loadedScripts[src]) {
        return this.loadedScripts[src];
      }
      this.loadedScripts[src] = new Promise(function (resolve, reject) {
        var s = document.createElement("script");
        s.src = src;
        s.onload = resolve;
        s.onerror = reject;
        document.head.appendChild(s);
      });
      return this.loadedScripts[src];
    },
  };

  PondolEditor.registerDriver("suneditor", function (id, options) {
    PondolEditor.waitFor(
      function () {
        return typeof window.SUNEDITOR !== "undefined";
      },
      function () {
        var el = document.getElementById(id);
        if (!el) return;

        var isV3 = !!window.SUNEDITOR.plugins;
        var langKey = options.lang || "ko";
        var langObj =
          (window.SUNEDITOR_LANG && window.SUNEDITOR_LANG[langKey]) ||
          (window.SUNEDITOR.langs && window.SUNEDITOR.langs[langKey]) ||
          undefined;

        var buttonPresets = isV3
          ? {
              minimal: [
                ["bold", "underline", "italic", "strike"],
                ["list", "link", "image"],
              ],
              standard: [
                ["undo", "redo"],
                ["blockStyle", "font", "fontSize"],
                ["bold", "italic", "underline", "strike", "removeFormat"],
                ["fontColor", "backgroundColor"],
                ["align", "list", "table", "link", "image"],
                ["fullScreen", "codeView"],
              ],
              full: [
                ["undo", "redo"],
                ["blockStyle", "font", "fontSize"],
                [
                  "bold",
                  "italic",
                  "underline",
                  "strike",
                  "subscript",
                  "superscript",
                  "removeFormat",
                ],
                ["fontColor", "backgroundColor"],
                ["outdent", "indent", "align", "list", "lineHeight", "hr"],
                ["table", "link", "image", "video"],
                ["fullScreen", "showBlocks", "codeView", "preview"],
              ],
            }
          : {
              minimal: [
                ["bold", "underline", "italic", "strike"],
                ["list", "link", "image"],
              ],
              standard: [
                ["undo", "redo"],
                ["fontSize", "formatBlock"],
                ["bold", "underline", "italic", "strike", "removeFormat"],
                ["fontColor", "hiliteColor"],
                ["align", "list", "table", "link", "image"],
                ["fullScreen", "codeView"],
              ],
              full: [
                ["undo", "redo"],
                ["font", "fontSize", "formatBlock"],
                [
                  "bold",
                  "underline",
                  "italic",
                  "strike",
                  "subscript",
                  "superscript",
                  "removeFormat",
                ],
                ["fontColor", "hiliteColor"],
                [
                  "outdent",
                  "indent",
                  "align",
                  "list",
                  "lineHeight",
                  "horizontalRule",
                ],
                ["table", "link", "image", "video"],
                ["fullScreen", "showBlocks", "codeView", "preview"],
              ],
            };

        var preset = options.preset || "standard";
        var editor = null;

        var sync = function () {
          if (!editor) return;
          if (
            editor.$ &&
            editor.$.html &&
            typeof editor.$.html.get === "function"
          ) {
            el.value = editor.$.html.get();
          } else if (typeof editor.save === "function") {
            editor.save();
          }
        };

        var baseConfig = {
          height: options.height || "350px",
          lang: langObj,
          buttonList: buttonPresets[preset] || buttonPresets.standard,
        };

        if (isV3) {
          baseConfig.plugins = window.SUNEDITOR.plugins;
          if (options.uploadUrl) {
            baseConfig.image = {
              uploadUrl: options.uploadUrl,
              uploadHeaders: {
                "X-CSRF-TOKEN": PondolEditor.getCsrfToken(),
              },
            };
          }
          baseConfig.events = {
            onChange: sync,
          };
        } else {
          if (options.uploadUrl) {
            baseConfig.imageUploadUrl = options.uploadUrl;
            baseConfig.imageUploadHeader = {
              "X-CSRF-TOKEN": PondolEditor.getCsrfToken(),
            };
          }
        }

        var config = Object.assign(baseConfig, options.config || {});
        editor = window.SUNEDITOR.create(el, config);

        if (!isV3) {
          editor.onChange = sync;
        }

        PondolEditor.register(id, editor, sync);
        PondolEditor.bindFormSync(el, sync);
      },
    );
  });
  window.PondolEditor = PondolEditor;

  // 기존 SmartEditor 컴포넌트의 전역 함수 호환
  if (typeof window.updateContentsField !== "function") {
    window.updateContentsField = function () {
      PondolEditor.syncAll();
    };
  }
})(window);
