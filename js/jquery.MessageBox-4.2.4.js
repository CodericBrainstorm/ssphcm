// JavaScript Document
(function($){
	$.widget('ui.MessageBox', {
		widgetEventPrefix:'r',
		options:{
			messageText:'',
			nDialgoBoxType:0,
			cTitleBarText:'',
			nTimeout:-1
		},
		_init:function(){
			var object=this, element=this.element;
			$(element).append($("<div/>",{id:'ui.MessageBox-icon', css:{float:'left', width:'40px', height:'40px'}})).append($("<div/>",{id:'ui.MessageBox-message', css:{float:'left', width:'40px', height:'40px'}}));
			this._addIcons()._addButtons();
			return this;
		},
		_create:function(){
			var object=this, element=this.element;
			$(element).dialog({title:this.options.cTitleBarText, autoOpen:false, closeText:'Cerrar', draggable:false, modal:true, resizable:false, buttons:{Aceptar:function(){ $(this).dialog("close");}}});
		},show:function(){
			console.log(this.options);
		},
		_setOptions:function(options){
			var key;
			for (key in options ) {
				this._setOption(key, options[ key ] );
			}
			this._addIcons()._addButtons();
			return this;
		},
		_setOption:function(name, value){
			var object=this, element=this.element;
			switch(name){
				case "show":
					$(element).dialog("open");
				break;
			}
			$.Widget.prototype._setOption.apply(this, arguments);
			return this;
		},
		_addText:function(){
			$("#ui.MessageBox-message");
		},
		_addButtons:function(){
			//console.log("_addButtons");
			
		},
		_addIcons:function(){
			//console.log("_addIcons");
			
		},
		destroy:function(){
			$.Widget.prototype.destroy.call(this);
		}
	});
}(jQuery))
//16-21
//32-37
//48-53
//64-69