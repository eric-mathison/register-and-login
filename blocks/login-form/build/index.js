!function(){"use strict";var n,e={556:function(){var n=window.wp.blocks,e=window.wp.element,r=(window.wp.i18n,window.wp.blockEditor),t=window.wp.components;(0,n.registerBlockType)("register-and-login/login-form",{edit:function(n){let{attributes:r,setAttributes:o}=n;return(0,e.createElement)(t.Panel,null,(0,e.createElement)(t.TextControl,null))},save:function(){return(0,e.createElement)("div",r.useBlockProps.save())}})}},r={};function t(n){var o=r[n];if(void 0!==o)return o.exports;var i=r[n]={exports:{}};return e[n](i,i.exports,t),i.exports}t.m=e,n=[],t.O=function(e,r,o,i){if(!r){var u=1/0;for(s=0;s<n.length;s++){r=n[s][0],o=n[s][1],i=n[s][2];for(var l=!0,c=0;c<r.length;c++)(!1&i||u>=i)&&Object.keys(t.O).every((function(n){return t.O[n](r[c])}))?r.splice(c--,1):(l=!1,i<u&&(u=i));if(l){n.splice(s--,1);var a=o();void 0!==a&&(e=a)}}return e}i=i||0;for(var s=n.length;s>0&&n[s-1][2]>i;s--)n[s]=n[s-1];n[s]=[r,o,i]},t.o=function(n,e){return Object.prototype.hasOwnProperty.call(n,e)},function(){var n={826:0,46:0};t.O.j=function(e){return 0===n[e]};var e=function(e,r){var o,i,u=r[0],l=r[1],c=r[2],a=0;if(u.some((function(e){return 0!==n[e]}))){for(o in l)t.o(l,o)&&(t.m[o]=l[o]);if(c)var s=c(t)}for(e&&e(r);a<u.length;a++)i=u[a],t.o(n,i)&&n[i]&&n[i][0](),n[u[a]]=0;return t.O(s)},r=self.webpackChunkregister_and_login=self.webpackChunkregister_and_login||[];r.forEach(e.bind(null,0)),r.push=e.bind(null,r.push.bind(r))}();var o=t.O(void 0,[46],(function(){return t(556)}));o=t.O(o)}();