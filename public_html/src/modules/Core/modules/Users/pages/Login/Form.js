/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */

/* script */
import actions from "/src/store/actions.js";
/**
 * @vue-data     {String} user - form data
 * @vue-data     {String} password - form data
 * @vue-data     {String} language - form data
 * @vue-data     {String} layout - form data
 * @vue-event    {Object} onSubmit - submit form event
 */

var __vue_script__ = {
  name: 'Login',
  data: function data() {
    return {
      user: '',
      password: '',
      language: this.$store.state.Core.Language.defaultLanguage,
      layout: ''
    };
  },
  methods: {
    onSubmit: function onSubmit() {
      this.$refs.user.validate();
      this.$refs.password.validate();

      if (this.$refs.user.hasError || this.$refs.password.hasError) {
        this.formHasError = true;
      } else {
        this.$store.dispatch(actions.Core.Users.login, {
          formData: {
            username: this.user,
            password: this.password,
            fingerPrint: ''
          },
          vm: this
        });
      }
    }
  }
  /* template */

};

var __vue_render__ = function __vue_render__() {
  var _vm = this;

  var _h = _vm.$createElement;

  var _c = _vm._self._c || _h;

  return _c('div', [_c('form', {
    staticClass: "col q-gutter-md q-mx-lg",
    attrs: {
      "autocomplete": _vm.$store.state.Core.Users.loginPageRememberCredentials ? 'on' : 'off'
    },
    on: {
      "submit": function submit($event) {
        $event.preventDefault();
        $event.stopPropagation();
        return _vm.onSubmit($event);
      }
    }
  }, [_c('q-input', {
    ref: "user",
    attrs: {
      "type": "text",
      "label": _vm.$t('LBL_USER'),
      "lazy-rules": "",
      "rules": [function (val) {
        return val && val.length > 0 || 'Please type something';
      }]
    },
    scopedSlots: _vm._u([{
      key: "prepend",
      fn: function fn() {
        return [_c('q-icon', {
          attrs: {
            "name": "mdi-account"
          }
        })];
      },
      proxy: true
    }]),
    model: {
      value: _vm.user,
      callback: function callback($$v) {
        _vm.user = $$v;
      },
      expression: "user"
    }
  }), _vm._v(" "), _c('q-input', {
    ref: "password",
    attrs: {
      "type": "password",
      "label": _vm.$t('Password'),
      "lazy-rules": "",
      "rules": [function (val) {
        return val && val.length > 0 || 'Please type something';
      }]
    },
    scopedSlots: _vm._u([{
      key: "prepend",
      fn: function fn() {
        return [_c('q-icon', {
          attrs: {
            "name": "mdi-lock"
          }
        })];
      },
      proxy: true
    }]),
    model: {
      value: _vm.password,
      callback: function callback($$v) {
        _vm.password = $$v;
      },
      expression: "password"
    }
  }), _vm._v(" "), _vm.$store.state.Core.Users.languageSelection ? _c('q-select', {
    attrs: {
      "options": _vm.$store.state.Core.Language.langs,
      "label": _vm.$t('LBL_CHOOSE_LANGUAGE')
    },
    scopedSlots: _vm._u([{
      key: "prepend",
      fn: function fn() {
        return [_c('q-icon', {
          attrs: {
            "name": "mdi-translate"
          }
        })];
      },
      proxy: true
    }], null, false, 1615289671),
    model: {
      value: _vm.language,
      callback: function callback($$v) {
        _vm.language = $$v;
      },
      expression: "language"
    }
  }) : _vm._e(), _vm._v(" "), _vm.$store.state.Core.Users.layoutSelection ? _c('q-select', {
    attrs: {
      "options": _vm.$store.state.Env.layouts,
      "label": _vm.$t('LBL_SELECT_LAYOUT')
    },
    scopedSlots: _vm._u([{
      key: "prepend",
      fn: function fn() {
        return [_c('q-icon', {
          attrs: {
            "name": "mdi-looks"
          }
        })];
      },
      proxy: true
    }], null, false, 2692984661),
    model: {
      value: _vm.layout,
      callback: function callback($$v) {
        _vm.layout = $$v;
      },
      expression: "layout"
    }
  }) : _vm._e(), _vm._v(" "), _c('q-btn', {
    staticClass: "full-width q-mt-lg",
    attrs: {
      "size": "lg",
      "label": _vm.$t('LBL_SIGN_IN'),
      "type": "submit",
      "color": "secondary"
    }
  }), _vm._v(" "), _vm.$store.state.Core.Users.forgotPassword ? _c('router-link', {
    staticClass: "text-secondary float-right",
    attrs: {
      "to": {
        name: 'Reminder'
      }
    }
  }, [_vm._v(_vm._s(_vm.$t('ForgotPassword')))]) : _vm._e()], 1)]);
};

var __vue_staticRenderFns__ = [];
/* style */

var __vue_inject_styles__ = undefined;
/* scoped */

var __vue_scope_id__ = "data-v-2adc3052";
/* module identifier */

var __vue_module_identifier__ = undefined;
/* functional template */

var __vue_is_functional_template__ = false;
/* component normalizer */

function __vue_normalize__(template, style, script, scope, functional, moduleIdentifier, createInjector, createInjectorSSR) {
  var component = (typeof script === 'function' ? script.options : script) || {}; // For security concerns, we use only base name in production mode.

  component.__file = "Form.vue";

  if (!component.render) {
    component.render = template.render;
    component.staticRenderFns = template.staticRenderFns;
    component._compiled = true;
    if (functional) component.functional = true;
  }

  component._scopeId = scope;

  if (false) {
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

/* style inject SSR */


export default __vue_normalize__({
  render: __vue_render__,
  staticRenderFns: __vue_staticRenderFns__
}, __vue_inject_styles__, __vue_script__, __vue_scope_id__, __vue_is_functional_template__, __vue_module_identifier__, undefined, undefined);