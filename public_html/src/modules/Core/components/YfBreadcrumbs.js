/* script */
//
//
//
//
//
//
//
//
//
//
//
//
//
var __vue_script__ = {
  name: 'YfBreadcrumbs',
  computed: {
    matched: function matched() {
      return this.$route.matched.filter(function (route) {
        return route.path && route.path.split('/').pop();
      });
    }
  }
  /* template */

};

var __vue_render__ = function __vue_render__() {
  var _vm = this;

  var _h = _vm.$createElement;

  var _c = _vm._self._c || _h;

  return _c("q-breadcrumbs", {
    class: ["breadcrumbs", ""],
    attrs: {
      "active-color": "info"
    }
  }, [_c("q-breadcrumbs-el", {
    attrs: {
      icon: "mdi-home",
      to: "/"
    }
  }), _vm._v(" "), _vm._l(_vm.matched, function (route) {
    return _c("q-breadcrumbs-el", {
      key: route.name,
      attrs: {
        label: route.path.split("/").pop(),
        to: route.path
      }
    });
  })], 2);
};

var __vue_staticRenderFns__ = [];
__vue_render__._withStripped = true;
/* style */

var __vue_inject_styles__ = function __vue_inject_styles__(inject) {
  if (!inject) return;
  inject("data-v-6bc036b2_0", {
    source: "\n.src-modules-Core-components-breadcrumbs-1za- * {\r\n  flex-wrap: nowrap;\n}\r\n",
    map: {
      "version": 3,
      "sources": ["C:\\www\\YetiForceCRM\\public_html\\src\\modules\\Core\\components\\YfBreadcrumbs.vue"],
      "names": [],
      "mappings": ";AAwBA;EACA,iBAAA;AACA",
      "file": "YfBreadcrumbs.vue",
      "sourcesContent": ["<!-- /* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */ -->\r\n<template>\r\n  <q-breadcrumbs active-color=\"info\" :class=\"['breadcrumbs', '']\">\r\n    <q-breadcrumbs-el icon=\"mdi-home\" :to=\"'/'\" />\r\n    <q-breadcrumbs-el\r\n      v-for=\"route in matched\"\r\n      :key=\"route.name\"\r\n      :label=\"route.path.split('/').pop()\"\r\n      :to=\"route.path\"\r\n    />\r\n  </q-breadcrumbs>\r\n</template>\r\n\r\n<script>\r\nexport default {\r\n  name: 'YfBreadcrumbs',\r\n  computed: {\r\n    matched() {\r\n      return this.$route.matched.filter(route => route.path && route.path.split('/').pop())\r\n    }\r\n  }\r\n}\r\n</script>\r\n<style module>\r\n.breadcrumbs * {\r\n  flex-wrap: nowrap;\r\n}\r\n</style>\r\n"]
    },
    media: undefined
  });
  Object.defineProperty(this, "$style", {
    value: {
      "breadcrumbs": "src-modules-Core-components-breadcrumbs-1za-"
    }
  });
};
/* scoped */


var __vue_scope_id__ = undefined;
/* module identifier */

var __vue_module_identifier__ = undefined;
/* functional template */

var __vue_is_functional_template__ = false;
/* component normalizer */

function __vue_normalize__(template, style, script, scope, functional, moduleIdentifier, createInjector, createInjectorSSR) {
  var component = (typeof script === 'function' ? script.options : script) || {}; // For security concerns, we use only base name in production mode.

  component.__file = "C:\\www\\YetiForceCRM\\public_html\\src\\modules\\Core\\components\\YfBreadcrumbs.vue";

  if (!component.render) {
    component.render = template.render;
    component.staticRenderFns = template.staticRenderFns;
    component._compiled = true;
    if (functional) component.functional = true;
  }

  component._scopeId = scope;

  if (true) {
    var hook;

    if (false) {
      // In SSR.
      hook = function hook(context) {
        // 2.3 injection
        context = context || // cached call
        this.$vnode && this.$vnode.ssrContext || // stateful
        this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext; // functional
        // 2.2 with runInNewContext: true

        if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
          context = __VUE_SSR_CONTEXT__;
        } // inject component styles


        if (style) {
          style.call(this, createInjectorSSR(context));
        } // register component module identifier for async chunk inference


        if (context && context._registeredComponents) {
          context._registeredComponents.add(moduleIdentifier);
        }
      }; // used by ssr in case component is cached and beforeCreate
      // never gets called


      component._ssrRegister = hook;
    } else if (style) {
      hook = function hook(context) {
        style.call(this, createInjector(context));
      };
    }

    if (hook !== undefined) {
      if (component.functional) {
        // register for functional component in vue file
        var originalRender = component.render;

        component.render = function renderWithStyleInjection(h, context) {
          hook.call(context);
          return originalRender(h, context);
        };
      } else {
        // inject component registration as beforeCreate hook
        var existing = component.beforeCreate;
        component.beforeCreate = existing ? [].concat(existing, hook) : [hook];
      }
    }
  }

  return component;
}
/* style inject */


function __vue_create_injector__() {
  var head = document.head || document.getElementsByTagName('head')[0];
  var styles = __vue_create_injector__.styles || (__vue_create_injector__.styles = {});
  var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\\b/.test(navigator.userAgent.toLowerCase());
  return function addStyle(id, css) {
    if (document.querySelector('style[data-vue-ssr-id~="' + id + '"]')) return; // SSR styles are present.

    var group = isOldIE ? css.media || 'default' : id;
    var style = styles[group] || (styles[group] = {
      ids: [],
      parts: [],
      element: undefined
    });

    if (!style.ids.includes(id)) {
      var code = css.source;
      var index = style.ids.length;
      style.ids.push(id);

      if (false && css.map) {
        // https://developer.chrome.com/devtools/docs/javascript-debugging
        // this makes source maps inside style tags work properly in Chrome
        code += '\n/*# sourceURL=' + css.map.sources[0] + ' */'; // http://stackoverflow.com/a/26603875

        code += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(css.map)))) + ' */';
      }

      if (isOldIE) {
        style.element = style.element || document.querySelector('style[data-group=' + group + ']');
      }

      if (!style.element) {
        var el = style.element = document.createElement('style');
        el.type = 'text/css';
        if (css.media) el.setAttribute('media', css.media);

        if (isOldIE) {
          el.setAttribute('data-group', group);
          el.setAttribute('data-next-index', '0');
        }

        head.appendChild(el);
      }

      if (isOldIE) {
        index = parseInt(style.element.getAttribute('data-next-index'));
        style.element.setAttribute('data-next-index', index + 1);
      }

      if (style.element.styleSheet) {
        style.parts.push(code);
        style.element.styleSheet.cssText = style.parts.filter(Boolean).join('\n');
      } else {
        var textNode = document.createTextNode(code);
        var nodes = style.element.childNodes;
        if (nodes[index]) style.element.removeChild(nodes[index]);
        if (nodes.length) style.element.insertBefore(textNode, nodes[index]);else style.element.appendChild(textNode);
      }
    }
  };
}
/* style inject SSR */


export default __vue_normalize__({
  render: __vue_render__,
  staticRenderFns: __vue_staticRenderFns__
}, __vue_inject_styles__, __vue_script__, __vue_scope_id__, __vue_is_functional_template__, __vue_module_identifier__, __vue_create_injector__, undefined);