/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
import e from"./Items/Item.vue.js";export default(function(e,t,n,s,i,r,o,a){const d=("function"==typeof n?n.options:n)||{};d.__file="LeftMenu.vue",d.render||(d.render=e.render,d.staticRenderFns=e.staticRenderFns,d._compiled=!0,i&&(d.functional=!0)),d._scopeId=s;{let e;if(t&&(e=function(e){t.call(this,o(e))}),void 0!==e)if(d.functional){const t=d.render;d.render=function(n,s){return e.call(s),t(n,s)}}else{const t=d.beforeCreate;d.beforeCreate=t?[].concat(t,e):[e]}}return d}({render:function(){var e=this.$createElement,t=this._self._c||e;return t("q-list",this._l(this.items,function(e){return t("menu-item",{key:e.id,attrs:{item:e}})}),1)},staticRenderFns:[]},function(e){e&&(e("data-v-3ecbb33c_0",{source:"",map:void 0,media:void 0}),Object.defineProperty(this,"$style",{value:{}}))},{name:"Core.Left.Menu",components:{MenuItem:e},data:()=>({userName:"User Name",companyName:"Company Name"}),computed:{items(){return this.$store.state.Core.Menu.items}}},void 0,!1,0,function e(){const t=document.head||document.getElementsByTagName("head")[0],n=e.styles||(e.styles={}),s="undefined"!=typeof navigator&&/msie [6-9]\\b/.test(navigator.userAgent.toLowerCase());return function(e,i){if(document.querySelector('style[data-vue-ssr-id~="'+e+'"]'))return;const r=s?i.media||"default":e,o=n[r]||(n[r]={ids:[],parts:[],element:void 0});if(!o.ids.includes(e)){let n=i.source,a=o.ids.length;if(o.ids.push(e),i.map&&(n+="\n/*# sourceURL="+i.map.sources[0]+" */",n+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(i.map))))+" */"),s&&(o.element=o.element||document.querySelector("style[data-group="+r+"]")),!o.element){const e=o.element=document.createElement("style");e.type="text/css",i.media&&e.setAttribute("media",i.media),s&&(e.setAttribute("data-group",r),e.setAttribute("data-next-index","0")),t.appendChild(e)}if(s&&(a=parseInt(o.element.getAttribute("data-next-index")),o.element.setAttribute("data-next-index",a+1)),o.element.styleSheet)o.parts.push(n),o.element.styleSheet.cssText=o.parts.filter(Boolean).join("\n");else{const e=document.createTextNode(n),t=o.element.childNodes;t[a]&&o.element.removeChild(t[a]),t.length?o.element.insertBefore(e,t[a]):o.element.appendChild(e)}}}}));