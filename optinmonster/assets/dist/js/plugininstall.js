!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(r,o,function(e){return t[e]}.bind(null,o));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=216)}({0:function(t,e){var n=t.exports={version:"2.6.9"};"number"==typeof __e&&(__e=n)},216:function(t,e,n){"use strict";var r,o=n(217),i=(r=o)&&r.__esModule?r:{default:r};window.OMAPI_Plugins=window.OMAPI_Plugins||{},function(t,e,n,r,o){r.handleSubmission=function(e){if(e.preventDefault(),!r.pluginData.status)throw new Error("Missing Plugin Data");var o=n(".button-install"),u=n(".button-activate"),a=o.html(),l=u.html();o.html(o.data("actiontext")),u.html(u.data("actiontext")),n("#om-plugin-alerts").hide(),n.ajax({type:"POST",beforeSend:function(t){t.setRequestHeader("X-WP-Nonce",r.restNonce)},url:r.restUrl+"omapp/v1/plugins/",data:{id:r.pluginData.id,actionNonce:r.actionNonce},success:function(e){t.location.reload()},error:function(t,e,s){o.html(a),u.html(l);var c="Something went wrong!";if(t.responseJSON&&t.responseJSON.message&&(c+="<br>Error found: "+t.responseJSON.message),t.responseJSON&&t.responseJSON.data)try{c+="<br>(data: "+(0,i.default)(t.responseJSON.data)+")"}catch(t){}var f=r.pluginData.installed?"activate":"install";console.error("Could not "+f+" the "+r.pluginData.name+" plugin",{jqXHR:t,textStatus:e,errorThrown:s}),n("#om-plugin-alerts").show().html(n("<p/>").html(c))}})},r.init=function(){n("body").on("submit",".install-plugin-form",r.handleSubmission)},n(r.init)}(window,document,jQuery,window.OMAPI_Plugins)},217:function(t,e,n){t.exports={default:n(218),__esModule:!0}},218:function(t,e,n){var r=n(0),o=r.JSON||(r.JSON={stringify:JSON.stringify});t.exports=function(t){return o.stringify.apply(o,arguments)}}});