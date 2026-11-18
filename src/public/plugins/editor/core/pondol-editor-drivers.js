// src/public/plugins/editor/core/pondol-editor-drivers.js
(function (window) {
  "use strict";

  if (!window.PondolEditor) {
    return;
  }

  var PE = window.PondolEditor;

  // ------------------------------------------------------------------
  // 1. EasyMDE (Markdown - Native Textarea)
  // ------------------------------------------------------------------
  PE.registerDriver("easymde", function (id, options) {
    PE.waitFor(
      function () {
        return typeof window.EasyMDE !== "undefined";
      },
      function () {
        var el = document.getElementById(id);
        if (!el) return;

        var config = Object.assign(
          {
            element: el,
            spellChecker:
              options.spellChecker !== undefined ? options.spellChecker : false,
            minHeight: options.minHeight || "300px",
            uploadImage: !!options.uploadUrl,
            imageUploadFunction: options.uploadUrl
              ? function (file, onSuccess, onError) {
                  PE.uploadFile(options.uploadUrl, file, "file")
                    .then(function (res) {
                      if (res.url) onSuccess(res.url);
                      else onError(res.error || "Upload failed");
                    })
                    .catch(function (err) {
                      onError(err.message || "Upload error");
                    });
                }
              : undefined,
          },
          options.config || {},
        );

        var editor = new window.EasyMDE(config);
        var sync = function () {
          el.value = editor.value();
        };

        editor.codemirror.on("change", sync);
        PE.register(id, editor, sync);
        PE.bindFormSync(el, sync);
      },
    );
  });

  // ------------------------------------------------------------------
  // 2. Cherry Markdown (Markdown - Split Preview & Rich Paste)
  // ------------------------------------------------------------------
  PE.registerDriver("cherry-markdown", function (id, options) {
    PE.waitFor(
      function () {
        return typeof window.Cherry !== "undefined";
      },
      function () {
        var el = document.getElementById(id);
        var holder = document.getElementById(id + "_holder");
        if (!el || !holder) return;

        el.style.display = "none";
        var isDark = options.theme === "dark";
        var supportedLocales = ["zh_CN", "en_US", "ru_RU"];
        var locale =
          supportedLocales.indexOf(options.locale) !== -1
            ? options.locale
            : "en_US";

        var config = Object.assign(
          {
            id: holder.id,
            value: el.value || "",
            locale: locale,
            engine: {
              syntax: {
                table: {
                  enableChart: typeof window.echarts !== "undefined",
                },
              },
            },
            editor: {
              height: options.height || "450px",
            },
            themeSettings: {
              mainTheme: isDark ? "dark" : "default",
              codeBlockTheme: isDark ? "one-dark" : "default",
            },
            fileUpload: options.uploadUrl
              ? function (file, callback) {
                  PE.uploadFile(options.uploadUrl, file, "file")
                    .then(function (res) {
                      if (res.url) callback(res.url);
                    })
                    .catch(function () {});
                }
              : undefined,
            callback: {
              afterChange: function (markdown) {
                el.value = markdown;
              },
            },
          },
          options.config || {},
        );

        var editor = new window.Cherry(config);
        var sync = function () {
          el.value = editor.getMarkdown();
        };

        PE.register(id, editor, sync);
        PE.bindFormSync(el, sync);
      },
    );
  });

  // ------------------------------------------------------------------
  // 3. Vditor (Markdown - WYSIWYG / IR / SV 3모드)
  // ------------------------------------------------------------------
  PE.registerDriver("vditor", function (id, options) {
    PE.waitFor(
      function () {
        return typeof window.Vditor !== "undefined";
      },
      function () {
        var el = document.getElementById(id);
        var holder = document.getElementById(id + "_holder");
        if (!el || !holder) return;

        el.style.display = "none";
        var isDark = options.theme === "dark";
        var cdnBase = options.cdn || "/plugins/editor/vditor";

        var config = Object.assign(
          {
            height: options.height || 420,
            mode: options.mode || "ir",
            cdn: cdnBase,
            lang: options.lang || "ko_KR",
            i18n: window.VditorI18n || undefined,
            theme: isDark ? "dark" : "classic",
            preview: {
              theme: {
                current: isDark ? "dark" : "light",
                path: cdnBase + "/dist/css/content-theme",
              },
            },
            value: el.value || "",
            cache: { enable: false },
            upload: options.uploadUrl
              ? {
                  url: options.uploadUrl,
                  fieldName: "file",
                  headers: {
                    "X-CSRF-TOKEN": PE.getCsrfToken(),
                  },
                }
              : undefined,
            input: function (value) {
              el.value = value;
            },
          },
          options.config || {},
        );

        var editor = new window.Vditor(holder.id, config);
        var sync = function () {
          try {
            el.value = editor.getValue();
          } catch (e) {}
        };

        PE.register(id, editor, sync);
        PE.bindFormSync(el, sync);
      },
    );
  });

  // ------------------------------------------------------------------
  // 4. Editor.js (Block JSON)
  // ------------------------------------------------------------------
  PE.registerDriver("editorjs", function (id, options) {
    PE.waitFor(
      function () {
        return typeof window.EditorJS !== "undefined";
      },
      function () {
        var el = document.getElementById(id);
        var holder = document.getElementById(id + "_holder");
        if (!el || !holder) return;

        el.style.display = "none";

        var initialData = { blocks: [] };
        if (el.value && el.value.trim() !== "") {
          try {
            var parsed = JSON.parse(el.value);
            if (parsed && Array.isArray(parsed.blocks)) {
              initialData = parsed;
            }
          } catch (e) {
            initialData = {
              blocks: [{ type: "paragraph", data: { text: el.value } }],
            };
          }
        }

        var tools = {};
        if (window.Header) {
          tools.header = {
            class: window.Header,
            inlineToolbar: true,
          };
        }
        if (window.EditorjsList || window.List) {
          tools.list = {
            class: window.EditorjsList || window.List,
            inlineToolbar: true,
          };
        }
        if (window.Quote) {
          tools.quote = { class: window.Quote, inlineToolbar: true };
        }
        if (window.CodeTool) {
          tools.code = window.CodeTool;
        }
        if (window.Table) {
          tools.table = { class: window.Table, inlineToolbar: true };
        }
        if (window.ImageTool && options.uploadUrl) {
          tools.image = {
            class: window.ImageTool,
            config: {
              endpoints: { byFile: options.uploadUrl },
              additionalRequestHeaders: {
                "X-CSRF-TOKEN": PE.getCsrfToken(),
              },
            },
          };
        }

        var editor = new window.EditorJS(
          Object.assign(
            {
              holder: holder.id,
              minHeight: options.minHeight || 250,
              data: initialData,
              tools: tools,
              onChange: function (api) {
                api.saver
                  .save()
                  .then(function (outputData) {
                    el.value = JSON.stringify(outputData);
                  })
                  .catch(function () {});
              },
            },
            options.config || {},
          ),
        );

        PE.register(id, editor, null);

        // Editor.js는 save()가 비동기이므로 폼 제출 시 최신 블록 저장 보장
        if (el.form && el.form.dataset.pondolEditorJsBound !== "1") {
          el.form.dataset.pondolEditorJsBound = "1";
          el.form.addEventListener("submit", function (e) {
            if (el.form.dataset.pondolSubmitting === "1") {
              return;
            }
            e.preventDefault();
            PE.syncAll();
            editor
              .save()
              .then(function (outputData) {
                el.value = JSON.stringify(outputData);
                el.form.dataset.pondolSubmitting = "1";
                el.form.submit();
              })
              .catch(function () {
                el.form.dataset.pondolSubmitting = "1";
                el.form.submit();
              });
          });
        }
      },
    );
  });

  // ------------------------------------------------------------------
  // 5. Summernote Lite (jQuery 자동 감지·폴백 + 업로드/테마/프리셋 지원)
  // ------------------------------------------------------------------
  PE.registerDriver("summernote", function (id, options) {
    var base = options.baseUrl || "/plugins/editor/summernote";
    var jqueryUrl = options.jqueryUrl || "/plugins/editor/core/jquery.min.js";

    var ensureJquery = window.jQuery
      ? Promise.resolve()
      : PE.loadScript(jqueryUrl);

    ensureJquery
      .then(function () {
        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.summernote) {
          return Promise.resolve();
        }
        return PE.loadScript(base + "/summernote-lite.min.js").then(
          function () {
            return PE.loadScript(base + "/lang/summernote-ko-KR.min.js");
          },
        );
      })
      .then(function () {
        var $el = window.jQuery("#" + id);
        var el = document.getElementById(id);
        if (!$el.length || $el.next().hasClass("note-editor")) return;

        var callbacks = {
          onChange: function (contents) {
            el.value = contents;
          },
        };

        if (options.imageMode !== "base64" && options.uploadUrl) {
          callbacks.onImageUpload = function (files) {
            Array.prototype.forEach.call(files, function (file) {
              PE.uploadFile(options.uploadUrl, file, "file")
                .then(function (res) {
                  if (res.url) {
                    $el.summernote("insertImage", res.url);
                  }
                })
                .catch(function () {});
            });
          };
        }

        var toolbar =
          options.preset === "minimal"
            ? [
                ["style", ["bold", "italic", "underline", "clear"]],
                ["para", ["ul", "ol"]],
                ["insert", ["link", "picture"]],
              ]
            : undefined;

        var config = Object.assign(
          {
            height: options.height || 350,
            lang: options.lang || "ko-KR",
            dialogsInBody: true,
            dialogsFade: false,
            callbacks: callbacks,
          },
          toolbar ? { toolbar: toolbar } : {},
          options.config || {},
        );

        $el.summernote(config);

        var sync = function () {
          el.value = $el.summernote("code");
        };
        PE.register(id, $el, sync);
        PE.bindFormSync(el, sync);
      })
      .catch(function (err) {
        console.error("[PondolEditor] Summernote load failed:", err);
      });
  });

  // ------------------------------------------------------------------
  // 6. Naver SmartEditor2 (기존 100% 호환)
  // ------------------------------------------------------------------
  PE.registerDriver("smart-editor", function (id, options) {
    PE.waitFor(
      function () {
        return window.nhn && window.nhn.husky && window.nhn.husky.EZCreator;
      },
      function () {
        var el = document.getElementById(id);
        if (!el) return;

        window.oEditors = window.oEditors || [];
        var base = options.baseUrl || "/plugins/editor/smart-editor";

        window.nhn.husky.EZCreator.createInIFrame({
          oAppRef: window.oEditors,
          elPlaceHolder: id,
          sSkinURI: base + "/SmartEditor2Skin.html",
          sCSSBaseURI: base + "/css/ko_KR",
          htParams: Object.assign(
            {
              bUseToolbar: true,
              bUseVerticalResizer: true,
              bUseModeChanger: true,
            },
            options.config || {},
          ),
          fCreator: "createSEditor2",
        });

        var sync = function () {
          if (window.oEditors.getById && window.oEditors.getById[id]) {
            window.oEditors.getById[id].exec("UPDATE_CONTENTS_FIELD", []);
          }
        };

        PE.register(id, window.oEditors, sync);
        PE.bindFormSync(el, sync);
      },
    );
  });

  // ------------------------------------------------------------------
  // 7. TinyMCE (기존 100% 호환 + light/dark 테마 대응)
  // ------------------------------------------------------------------
  PE.registerDriver("tinymce", function (id, options) {
    PE.waitFor(
      function () {
        return typeof window.tinymce !== "undefined";
      },
      function () {
        var el = document.getElementById(id);
        if (!el) return;

        var isDark = options.theme === "dark";

        window.tinymce.init(
          Object.assign(
            {
              selector: "#" + id,
              plugins:
                "preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help quickbars emoticons",
              toolbar:
                "undo redo | blocks | bold italic strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | emoticons charmap | removeformat",
              images_file_types: "jpg,jpeg,png,gif,webp",
              images_upload_url: options.uploadUrl || undefined,
              license_key: options.license_key || "gpl",
              language: options.language || "ko_KR",
              skin: isDark ? "oxide-dark" : "oxide",
              content_css: isDark ? "dark" : "default",
              setup: function (editor) {
                editor.on("change", function () {
                  editor.save();
                });
                PE.register(id, editor, function () {
                  editor.save();
                });
                PE.bindFormSync(el, function () {
                  editor.save();
                });
              },
            },
            options.config || {},
          ),
        );
      },
    );
  });

  // ------------------------------------------------------------------
  // 8. Froala (기존 100% 호환)
  // ------------------------------------------------------------------
  PE.registerDriver("froala", function (id, options) {
    PE.waitFor(
      function () {
        return typeof window.FroalaEditor !== "undefined";
      },
      function () {
        var el = document.getElementById(id);
        if (!el) return;

        var editor = new window.FroalaEditor(
          "#" + id,
          Object.assign(
            {
              attribution: false,
              theme: options.theme === "dark" ? "dark" : "default",
            },
            options.config || {},
          ),
        );

        PE.register(id, editor, function () {
          if (editor.html && typeof editor.html.get === "function") {
            el.value = editor.html.get();
          }
        });
        PE.bindFormSync(el, function () {
          if (editor.html && typeof editor.html.get === "function") {
            el.value = editor.html.get();
          }
        });
      },
    );
  });

  // ------------------------------------------------------------------
  // 9. RichTextEditor (기존 100% 호환)
  // ------------------------------------------------------------------
  PE.registerDriver("richtext", function (id, options) {
    PE.waitFor(
      function () {
        return typeof window.RichTextEditor !== "undefined";
      },
      function () {
        var el = document.getElementById(id);
        if (!el) return;

        window.RTEditorInstances = window.RTEditorInstances || {};
        var instance = new window.RichTextEditor(
          el,
          Object.assign(
            {
              url_base: options.baseUrl || "/plugins/editor/richtext",
            },
            options.config || {},
          ),
        );

        window.RTEditorInstances[id] = instance;

        var sync = function () {
          el.value = instance.getHTMLCode();
        };

        instance.attachEvent("change", sync);
        PE.register(id, instance, sync);
        PE.bindFormSync(el, sync);
      },
    );
  });
})(window);
